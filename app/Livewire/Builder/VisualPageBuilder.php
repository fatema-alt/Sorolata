<?php

declare(strict_types=1);

namespace App\Livewire\Builder;

use App\Enums\BlockType;
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
        } else {
            $this->loadBlankCanvas();
        }

        $this->sanitizeSections();

        if (!empty($this->sections)) {
            $this->selectedSectionId = $this->sections[0]['id'] ?? null;
        }
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

        return view('livewire.builder.visual-page-builder', [
            'templates' => $templates,
            'revisions' => $revisions,
            'product' => $product,
            'catalogProducts' => Product::select('id', 'title', 'slug', 'price')->take(30)->get(),
            'parser' => $parser,
            'selectedSection' => $this->selectedSection,
            'selectedIndex' => $this->selectedIndex,
        ])->layout('layouts.builder');
    }
}
