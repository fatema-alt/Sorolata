<?php

declare(strict_types=1);

namespace App\Livewire\Builder;

use App\Enums\BlockType;
use App\Models\Artisan;
use App\Models\Category;
use App\Models\Collection;
use App\Models\District;
use App\Models\Page;
use App\Models\PageRevision;
use App\Models\PageTemplate;
use App\Models\Product;
use App\Services\Builder\BlockRegistry;
use App\Services\Builder\DynamicDataParserService;
use App\Services\Builder\LayoutEngineService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class VisualPageBuilder extends Component
{
    use WithFileUploads;

    public ?int $productId = null;
    public ?int $pageId = null;

    public string $pageType = 'product';
    public string $title = 'Untitled Page';
    public string $slug = '';

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $sections = [];
    public ?string $selectedSectionId = null;

    public string $activeViewport = 'desktop'; // desktop, tablet, mobile
    public string $previewLocale = 'bn';       // bn, en (Language preview in canvas)
    public string $activeTab = 'library';      // elements, library, templates, revisions, seo
    public string $inspectorTab = 'content';   // content, style, advanced
    public string $searchQuery = '';
    public string $statusMessage = 'All changes saved';

    /**
     * Livewire file upload property for real image uploads
     * @var mixed
     */
    public $uploadedImage = null;

    // SEO properties
    public string $seoTitle = '';
    public string $seoDescription = '';
    public string $seoCanonical = '';

    public function mount(?int $productId = null, ?int $pageId = null): void
    {
        $this->productId = $productId;
        $this->pageId = $pageId;

        if ($productId) {
            $product = Product::with(['artisan', 'district', 'media'])->findOrFail($productId);
            $this->pageType = 'product';
            $this->title = $product->title;
            $this->slug = $product->slug;

            $this->seoTitle = $product->title . ' — Bengal Heritage Guild';
            $this->seoDescription = $product->subtitle ?? '';

            if (!empty($product->layout_config) && isset($product->layout_config['sections'])) {
                $this->sections = $product->layout_config['sections'];
            } else {
                $this->loadDefaultProductSections($product);
            }
        } elseif ($pageId) {
            $page = Page::findOrFail($pageId);
            $this->pageType = $page->page_type;
            $this->title = $page->title;
            $this->slug = $page->slug;
            $this->sections = $page->layout_json['sections'] ?? [];
            $this->seoTitle = $page->seo_meta['title'] ?? $page->title;
            $this->seoDescription = $page->seo_meta['description'] ?? '';

            $isHome = ($this->slug === 'home' || $this->pageType === 'home');
            $hasAuthenticHomeBlocks = !empty($this->sections) && collect($this->sections)->pluck('type')->contains('featured_collections');

            if (empty($this->sections) || ($isHome && !$hasAuthenticHomeBlocks)) {
                $this->loadDefaultPageSections($page);
            } elseif ($isHome) {
                $this->ensureBilingualSections();
            }
        } else {
            $this->loadBlankCanvas();
        }

        $this->sanitizeSections();

        if (!empty($this->sections)) {
            $this->selectedSectionId = $this->sections[0]['id'] ?? null;
        }
    }

    public function setPreviewLocale(string $locale): void
    {
        $this->previewLocale = in_array($locale, ['en', 'bn'], true) ? $locale : 'bn';
    }

    public function sanitizeSections(): void
    {
        $this->sections = array_values(array_filter($this->sections, function ($sec) {
            return is_array($sec) && !empty($sec['type']) && !empty($sec['id']);
        }));
    }

    public function updatedSections(): void
    {
        $this->autoSave();
    }

    public function updatedSeoTitle(): void
    {
        $this->autoSave();
    }

    public function updatedSeoDescription(): void
    {
        $this->autoSave();
    }

    public function selectSection(string $sectionId): void
    {
        $this->selectedSectionId = $sectionId;
    }

    public function setViewport(string $viewport): void
    {
        $this->activeViewport = in_array($viewport, ['desktop', 'tablet', 'mobile'], true) 
            ? $viewport 
            : 'desktop';
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function setInspectorTab(string $tab): void
    {
        $this->inspectorTab = in_array($tab, ['content', 'style', 'advanced'], true) 
            ? $tab 
            : 'content';
    }

    public function addSection(string $type): void
    {
        $newId = 'sec_' . Str::random(8);
        $blockType = BlockType::tryFrom($type) ?? BlockType::HERO_CINEMATIC;

        $defaultSettings = [
            'headline' => $blockType->defaultTitle(),
            'subheadline' => 'An archival voyage into centuries of Bengali master artisans.',
            'badge_text' => 'GI Certified Heritage Artifact',
            'primary_cta_text' => 'Explore Collection',
            'primary_cta_url' => '/shop',
            'secondary_cta_text' => 'Read Artisan Story',
            'secondary_cta_url' => '/stories',
            'media_type' => 'none',
            'image_url' => '',
            'video_url' => '',
            'alignment' => 'center',
            'text_color' => '#1D1D1D',
            'headline_size' => 'text-4xl',
            'padding_top' => '64px',
            'padding_bottom' => '64px',
            'bg_color' => '#FFFFFF',
            'container_width' => 'boxed',
        ];

        $newSection = [
            'id' => $newId,
            'type' => $blockType->value,
            'is_active' => true,
            'settings' => $defaultSettings,
            'blocks' => [],
        ];

        $this->sections[] = $newSection;
        $this->selectedSectionId = $newId;
        $this->autoSave();
        $this->statusMessage = 'Section added';
    }

    public function addElement(string $elementType): void
    {
        if ($this->selectedSectionId) {
            $this->addElementToSection($this->selectedSectionId, $elementType);
            return;
        }

        if (!empty($this->sections)) {
            $this->addElementToSection($this->sections[count($this->sections) - 1]['id'], $elementType);
            return;
        }

        $newId = 'el_' . Str::random(8);
        $blockType = BlockType::tryFrom($elementType) ?? BlockType::ELEMENT_HEADING;

        $defaultSettings = [
            'headline' => match ($elementType) {
                'element_heading' => 'New Editorial Heading',
                'element_button' => 'Explore Masterpieces',
                'element_product_card' => 'GI Certified Jamdani Sari',
                'element_artisan_quote' => 'Ustad Alimullah Dewan',
                default => $blockType->defaultTitle(),
            },
            'subheadline' => match ($elementType) {
                'element_paragraph' => 'Preserving ancestral loom lineages, river basin metallurgy, and living master craftsmanship across historical Bengal.',
                'element_artisan_quote' => 'Our hands carry the memory of three centuries of loom heritage on the Shitalakshya river banks.',
                default => '',
            },
            'badge_text' => match ($elementType) {
                'element_artisan_quote' => 'Royal Weaver Guild',
                'element_product_card' => 'GI Certified',
                default => 'Heritage',
            },
            'price' => '৳ 18,500',
            'primary_cta_text' => 'Acquire Piece',
            'primary_cta_url' => '/shop',
            'media_type' => $elementType === 'element_video' ? 'video' : ($elementType === 'element_image' ? 'image' : 'none'),
            'image_url' => $elementType === 'element_product_card' ? 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600' : '',
            'video_url' => $elementType === 'element_video' ? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' : '',
            'alignment' => match ($elementType) {
                'element_paragraph' => 'left',
                'element_artisan_quote' => 'left',
                default => 'center',
            },
            'text_color' => '#1D1D1D',
            'headline_size' => match ($elementType) {
                'element_heading' => 'text-3xl',
                'element_paragraph' => 'text-sm sm:text-base',
                default => 'text-xl sm:text-2xl',
            },
            'padding_top' => '16px',
            'padding_bottom' => '16px',
            'bg_color' => '#FFFFFF',
            'container_width' => 'boxed',
        ];

        $newSection = [
            'id' => $newId,
            'type' => $blockType->value,
            'is_active' => true,
            'settings' => $defaultSettings,
            'blocks' => [],
        ];

        $this->sections[] = $newSection;
        $this->selectedSectionId = $newId;
        $this->autoSave();
        $this->statusMessage = 'Element added to canvas';
    }

    public function addElementToSection(string $sectionId, string $elementType): void
    {
        $newId = 'el_' . Str::random(8);
        $blockType = BlockType::tryFrom($elementType) ?? BlockType::ELEMENT_HEADING;

        $defaultSettings = [
            'headline' => match ($elementType) {
                'element_heading' => 'New Editorial Heading',
                'element_button' => 'Explore Masterpieces',
                'element_product_card' => 'GI Certified Jamdani Sari',
                'element_artisan_quote' => 'Ustad Alimullah Dewan',
                default => $blockType->defaultTitle(),
            },
            'subheadline' => match ($elementType) {
                'element_paragraph' => 'Preserving ancestral loom lineages, river basin metallurgy, and living master craftsmanship across historical Bengal.',
                'element_artisan_quote' => 'Our hands carry the memory of three centuries of loom heritage on the Shitalakshya river banks.',
                default => '',
            },
            'badge_text' => match ($elementType) {
                'element_artisan_quote' => 'Royal Weaver Guild',
                'element_product_card' => 'GI Certified',
                default => 'Heritage',
            },
            'price' => '৳ 18,500',
            'primary_cta_text' => 'Explore Piece',
            'primary_cta_url' => '/shop',
            'image_url' => $elementType === 'element_product_card' ? 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600' : '',
            'video_url' => $elementType === 'element_video' ? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' : '',
            'alignment' => match ($elementType) {
                'element_paragraph' => 'left',
                'element_artisan_quote' => 'left',
                default => 'center',
            },
            'text_color' => '#1D1D1D',
            'headline_size' => match ($elementType) {
                'element_heading' => 'text-2xl',
                'element_paragraph' => 'text-sm sm:text-base',
                default => 'text-lg',
            },
        ];

        $newChild = [
            'id' => $newId,
            'type' => $blockType->value,
            'is_active' => true,
            'settings' => $defaultSettings,
        ];

        foreach ($this->sections as &$sec) {
            if ($sec['id'] === $sectionId) {
                if (!isset($sec['elements']) || !is_array($sec['elements'])) {
                    $sec['elements'] = [];
                }
                $sec['elements'][] = $newChild;
                break;
            }
        }
        unset($sec);

        $this->selectedSectionId = $sectionId;
        $this->autoSave();
        $this->statusMessage = 'Element fitted inside section';
    }

    public function removeElementFromSection(string $sectionId, int $elementIndex): void
    {
        foreach ($this->sections as &$sec) {
            if ($sec['id'] === $sectionId && isset($sec['elements'][$elementIndex])) {
                array_splice($sec['elements'], $elementIndex, 1);
                break;
            }
        }
        unset($sec);

        $this->autoSave();
        $this->statusMessage = 'Element removed from section';
    }

    public function insertElementAt(string $elementType, int $targetIndex): void
    {
        $newId = 'el_' . Str::random(8);
        $blockType = BlockType::tryFrom($elementType) ?? BlockType::ELEMENT_HEADING;
        $isElement = str_starts_with($elementType, 'element_');

        $defaultSettings = [
            'headline' => match ($elementType) {
                'element_heading' => 'New Editorial Heading',
                'element_button' => 'Explore Masterpieces',
                'element_product_card' => 'GI Certified Jamdani Sari',
                'element_artisan_quote' => 'Ustad Alimullah Dewan',
                default => $blockType->defaultTitle(),
            },
            'subheadline' => match ($elementType) {
                'element_paragraph' => 'Preserving ancestral loom lineages, river basin metallurgy, and living master craftsmanship across historical Bengal.',
                'element_artisan_quote' => 'Our hands carry the memory of three centuries of loom heritage on the Shitalakshya river banks.',
                default => '',
            },
            'badge_text' => match ($elementType) {
                'element_artisan_quote' => 'Royal Weaver Guild',
                'element_product_card' => 'GI Certified',
                default => 'Heritage',
            },
            'price' => '৳ 18,500',
            'primary_cta_text' => 'Acquire Piece',
            'primary_cta_url' => '/shop',
            'secondary_cta_text' => $isElement ? '' : 'Explore Heritage',
            'secondary_cta_url' => '/stories',
            'media_type' => $elementType === 'element_video' ? 'video' : ($elementType === 'element_image' ? 'image' : 'none'),
            'image_url' => $elementType === 'element_product_card' ? 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600' : '',
            'video_url' => $elementType === 'element_video' ? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' : '',
            'alignment' => match ($elementType) {
                'element_paragraph' => 'left',
                'element_artisan_quote' => 'left',
                default => 'center',
            },
            'text_color' => '#1D1D1D',
            'headline_size' => match ($elementType) {
                'element_heading', 'hero_cinematic' => 'text-4xl sm:text-5xl',
                'element_paragraph' => 'text-base',
                default => 'text-2xl sm:text-3xl',
            },
            'padding_top' => $isElement ? '24px' : '64px',
            'padding_bottom' => $isElement ? '24px' : '64px',
            'bg_color' => '#FFFFFF',
            'container_width' => 'boxed',
        ];

        $newSection = [
            'id' => $newId,
            'type' => $blockType->value,
            'is_active' => true,
            'settings' => $defaultSettings,
            'blocks' => [],
        ];

        if ($targetIndex < 0) {
            $targetIndex = 0;
        }
        if ($targetIndex > count($this->sections)) {
            $targetIndex = count($this->sections);
        }

        array_splice($this->sections, $targetIndex, 0, [$newSection]);
        $this->selectedSectionId = $newId;
        $this->autoSave();
        $this->statusMessage = "Dropped {$blockType->defaultTitle()} at position #" . ($targetIndex + 1);
    }

    public function reorderSections(int $fromIndex, int $toIndex): void
    {
        if ($fromIndex < 0 || $fromIndex >= count($this->sections)) return;
        if ($toIndex < 0 || $toIndex >= count($this->sections)) return;
        if ($fromIndex === $toIndex) return;

        $item = array_splice($this->sections, $fromIndex, 1)[0];
        array_splice($this->sections, $toIndex, 0, [$item]);
        $this->autoSave();
        $this->statusMessage = "Section reordered";
    }

    public function updatedUploadedImage(): void
    {
        $this->validate([
            'uploadedImage' => 'file|mimes:jpeg,jpg,png,webp|max:10240', // 10MB max
        ]);

        $filename = 'craft_' . Str::random(12) . '.' . $this->uploadedImage->getClientOriginalExtension();
        $path = $this->uploadedImage->storeAs('builder_uploads', $filename, 'public');
        $url = '/storage/' . $path;

        if ($this->selectedIndex !== null && isset($this->sections[$this->selectedIndex])) {
            $this->sections[$this->selectedIndex]['settings']['image_url'] = $url;
            $this->sections[$this->selectedIndex]['settings']['media_type'] = 'image';
            $this->autoSave();
            $this->statusMessage = '✓ Image uploaded successfully';
        }

        $this->uploadedImage = null;
    }

    public function removeImage(): void
    {
        if ($this->selectedIndex !== null && isset($this->sections[$this->selectedIndex])) {
            $this->sections[$this->selectedIndex]['settings']['image_url'] = '';
            $this->autoSave();
            $this->statusMessage = 'Image removed';
        }
    }

    public function moveSection(string $sectionId, string $direction): void
    {
        $index = $this->findSectionIndex($sectionId);
        if ($index === null) return;

        $targetIndex = $direction === 'up' ? $index - 1 : $index + 1;

        if ($targetIndex >= 0 && $targetIndex < count($this->sections)) {
            $temp = $this->sections[$index];
            $this->sections[$index] = $this->sections[$targetIndex];
            $this->sections[$targetIndex] = $temp;
            $this->autoSave();
        }
    }

    public function duplicateSection(string $sectionId): void
    {
        $index = $this->findSectionIndex($sectionId);
        if ($index === null) return;

        $duplicate = $this->sections[$index];
        $duplicate['id'] = 'sec_' . Str::random(8);
        $duplicate['settings']['headline'] = ($duplicate['settings']['headline'] ?? 'Section') . ' (Copy)';

        array_splice($this->sections, $index + 1, 0, [$duplicate]);
        $this->selectedSectionId = $duplicate['id'];
        $this->autoSave();
        $this->statusMessage = 'Section duplicated';
    }

    public function toggleSection(string $sectionId): void
    {
        $index = $this->findSectionIndex($sectionId);
        if ($index === null) return;

        $this->sections[$index]['is_active'] = !($this->sections[$index]['is_active'] ?? true);
        $this->autoSave();
    }

    public function deleteSection(string $sectionId): void
    {
        $this->sections = array_values(array_filter($this->sections, fn($s) => $s['id'] !== $sectionId));
        $this->selectedSectionId = !empty($this->sections) ? $this->sections[0]['id'] : null;
        $this->autoSave();
        $this->statusMessage = 'Section removed';
    }

    public function importTemplate(int $templateId): void
    {
        $template = PageTemplate::findOrFail($templateId);
        if (isset($template->layout_json['sections']) && is_array($template->layout_json['sections'])) {
            $this->sections = $template->layout_json['sections'];
            $this->selectedSectionId = $this->sections[0]['id'] ?? null;
            $this->autoSave();
            $this->statusMessage = "Imported template: {$template->name}";
        }
    }

    public function autoSave(): void
    {
        $this->sanitizeSections();

        $layoutData = [
            'version' => 1,
            'page_type' => $this->pageType,
            'sections' => $this->sections,
        ];

        if ($this->productId) {
            $product = Product::find($this->productId);
            if ($product) {
                $product->update(['layout_config' => $layoutData]);
                PageRevision::create([
                    'product_id' => $product->id,
                    'version' => $product->layouts()->count() + 1,
                    'layout_json' => $layoutData,
                    'change_summary' => 'Auto-saved via Visual Builder',
                    'is_autosave' => true,
                ]);
            }
        } elseif ($this->pageId) {
            $page = Page::find($this->pageId);
            if ($page) {
                $page->update([
                    'layout_json' => $layoutData,
                    'seo_meta' => [
                        'title' => $this->seoTitle,
                        'description' => $this->seoDescription,
                    ],
                ]);
            }
        }

        $this->statusMessage = 'Saved ' . now()->format('H:i:s');
    }

    public function publish(): void
    {
        $this->autoSave();

        if ($this->productId) {
            Product::where('id', $this->productId)->update(['status' => 'published']);
        } elseif ($this->pageId) {
            Page::where('id', $this->pageId)->update([
                'status' => 'published',
                'published_at' => now(),
            ]);
        }

        $this->statusMessage = '✓ Published Live';
        session()->flash('success', 'Page published successfully!');
    }

    public function rollback(int $revisionId): void
    {
        $revision = PageRevision::findOrFail($revisionId);
        if (isset($revision->layout_json['sections'])) {
            $this->sections = $revision->layout_json['sections'];
            $this->selectedSectionId = $this->sections[0]['id'] ?? null;
            $this->statusMessage = "Restored to revision v{$revision->version}";
            $this->autoSave();
        }
    }

    protected function findSectionIndex(string $sectionId): ?int
    {
        foreach ($this->sections as $index => $sec) {
            if ($sec['id'] === $sectionId) {
                return $index;
            }
        }
        return null;
    }

    public function getSelectedIndexProperty(): ?int
    {
        return $this->selectedSectionId ? $this->findSectionIndex($this->selectedSectionId) : null;
    }

    public function getSelectedSectionProperty(): ?array
    {
        if (!$this->selectedSectionId) {
            return null;
        }

        foreach ($this->sections as $sec) {
            if ($sec['id'] === $this->selectedSectionId) {
                return $sec;
            }
        }

        return null;
    }

    protected function loadDefaultProductSections(Product $product): void
    {
        $this->sections = [
            [
                'id' => 'sec_hero_' . uniqid(),
                'type' => BlockType::HERO_CINEMATIC->value,
                'is_active' => true,
                'settings' => [
                    'badge_text' => 'GI Certified Heritage Artifact • 100% Handspun',
                    'headline' => '{{ product.title }}',
                    'subheadline' => '{{ product.subtitle }}',
                    'primary_cta_text' => 'Acquire Piece • {{ product.price }}',
                ],
            ],
            [
                'id' => 'sec_buy_' . uniqid(),
                'type' => BlockType::STICKY_PURCHASE_BOX->value,
                'is_active' => true,
                'settings' => [
                    'enable_floating_dock' => true,
                ],
            ],
            [
                'id' => 'sec_artisan_' . uniqid(),
                'type' => BlockType::ARTISAN_SPOTLIGHT->value,
                'is_active' => true,
                'settings' => [
                    'layout_style' => 'split_editorial',
                ],
            ],
            [
                'id' => 'sec_gallery_' . uniqid(),
                'type' => BlockType::INTERACTIVE_GALLERY->value,
                'is_active' => true,
                'settings' => [
                    'columns' => 3,
                ],
            ],
            [
                'id' => 'sec_provenance_' . uniqid(),
                'type' => BlockType::HERITAGE_PROVENANCE->value,
                'is_active' => true,
                'settings' => [
                    'show_river_basin' => true,
                ],
            ],
            [
                'id' => 'sec_specs_' . uniqid(),
                'type' => BlockType::SPECS_ANATOMY->value,
                'is_active' => true,
                'settings' => [],
            ],
            [
                'id' => 'sec_testimonial_' . uniqid(),
                'type' => BlockType::TESTIMONIAL_EDITORIAL->value,
                'is_active' => true,
                'settings' => [],
            ],
        ];
    }

    protected function loadBlankCanvas(): void
    {
        $this->sections = [
            [
                'id' => 'sec_hero_' . uniqid(),
                'type' => BlockType::HERO_CINEMATIC->value,
                'is_active' => true,
                'settings' => [
                    'badge_text' => 'New Handcrafted Collection',
                    'headline' => 'Living Heritage of Bengal',
                    'subheadline' => 'Curated handmade pieces from generational artisan guilds.',
                ],
            ],
        ];
    }

    public function getBilingualHomepageDefaults(): array
    {
        return [
            BlockType::HERO_CINEMATIC->value => [
                'badge_text' => 'BENGAL HERITAGE LIVING SANCTUARY • VOL 01',
                'badge_text_bn' => 'বেঙ্গল হেরিটেজ স্যাংকচুয়ারি • ভলিয়ম ০১',
                'headline' => 'Where Every Memory Recalls a Timeless History.',
                'headline_bn' => 'যেখানে প্রতিটি স্মৃতি বলে এক অনন্য ইতিহাস।',
                'subheadline' => 'Centuries of master weaving, acoustically resonant bell metals, and raw organic elements—preserved for those who cherish authentic human touch over mechanical assembly.',
                'subheadline_bn' => 'শতবর্ষের তাঁতশিল্প, কাঁসা-পিতলের নিখুঁত ভাস্কর্য এবং প্রাকৃতিক উপাদানের জাদু—সংরক্ষিত তাদের জন্য, যারা যান্ত্রিক উৎপাদনের চেয়ে মানুষের হাতের স্পর্শকে মূল্যবান মনে করেন।',
                'primary_cta_text' => 'Explore Archival Pieces',
                'primary_cta_text_bn' => 'সংগ্রহশালা দেখুন',
                'primary_cta_url' => '/shop',
                'secondary_cta_text' => 'Explore By District',
                'secondary_cta_text_bn' => 'জেলা ভিত্তিক অন্বেষণ',
                'secondary_cta_url' => '/districts',
                'metric_1_val' => '100%',
                'metric_1_lbl' => 'GI Provenance',
                'metric_1_lbl_bn' => 'জিআই সনদপ্রাপ্ত',
                'metric_2_val' => '64',
                'metric_2_lbl' => 'Bengal Districts',
                'metric_2_lbl_bn' => 'ঐতিহ্যবাহী জেলা',
                'metric_3_val' => 'Direct',
                'metric_3_lbl' => 'Artisan Royalty',
                'metric_3_lbl_bn' => 'কারিগরদের ন্যায্যসম্মান',
                'image_url' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1200&auto=format&fit=crop',
                'card_badge' => 'Featured Archival Piece',
                'card_badge_bn' => 'বিশেষ শিল্পকর্ম',
                'card_title' => 'Imperial Sonargaon Muslin Scarf',
                'card_title_bn' => 'সোনারগাঁয়ের শাহী মসলিন স্কার্ফ',
                'card_artisan' => 'Master Weaver Alimuddin',
                'card_artisan_bn' => 'ওস্তাদ তাঁতি আলিমুদ্দিন',
                'card_price' => '৳ 18,500',
            ],
            BlockType::FEATURED_COLLECTIONS->value => [
                'badge_text' => 'Curated Drops',
                'badge_text_bn' => 'নির্বাচিত কালেকশনসমূহ',
                'headline' => 'Featured Collections',
                'headline_bn' => 'বিশেষ কালেকশন',
                'subheadline' => 'Curated for Living Room, Dining, Bedroom & Festival Gifting',
                'subheadline_bn' => 'লিভিং রুম, ডাইনিং, বেডরুম ও উৎসবের উপহারের জন্য বিশেষভাবে কিউরেটেড',
            ],
            BlockType::CATEGORY_GRID->value => [
                'badge_text' => 'Heritage Craft Guilds',
                'badge_text_bn' => 'কারুশিল্প শাখা',
                'headline' => 'Timeless Guilds of Bengal Craftsmanship',
                'headline_bn' => 'ঐতিহ্যের শ্রেষ্ঠ কারুশিল্প সম্ভার',
                'subheadline' => 'Explore centuries-old techniques perfected across generations of master craftsmen.',
                'subheadline_bn' => 'প্রজন্ম থেকে প্রজন্মে সিদ্ধহস্ত কারিগরদের শতবর্ষী ঐতিহ্যের সন্ধান।',
            ],
            BlockType::PRODUCT_CAROUSEL->value => [
                'badge_text' => 'Fresh From Ateliers',
                'badge_text_bn' => 'নতুন সংগ্রহ',
                'headline' => 'New Arrivals & Fresh Weaves',
                'headline_bn' => 'নতুন আগমন',
                'subheadline' => 'Touch-friendly horizontal scroll →',
                'subheadline_bn' => 'হরিজন্টাল স্ক্রোল করে দেখুন →',
            ],
            BlockType::BEST_SELLERS->value => [
                'badge_text' => 'Collector Favorites',
                'badge_text_bn' => 'সংগ্রাহকদের প্রিয়',
                'headline' => 'Archival Best Sellers & Acclaimed Masterpieces',
                'headline_bn' => 'আমাদের বিশেষ সংকলন',
                'primary_cta_text' => 'View Entire Catalog',
                'primary_cta_text_bn' => 'সম্পূর্ণ ক্যাটালগ দেখুন',
            ],
            BlockType::EDITOR_CHOICE->value => [
                'badge_text' => 'Curator Special',
                'badge_text_bn' => 'কিউরেটর স্পেশাল',
                'headline' => "Editor's Choice: Heirloom Tangail Mulberry Silk Jamdani",
                'headline_bn' => 'এডিটরস চয়েস: টাঙ্গাইল পিওর মালবেরি সিল্ক জামদানি',
                'subheadline' => 'Hand-loomed supplementary weft geometric motifs on pure organic mulberry silk. Crafted over six weeks by Master Weaver Rabeya Begum in Delduar.',
                'subheadline_bn' => 'অর্গানিক মালবেরি সিল্কে হাতে বোনা জ্যামিতিক মোটিফ। দেলদুয়ারের ওস্তাদ তাঁতি রাবেয়া বেগমের ছয় সপ্তাহের সাধনার ফসল।',
                'primary_cta_text' => 'Acquire Heirloom Piece →',
                'primary_cta_text_bn' => 'সংগ্রহ করুন →',
                'price' => 24500,
                'compare_price' => 29000,
                'image_url' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1000',
            ],
            BlockType::EDITORIAL_MANIFESTO->value => [
                'badge_text' => 'Living Heritage Manifesto',
                'badge_text_bn' => 'আমাদের ঐতিহ্য ঘোষণা',
                'headline' => '"Our craftsmanship is not merely merchandise; it is the soul, breath, and eternal resilience of our ancestors. We shall never let the sacred human touch fade away."',
                'headline_bn' => '"আমাদের কারুশিল্প কেবল কোনো পণ্য নয়; এটি আমাদের পূর্বপুরুষদের আত্মা, নিঃশ্বাস এবং বেঁচে থাকার লড়াইয়ের অমর গাথা। শিল্পের এই স্পর্শ আমরা হারিয়ে যেতে দেব না।\"',
                'subheadline' => 'In an era of disposable fashion and synthetic mass replication, Sorolata stands as a living sanctuary for Bengal\'s endangered heritage crafts. By ensuring direct dignifying royalties to rural guilds, we keep Bengal\'s living legacy thriving.',
                'subheadline_bn' => 'কৃত্রিম ফাস্ট ফ্যাশন ও যান্ত্রিক প্রতিলিপির যুগে সরলতা বিলুপ্তপ্রায় ঐতিহ্যবাহী শিল্পের আশ্রয়স্থল। গ্রামীণ কারিগরদের ন্যায্য মূল্যায়ন নিশ্চিত করে আমরা বাংলার জীবন্ত ঐতিহ্যকে বাঁচিয়ে রাখি।',
                'primary_cta_text' => 'Read Full Manifesto →',
                'primary_cta_text_bn' => 'সম্পূর্ণ ম্যানিফেস্টো পড়ুন →',
                'primary_cta_url' => '/pages/about',
            ],
            BlockType::HERITAGE_PROVENANCE->value => [
                'badge_text' => 'Geographical Indication',
                'badge_text_bn' => 'ভৌগোলিক পরিচয়',
                'headline' => 'Geographical Heritage Across 64 Bengal Districts',
                'headline_bn' => 'বাংলার ৬৪ জেলার ভৌগোলিক ঐতিহ্য',
                'secondary_cta_text' => 'Explore All Districts →',
                'secondary_cta_text_bn' => 'সবগুলো জেলা দেখুন →',
            ],
            BlockType::ARTISAN_SPOTLIGHT->value => [
                'badge_text' => 'Hereditary Masters',
                'badge_text_bn' => 'বংশানুক্রমিক ওস্তাদ',
                'headline' => 'Living Legends: The Master Artisans of Bengal',
                'headline_bn' => 'জীবন্ত কিংবদন্তি: ওস্তাদ কারিগরবৃন্দ',
                'subheadline' => 'Behind every museum-grade piece stands a lifetime of discipline, ancestral knowledge, and dedicated studio stewardship.',
                'subheadline_bn' => 'প্রতিটি শিল্পকর্মের পেছনে রয়েছে একজন শিল্পীর নাম, স্টুডিও এবং আজীবনের সাধনা।',
            ],
            BlockType::SHOPPABLE_PRODUCT_SHELF->value => [
                'badge_text' => 'Fresh Creations',
                'badge_text_bn' => 'তাজা শিল্পকর্ম',
                'headline' => 'Trending Archival Artifacts',
                'headline_bn' => 'জনপ্রিয় ঐতিহ্যবাহী শিল্পকর্ম',
                'subheadline' => 'Limited pieces ready for immediate international dispatch',
                'subheadline_bn' => 'সীমিত সংস্করণের সংরক্ষিত শিল্পকর্ম, আন্তর্জাতিক ডেলিভারির জন্য প্রস্তুত',
            ],
            BlockType::TESTIMONIAL_EDITORIAL->value => [
                'badge_text' => 'Collector Impressions',
                'badge_text_bn' => 'সংগ্রাহকদের মতামত',
                'headline' => 'Reflections & Memories from Global Collectors',
                'headline_bn' => 'সংগ্রাহকদের অনুভূতি ও স্মৃতিচারণ',
                'subheadline' => 'Genuine testimonials from textile historians, interior curators, and heirloom patrons worldwide.',
                'subheadline_bn' => 'টেক্সটাইল গবেষক, ইন্টেরিয়র ডিজাইনার এবং বৈশ্বিক শিল্প সংগ্রাহকদের অকৃত্রিম প্রতিক্রিয়া।',
            ],
            BlockType::INTERACTIVE_GALLERY->value => [
                'badge_text' => 'Visual Chronicle',
                'badge_text_bn' => 'ভিজ্যুয়াল ক্রনিকল',
                'headline' => '@SorolataHeritage Visual Journal',
                'headline_bn' => '@SorolataHeritage ভিজ্যুয়াল জার্নাল',
                'subheadline' => 'Intimate moments, loom rhythms, and daily life from artisan villages across the river delta.',
                'subheadline_bn' => 'আমাদের তাঁতি ও কারিগর পল্লীর দৈনন্দিন জীবনের গল্প ও ছন্দ',
            ],
            BlockType::HERITAGE_CLUB_NEWSLETTER->value => [
                'badge_text' => "Collector's Circle",
                'badge_text_bn' => 'সংগ্রাহক সার্কেল',
                'headline' => "Join the Sorolata Collector's Guild",
                'headline_bn' => 'সংগ্রাহক সার্কেলে যোগ দিন',
                'subheadline' => 'Receive intimate archival dispatches, private atelier invitations, and priority access to limited seasonal masterworks.',
                'subheadline_bn' => 'নতুন সংগ্রহশালা উন্মোচন ও সীমিত সংস্করণের শিল্পকর্মের আগাম তথ্য পান সরাসরি।',
                'primary_cta_text' => 'Join Collector Desk',
                'primary_cta_text_bn' => 'যুক্ত হন',
            ],
        ];
    }

    public function ensureBilingualSections(): void
    {
        $defaults = $this->getBilingualHomepageDefaults();

        foreach ($this->sections as &$sec) {
            $type = $sec['type'] ?? '';
            if (isset($defaults[$type])) {
                $defSettings = $defaults[$type];
                $sec['settings'] = $sec['settings'] ?? [];

                // Fill any completely missing keys
                foreach ($defSettings as $k => $v) {
                    if (!isset($sec['settings'][$k]) || $sec['settings'][$k] === '') {
                        $sec['settings'][$k] = $v;
                    }
                }

                // If headline has Bengali characters but headline_bn was not explicitly set
                if (!empty($sec['settings']['headline']) && preg_match('/[\x{0980}-\x{09FF}]/u', (string) $sec['settings']['headline'])) {
                    if (empty($sec['settings']['headline_bn'])) {
                        $sec['settings']['headline_bn'] = $sec['settings']['headline'];
                    }
                    if ($sec['settings']['headline'] === $sec['settings']['headline_bn']) {
                        $sec['settings']['headline'] = $defSettings['headline'] ?? $sec['settings']['headline'];
                    }
                }

                // If badge_text has Bengali characters but badge_text_bn was not explicitly set
                if (!empty($sec['settings']['badge_text']) && preg_match('/[\x{0980}-\x{09FF}]/u', (string) $sec['settings']['badge_text'])) {
                    if (empty($sec['settings']['badge_text_bn'])) {
                        $sec['settings']['badge_text_bn'] = $sec['settings']['badge_text'];
                    }
                    if ($sec['settings']['badge_text'] === $sec['settings']['badge_text_bn']) {
                        $sec['settings']['badge_text'] = $defSettings['badge_text'] ?? $sec['settings']['badge_text'];
                    }
                }

                // If subheadline has Bengali characters but subheadline_bn was not explicitly set
                if (!empty($sec['settings']['subheadline']) && preg_match('/[\x{0980}-\x{09FF}]/u', (string) $sec['settings']['subheadline'])) {
                    if (empty($sec['settings']['subheadline_bn'])) {
                        $sec['settings']['subheadline_bn'] = $sec['settings']['subheadline'];
                    }
                    if ($sec['settings']['subheadline'] === $sec['settings']['subheadline_bn']) {
                        $sec['settings']['subheadline'] = $defSettings['subheadline'] ?? $sec['settings']['subheadline'];
                    }
                }

                // Primary CTA
                if (!empty($sec['settings']['primary_cta_text']) && preg_match('/[\x{0980}-\x{09FF}]/u', (string) $sec['settings']['primary_cta_text'])) {
                    if (empty($sec['settings']['primary_cta_text_bn'])) {
                        $sec['settings']['primary_cta_text_bn'] = $sec['settings']['primary_cta_text'];
                    }
                    if ($sec['settings']['primary_cta_text'] === $sec['settings']['primary_cta_text_bn']) {
                        $sec['settings']['primary_cta_text'] = $defSettings['primary_cta_text'] ?? $sec['settings']['primary_cta_text'];
                    }
                }

                // Secondary CTA
                if (!empty($sec['settings']['secondary_cta_text']) && preg_match('/[\x{0980}-\x{09FF}]/u', (string) $sec['settings']['secondary_cta_text'])) {
                    if (empty($sec['settings']['secondary_cta_text_bn'])) {
                        $sec['settings']['secondary_cta_text_bn'] = $sec['settings']['secondary_cta_text'];
                    }
                    if ($sec['settings']['secondary_cta_text'] === $sec['settings']['secondary_cta_text_bn']) {
                        $sec['settings']['secondary_cta_text'] = $defSettings['secondary_cta_text'] ?? $sec['settings']['secondary_cta_text'];
                    }
                }
            }
        }
        unset($sec);
    }

    protected function loadDefaultPageSections(Page $page): void
    {
        if ($page->slug === 'home' || $page->page_type === 'home') {
            $defaults = $this->getBilingualHomepageDefaults();
            $this->sections = [
                [
                    'id' => 'sec_hero_' . uniqid(),
                    'type' => BlockType::HERO_CINEMATIC->value,
                    'is_active' => true,
                    'settings' => $defaults[BlockType::HERO_CINEMATIC->value],
                ],
                [
                    'id' => 'sec_collections_' . uniqid(),
                    'type' => BlockType::FEATURED_COLLECTIONS->value,
                    'is_active' => true,
                    'settings' => $defaults[BlockType::FEATURED_COLLECTIONS->value],
                ],
                [
                    'id' => 'sec_categories_' . uniqid(),
                    'type' => BlockType::CATEGORY_GRID->value,
                    'is_active' => true,
                    'settings' => $defaults[BlockType::CATEGORY_GRID->value],
                ],
                [
                    'id' => 'sec_new_arrivals_' . uniqid(),
                    'type' => BlockType::PRODUCT_CAROUSEL->value,
                    'is_active' => true,
                    'settings' => $defaults[BlockType::PRODUCT_CAROUSEL->value],
                ],
                [
                    'id' => 'sec_best_sellers_' . uniqid(),
                    'type' => BlockType::BEST_SELLERS->value,
                    'is_active' => true,
                    'settings' => $defaults[BlockType::BEST_SELLERS->value],
                ],
                [
                    'id' => 'sec_editor_choice_' . uniqid(),
                    'type' => BlockType::EDITOR_CHOICE->value,
                    'is_active' => true,
                    'settings' => $defaults[BlockType::EDITOR_CHOICE->value],
                ],
                [
                    'id' => 'sec_manifesto_' . uniqid(),
                    'type' => BlockType::EDITORIAL_MANIFESTO->value,
                    'is_active' => true,
                    'settings' => $defaults[BlockType::EDITORIAL_MANIFESTO->value],
                ],
                [
                    'id' => 'sec_districts_' . uniqid(),
                    'type' => BlockType::HERITAGE_PROVENANCE->value,
                    'is_active' => true,
                    'settings' => $defaults[BlockType::HERITAGE_PROVENANCE->value],
                ],
                [
                    'id' => 'sec_artisan_' . uniqid(),
                    'type' => BlockType::ARTISAN_SPOTLIGHT->value,
                    'is_active' => true,
                    'settings' => $defaults[BlockType::ARTISAN_SPOTLIGHT->value],
                ],
                [
                    'id' => 'sec_trending_' . uniqid(),
                    'type' => BlockType::SHOPPABLE_PRODUCT_SHELF->value,
                    'is_active' => true,
                    'settings' => $defaults[BlockType::SHOPPABLE_PRODUCT_SHELF->value],
                ],
                [
                    'id' => 'sec_testimonial_' . uniqid(),
                    'type' => BlockType::TESTIMONIAL_EDITORIAL->value,
                    'is_active' => true,
                    'settings' => $defaults[BlockType::TESTIMONIAL_EDITORIAL->value],
                ],
                [
                    'id' => 'sec_gallery_' . uniqid(),
                    'type' => BlockType::INTERACTIVE_GALLERY->value,
                    'is_active' => true,
                    'settings' => $defaults[BlockType::INTERACTIVE_GALLERY->value],
                ],
                [
                    'id' => 'sec_newsletter_' . uniqid(),
                    'type' => BlockType::HERITAGE_CLUB_NEWSLETTER->value,
                    'is_active' => true,
                    'settings' => $defaults[BlockType::HERITAGE_CLUB_NEWSLETTER->value],
                ],
            ];
        } elseif ($page->slug === 'about') {
            $this->sections = [
                [
                    'id' => 'sec_hero_' . uniqid(),
                    'type' => BlockType::HERO_CINEMATIC->value,
                    'is_active' => true,
                    'settings' => [
                        'badge_text' => 'OUR LIVING MANIFESTO',
                        'headline' => 'In Quiet Reverence For The Human Touch',
                        'subheadline' => 'Preserving endangered living craft traditions of Bangladesh.',
                    ],
                ],
                [
                    'id' => 'sec_narrative_' . uniqid(),
                    'type' => BlockType::EDITORIAL_NARRATIVE->value,
                    'is_active' => true,
                    'settings' => [
                        'headline' => 'The Loom Saga & Cultural Lineage',
                        'subheadline' => 'Across the alluvial river deltas, our master artisans breathe soul into natural fibers.',
                    ],
                ],
                [
                    'id' => 'sec_artisan_' . uniqid(),
                    'type' => BlockType::ARTISAN_SPOTLIGHT->value,
                    'is_active' => true,
                    'settings' => [
                        'headline' => 'Living Masters of the Heritage Guilds',
                    ],
                ],
                [
                    'id' => 'sec_provenance_' . uniqid(),
                    'type' => BlockType::HERITAGE_PROVENANCE->value,
                    'is_active' => true,
                    'settings' => [],
                ],
            ];
        } else {
            $this->loadBlankCanvas();
        }
    }

    public function render(): View
    {
        $this->sanitizeSections();

        $templates = PageTemplate::all();
        $revisions = $this->productId 
            ? PageRevision::where('product_id', $this->productId)->latest()->take(10)->get()
            : ($this->pageId ? PageRevision::where('page_id', $this->pageId)->latest()->take(10)->get() : collect());

        $product = $this->productId ? Product::with(['artisan', 'district', 'media'])->find($this->productId) : null;

        /** @var DynamicDataParserService $parser */
        $parser = app(DynamicDataParserService::class);

        $isHomePage = ($this->slug === 'home' || $this->pageType === 'home');

        $categories = $isHomePage ? Category::withCount('products')->get() : collect();
        $districts = $isHomePage ? District::withCount('products')->take(4)->get() : collect();
        $artisans = $isHomePage ? Artisan::with(['district'])->where('is_verified', true)->take(4)->get() : collect();
        $collections = $isHomePage ? Collection::take(2)->get() : collect();
        $featuredProducts = $isHomePage ? Product::with(['artisan', 'district', 'media'])->where('status', 'published')->orderBy('is_featured', 'desc')->take(8)->get() : collect();
        $bestSellers = $isHomePage ? Product::with(['artisan', 'district', 'media'])->where('status', 'published')->take(4)->get() : collect();
        $trendingProducts = $isHomePage ? Product::with(['artisan', 'district', 'media'])->where('status', 'published')->orderBy('id', 'desc')->take(4)->get() : collect();

        return view('livewire.builder.visual-page-builder', [
            'templates' => $templates,
            'revisions' => $revisions,
            'product' => $product,
            'catalogProducts' => Product::select('id', 'title', 'slug', 'price')->take(30)->get(),
            'parser' => $parser,
            'selectedSection' => $this->selectedSection,
            'selectedIndex' => $this->selectedIndex,
            'isHomePage' => $isHomePage,
            'categories' => $categories,
            'districts' => $districts,
            'artisans' => $artisans,
            'collections' => $collections,
            'products' => $featuredProducts,
            'bestSellers' => $bestSellers,
            'trendingProducts' => $trendingProducts,
        ])->layout('layouts.builder');
    }
}
