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
use App\Models\Story;
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

    /**
     * Livewire file upload property for repeater item images
     * @var mixed
     */
    public $uploadedItemImage = null;
    public ?string $uploadListKey = null;
    public ?int $uploadItemIndex = null;
    public string $uploadFieldKey = 'image_url';

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

            $existingSections = $product->layout_config['sections'] ?? [];
            $hasHomeBlocks = !empty($existingSections) && collect($existingSections)->contains(
                fn ($s) => is_array($s) && str_starts_with((string) ($s['type'] ?? ''), 'home_')
            );

            if ($hasHomeBlocks) {
                $this->sections = $existingSections;
            } else {
                $this->sections = static::getSorolataProductDefaults($product);
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
            $isCoreIndividual = in_array($this->slug, ['about', 'contact'], true);
            $hasHomeBlocks = !empty($this->sections) && collect($this->sections)->contains(
                fn ($s) => is_array($s) && str_starts_with((string) ($s['type'] ?? ''), 'home_')
            );

            if (empty($this->sections) || (($isHome || $isCoreIndividual) && !$hasHomeBlocks)) {
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

        if ($blockType->value === 'home_page_header') {
            $defaultSettings = [
                'bg_color' => '#F0E6D0',
                'text_color' => '#1F2A24',
                'show_pattern' => true,
                'show_band' => false,
                'breadcrumb' => $this->title ?: 'Page',
                'eyebrow' => 'Sorolata Heritage',
                'title' => $this->title ?: 'Heritage Page Title',
                'subtitle' => 'Handmade in Bangladesh. Passed down from parent to child.',
            ];
        } elseif ($blockType->value === 'home_rich_text') {
            $defaultSettings = [
                'bg_color' => '#F7F5F0',
                'text_color' => '#1F2A24',
                'eyebrow' => 'Our Craft',
                'heading' => 'Passed down from parent to child',
                'body' => 'Every piece at Sorolata is crafted slowly by hand in the villages and districts of Bangladesh.',
                'cta_text' => 'Explore Catalog →',
                'cta_href' => '/shop',
                'image_url' => '',
            ];
        } elseif ($blockType->value === 'home_product_showcase') {
            $defaultSettings = [
                'bg_color' => '#F7F5F0',
                'text_color' => '#1F2A24',
                'show_pattern' => true,
                'show_band' => true,
                'district_label' => '',
                'title' => $this->title ?: 'Heritage Piece',
                'artisan_label' => '',
                'subtitle' => '',
                'add_to_cart_text' => 'Bring it home',
                'wishlist_text' => 'Save to Wishlist',
                'wishlist_saved_text' => 'Saved in Wishlist',
                'trust_items' => [
                    ['text' => 'Made by hand'],
                    ['text' => 'Fair pay to makers'],
                    ['text' => 'Ships worldwide'],
                ],
            ];
        } elseif (str_starts_with($blockType->value, 'home_')) {
            $homeDefaults = collect(static::getSorolataHomepageSections())->keyBy('type');
            $defaultSettings = $homeDefaults[$blockType->value]['settings'] ?? [];
        } else {
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
        }

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
            $fieldKey = ($this->sections[$this->selectedIndex]['type'] ?? '') === 'home_districts'
                ? 'map_image_url'
                : 'image_url';
            $this->sections[$this->selectedIndex]['settings'][$fieldKey] = $url;
            $this->sections[$this->selectedIndex]['settings']['media_type'] = 'image';
            $this->autoSave();
            $this->statusMessage = '✓ Image uploaded successfully';
        }

        $this->uploadedImage = null;
    }

    public function removeImage(): void
    {
        if ($this->selectedIndex !== null && isset($this->sections[$this->selectedIndex])) {
            $fieldKey = ($this->sections[$this->selectedIndex]['type'] ?? '') === 'home_districts'
                ? 'map_image_url'
                : 'image_url';
            $this->sections[$this->selectedIndex]['settings'][$fieldKey] = '';
            $this->autoSave();
            $this->statusMessage = 'Image removed';
        }
    }

    public function setItemUploadTarget(string $listKey, int $index, string $fieldKey = 'image_url'): void
    {
        $this->uploadListKey = $listKey;
        $this->uploadItemIndex = $index;
        $this->uploadFieldKey = $fieldKey;
    }

    public function updatedUploadedItemImage(): void
    {
        if (!$this->uploadedItemImage) {
            return;
        }

        $this->validate([
            'uploadedItemImage' => 'file|mimes:jpeg,jpg,png,webp|max:10240',
        ]);

        $filename = 'craft_item_' . Str::random(12) . '.' . $this->uploadedItemImage->getClientOriginalExtension();
        $path = $this->uploadedItemImage->storeAs('builder_uploads', $filename, 'public');
        $url = '/storage/' . $path;

        if (
            $this->selectedIndex !== null
            && $this->uploadListKey !== null
            && $this->uploadItemIndex !== null
            && isset($this->sections[$this->selectedIndex]['settings'][$this->uploadListKey][$this->uploadItemIndex])
        ) {
            $this->sections[$this->selectedIndex]['settings'][$this->uploadListKey][$this->uploadItemIndex][$this->uploadFieldKey] = $url;
            $this->autoSave();
            $this->statusMessage = '✓ Item image uploaded';
        }

        $this->uploadedItemImage = null;
    }

    public function removeItemImage(string $listKey, int $index, string $fieldKey = 'image_url'): void
    {
        if (
            $this->selectedIndex !== null
            && isset($this->sections[$this->selectedIndex]['settings'][$listKey][$index])
        ) {
            $this->sections[$this->selectedIndex]['settings'][$listKey][$index][$fieldKey] = '';
            $this->autoSave();
            $this->statusMessage = 'Item image removed';
        }
    }

    public function addRepeaterItem(string $listKey): void
    {
        if ($this->selectedIndex === null || !isset($this->sections[$this->selectedIndex])) {
            return;
        }

        $type = $this->sections[$this->selectedIndex]['type'] ?? '';
        $items = $this->sections[$this->selectedIndex]['settings'][$listKey] ?? [];

        $newItem = match ($type . ':' . $listKey) {
            'home_header:nav_links' => ['label' => 'New Link', 'href' => '#products'],
            'home_hero:slides' => [
                'eyebrow' => 'BENGAL HERITAGE CRAFT',
                'headline' => 'Handcrafted with patience in Bangladesh.',
                'subheadline' => 'Every piece names its maker and the district it comes from.',
                'cta_text' => 'Shop Now →',
                'cta_href' => '/shop',
                'secondary_text' => 'Shop by District',
                'secondary_href' => '/districts',
                'image_url' => '',
                'bg' => 'linear-gradient(135deg,#1F4D3A 0%,#163829 60%,#3B2314 100%)',
            ],
            'home_hero:trust_items', 'home_product_showcase:trust_items' => ['text' => 'Authentic handmade craft'],
            'home_districts:items' => ['n' => (string) (count($items) + 1), 'name' => 'New District', 'bn' => 'নতুন জেলা', 'craft' => 'Traditional craft', 'href' => '#products', 'cta_text' => 'See products →'],
            'home_crafts:items' => ['name' => 'New Craft', 'line' => 'Handcrafted heritage', 'photo_label' => 'Photo: craft', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#5B6FAE,#26356A)', 'pattern' => 'patCloth', 'href' => '#products'],
            'home_products:items' => ['district' => 'Narayanganj', 'name' => 'New Heritage Piece', 'maker' => 'Master Artisan', 'price' => '৳ 12,000', 'old_price' => '', 'badge' => '', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#C9B48A,#8E7545)', 'pattern' => 'patCloth', 'href' => '/shop', 'button_text' => 'View Piece →'],
            'home_story_timeline:items' => ['when' => '2026', 'text' => 'A new chapter in Bangladesh’s living craft heritage.'],
            'home_makers:items' => ['name' => 'Artisan Name', 'craft' => 'Master Weaver', 'place' => 'Tangail', 'years' => '25', 'photo_label' => 'Real portrait photo', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#6E7FB8,#2A3A6B)'],
            'home_why:items' => ['title' => 'Authentic Craft', 'text' => 'Made slowly by hand using traditional methods.', 'd' => 'M12 4a8 8 0 100 16 8 8 0 000-16z'],
            'home_gifts:items' => ['name' => 'Special Gift', 'text' => 'A thoughtful handmade gift from Bengal.', 'cta_text' => 'See ideas →', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#2F6B50,#163829)', 'pattern' => 'patJaliLight', 'href' => '#products'],
            'home_reviews:items' => ['text' => 'A wonderful handcrafted piece with extraordinary detail.', 'who' => 'Customer Name, Dhaka'],
            'home_stories:items' => ['tag' => 'Craft', 'read' => '4 min read', 'title' => 'A new story from the weaving villages', 'photo_label' => 'Story photo', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#6E7FB8,#2A3A6B)', 'pattern' => 'patCloth', 'href' => '#stories'],
            'home_footer:shop_links', 'home_footer:help_links' => ['label' => 'New Link', 'href' => '#contact'],
            'home_footer:contact_lines' => ['text' => 'Email: hello@sorolata.com'],
            default => ['text' => 'New item'],
        };

        $items[] = $newItem;
        $this->sections[$this->selectedIndex]['settings'][$listKey] = $items;
        $this->autoSave();
        $this->statusMessage = 'Item added';
    }

    public static function mapProductToCardItem(Product $prod): array
    {
        $media = $prod->media->first();
        $imageUrl = $media ? url($media->file_path) : '';
        $hasDiscount = !empty($prod->compare_at_price) && $prod->compare_at_price > $prod->price;
        $discountPct = $hasDiscount ? round((($prod->compare_at_price - $prod->price) / $prod->compare_at_price) * 100) : 0;

        return [
            'product_id' => $prod->id,
            'district' => $prod->district?->name_en ?? $prod->district?->getLocalized('name') ?? 'Bangladesh',
            'name' => $prod->getLocalized('title'),
            'maker' => $prod->artisan?->getLocalized('full_name') ?? 'Master Artisan',
            'price' => '৳ ' . number_format((float) $prod->price, 0),
            'old_price' => $hasDiscount ? '৳ ' . number_format((float) $prod->compare_at_price, 0) : '',
            'badge' => $hasDiscount ? ($discountPct . '% off') : '',
            'image_url' => $imageUrl,
            'bg' => 'linear-gradient(160deg,#C9B48A,#8E7545)',
            'pattern' => 'patCloth',
            'href' => '/products/' . $prod->slug,
            'button_text' => 'View Piece →',
        ];
    }

    public function addExistingProductToSection(int $productId): void
    {
        if ($this->selectedIndex === null || !isset($this->sections[$this->selectedIndex])) {
            return;
        }
        $prod = Product::with(['artisan', 'district', 'media'])->find($productId);
        if (!$prod) {
            return;
        }
        $items = $this->sections[$this->selectedIndex]['settings']['items'] ?? [];
        $items[] = static::mapProductToCardItem($prod);
        $this->sections[$this->selectedIndex]['settings']['items'] = $items;
        $this->autoSave();
        $this->statusMessage = 'Product added to section';
    }

    public function selectExistingProductForItem(int $index, int $productId): void
    {
        if ($this->selectedIndex === null || !isset($this->sections[$this->selectedIndex]['settings']['items'][$index])) {
            return;
        }
        $prod = Product::with(['artisan', 'district', 'media'])->find($productId);
        if (!$prod) {
            return;
        }
        $this->sections[$this->selectedIndex]['settings']['items'][$index] = static::mapProductToCardItem($prod);
        $this->autoSave();
        $this->statusMessage = 'Product linked';
    }

    public static function mapArtisanToCardItem(Artisan $artisan): array
    {
        return [
            'artisan_id' => $artisan->id,
            'name' => $artisan->getLocalized('full_name'),
            'craft' => $artisan->craft_type?->title() ?? 'Master Artisan',
            'place' => $artisan->district?->name_en ?? $artisan->district?->getLocalized('name') ?? 'Bangladesh',
            'years' => (string) ($artisan->years_of_mastery ?? 25),
            'photo_label' => 'Real portrait photo',
            'image_url' => $artisan->portrait_url ?: '',
            'bg' => 'linear-gradient(160deg,#6E7FB8,#2A3A6B)',
            'href' => '/artisan/' . ($artisan->slug ?: $artisan->uuid),
        ];
    }

    public function addExistingArtisanToSection(int $artisanId): void
    {
        if ($this->selectedIndex === null || !isset($this->sections[$this->selectedIndex])) {
            return;
        }
        $artisan = Artisan::with('district')->find($artisanId);
        if (!$artisan) {
            return;
        }
        $items = $this->sections[$this->selectedIndex]['settings']['items'] ?? [];
        $items[] = static::mapArtisanToCardItem($artisan);
        $this->sections[$this->selectedIndex]['settings']['items'] = $items;
        $this->autoSave();
        $this->statusMessage = 'Artisan added to section';
    }

    public function selectExistingArtisanForItem(int $index, int $artisanId): void
    {
        if ($this->selectedIndex === null || !isset($this->sections[$this->selectedIndex]['settings']['items'][$index])) {
            return;
        }
        $artisan = Artisan::with('district')->find($artisanId);
        if (!$artisan) {
            return;
        }
        $this->sections[$this->selectedIndex]['settings']['items'][$index] = static::mapArtisanToCardItem($artisan);
        $this->autoSave();
        $this->statusMessage = 'Artisan linked';
    }

    public static function mapStoryToCardItem(Story $story): array
    {
        return [
            'story_id' => $story->id,
            'tag' => $story->getLocalized('category') ?: 'Story',
            'read' => ($story->reading_time ?: 4) . ' min read',
            'title' => $story->getLocalized('title'),
            'photo_label' => 'Story photo',
            'image_url' => $story->hero_image ?: '',
            'bg' => 'linear-gradient(160deg,#6E7FB8,#2A3A6B)',
            'pattern' => 'patCloth',
            'href' => '/stories/' . ($story->slug ?: $story->uuid),
        ];
    }

    public function addExistingStoryToSection(int $storyId): void
    {
        if ($this->selectedIndex === null || !isset($this->sections[$this->selectedIndex])) {
            return;
        }
        $story = Story::find($storyId);
        if (!$story) {
            return;
        }
        $items = $this->sections[$this->selectedIndex]['settings']['items'] ?? [];
        $items[] = static::mapStoryToCardItem($story);
        $this->sections[$this->selectedIndex]['settings']['items'] = $items;
        $this->autoSave();
        $this->statusMessage = 'Story added to section';
    }

    public function selectExistingStoryForItem(int $index, int $storyId): void
    {
        if ($this->selectedIndex === null || !isset($this->sections[$this->selectedIndex]['settings']['items'][$index])) {
            return;
        }
        $story = Story::find($storyId);
        if (!$story) {
            return;
        }
        $this->sections[$this->selectedIndex]['settings']['items'][$index] = static::mapStoryToCardItem($story);
        $this->autoSave();
        $this->statusMessage = 'Story linked';
    }

    public function removeRepeaterItem(string $listKey, int $index): void
    {
        if ($this->selectedIndex === null || !isset($this->sections[$this->selectedIndex]['settings'][$listKey][$index])) {
            return;
        }

        $items = $this->sections[$this->selectedIndex]['settings'][$listKey];
        array_splice($items, $index, 1);
        $this->sections[$this->selectedIndex]['settings'][$listKey] = array_values($items);
        $this->autoSave();
        $this->statusMessage = 'Item removed';
    }

    public function moveRepeaterItem(string $listKey, int $index, string $direction): void
    {
        if ($this->selectedIndex === null || !isset($this->sections[$this->selectedIndex]['settings'][$listKey])) {
            return;
        }

        $items = $this->sections[$this->selectedIndex]['settings'][$listKey];
        $target = $direction === 'up' ? $index - 1 : $index + 1;
        if ($target < 0 || $target >= count($items)) {
            return;
        }

        $tmp = $items[$target];
        $items[$target] = $items[$index];
        $items[$index] = $tmp;
        $this->sections[$this->selectedIndex]['settings'][$listKey] = array_values($items);
        $this->autoSave();
    }

    public function resetHomepageToDefault(): void
    {
        if ($this->productId) {
            $product = Product::with(['artisan', 'district', 'media'])->findOrFail($this->productId);
            $this->sections = static::getSorolataProductDefaults($product);
            $this->statusMessage = '✓ Reset to Sorolata Product Template';
        } elseif ($this->slug === 'home' || $this->pageType === 'home') {
            $this->sections = static::getSorolataHomepageSections();
            $this->statusMessage = '✓ Reset to Sorolata Homepage Template';
        } else {
            $this->sections = static::getSorolataPageDefaults($this->slug, $this->title);
            $this->statusMessage = '✓ Reset to Sorolata Heritage Page Template';
        }
        $this->selectedSectionId = $this->sections[0]['id'] ?? null;
        $this->autoSave();
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
            if ($type === BlockType::HOME_HERO->value && empty($sec['settings']['slides'])) {
                $sec['settings']['slides'] = static::getDefaultHeroSlides($sec['settings'] ?? []);
            }
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

    public static function getDefaultHeroSlides(array $s = []): array
    {
        return [
            [
                'headline' => !empty($s['headline']) ? $s['headline'] : 'Handmade in Bangladesh. Passed down from parent to child.',
                'subheadline' => !empty($s['subheadline']) ? $s['subheadline'] : 'Muslin, brass, clay and more. Every piece names its maker and the district it comes from.',
                'cta_text' => $s['primary_cta_text'] ?? 'Shop Now →',
                'cta_href' => $s['primary_cta_href'] ?? '/shop',
                'secondary_text' => $s['secondary_cta_text'] ?? 'Shop by District',
                'secondary_href' => $s['secondary_cta_href'] ?? '/districts',
                'image_url' => !empty($s['image_url']) ? $s['image_url'] : 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1600&auto=format&fit=crop',
                'bg' => 'linear-gradient(135deg,#1F4D3A 0%,#163829 60%,#3B2314 100%)',
            ],
            [
                'headline' => 'Woven on wooden pit looms, one thread at a time.',
                'subheadline' => 'Heirloom sarees and scarves crafted by generational weavers who memorize every floral motif by heart.',
                'cta_text' => 'Explore Cloth →',
                'cta_href' => '/shop?category=cloth',
                'secondary_text' => 'Meet Our Makers',
                'secondary_href' => '/artisans',
                'image_url' => 'https://images.unsplash.com/photo-1583391733956-6c78276477e2?q=80&w=1600&auto=format&fit=crop',
                'bg' => 'linear-gradient(135deg,#2C2240 0%,#1F4D3A 65%,#5E2618 100%)',
            ],
            [
                'headline' => 'Hand-hammered brass, ringing with generations of craft.',
                'subheadline' => 'Kettles, plates, and bowls shaped in family foundries with zero machines and pure bell-metal resonance.',
                'cta_text' => 'Shop Brassware →',
                'cta_href' => '/shop?category=brass',
                'secondary_text' => 'Explore Bogura',
                'secondary_href' => '/districts',
                'image_url' => 'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1600&auto=format&fit=crop',
                'bg' => 'linear-gradient(135deg,#4A3510 0%,#2B2118 65%,#163829 100%)',
            ],
            [
                'headline' => 'Earthy patterns from the river basins of Bengal.',
                'subheadline' => 'Thick hand-woven floor carpets and sun-baked clayware sent straight from the village workshop to your home.',
                'cta_text' => 'View All Pieces →',
                'cta_href' => '/shop',
                'secondary_text' => 'Read Stories',
                'secondary_href' => '/stories',
                'image_url' => 'https://images.unsplash.com/photo-1590736969955-71cc94901144?q=80&w=1600&auto=format&fit=crop',
                'bg' => 'linear-gradient(135deg,#5E2618 0%,#2B2118 60%,#1F4D3A 100%)',
            ],
        ];
    }

    public static function getSorolataHomepageSections(): array
    {
        return [
            [
                'id' => 'sec_home_header',
                'type' => BlockType::HOME_HEADER->value,
                'is_active' => true,
                'settings' => [
                    'show_topbar' => true,
                    'topbar_text' => 'Free insured delivery to Bangladesh, USA, UK, Canada and Europe',
                    'topbar_bg' => '#1F4D3A',
                    'topbar_color' => '#FBF6EC',
                    'bg_color' => '#F7F5F0',
                    'brand_name' => 'Sorolata',
                    'brand_bn' => 'সরলতা',
                    'brand_href' => '/',
                    'show_lang_toggle' => true,
                    'show_stitch_border' => true,
                    'search_href' => '/search',
                    'cart_href' => '/cart',
                    'nav_links' => [
                        ['label' => 'Home', 'href' => '/'],
                        ['label' => 'Shop', 'href' => '/shop'],
                        ['label' => 'Districts', 'href' => '/districts'],
                        ['label' => 'Our Makers', 'href' => '/artisans'],
                        ['label' => 'Stories', 'href' => '/stories'],
                        ['label' => 'About Us', 'href' => '/about'],
                        ['label' => 'Contact', 'href' => '/contact'],
                    ],
                ],
            ],
            [
                'id' => 'sec_home_hero',
                'type' => BlockType::HOME_HERO->value,
                'is_active' => true,
                'settings' => [
                    'bg_color' => '#F7F5F0',
                    'text_color' => '#1F2A24',
                    'primary_color' => '#1F4D3A',
                    'accent_color' => '#B8892F',
                    'show_pattern' => true,
                    'show_band' => true,
                    'slide_interval' => 5.5,
                    'headline' => 'Handmade in Bangladesh. Passed down from parent to child.',
                    'subheadline' => 'Muslin, brass, clay and more. Every piece names its maker and the district it comes from.',
                    'primary_cta_text' => 'Shop Now →',
                    'primary_cta_href' => '/shop',
                    'secondary_cta_text' => 'Shop by District',
                    'secondary_cta_href' => '/districts',
                    'trust_items' => [
                        ['text' => 'Made by hand'],
                        ['text' => 'Fair pay to makers'],
                        ['text' => 'Ships worldwide'],
                    ],
                    'image_url' => '',
                    'featured_eyebrow' => 'Featured · Narayanganj (Sonargaon)',
                    'featured_title' => 'Imperial Sonargaon Muslin Scarf',
                    'featured_price' => '৳ 18,500',
                    'featured_href' => '/shop',
                    'slides' => static::getDefaultHeroSlides(),
                ],
            ],
            [
                'id' => 'sec_home_districts',
                'type' => BlockType::HOME_DISTRICTS->value,
                'is_active' => true,
                'settings' => [
                    'bg_color' => '#F7F5F0',
                    'text_color' => '#1F2A24',
                    'map_card_bg' => '#F0E6D0',
                    'eyebrow' => 'Shop by district',
                    'heading' => 'Every district has its own craft',
                    'subtitle' => 'Tap a district to see what is made there, and who makes it.',
                    'map_image_url' => '',
                    'map_image_alt' => 'Map of Bangladesh craft districts',
                    'coming_soon_text' => 'More districts are coming soon, including clay and terracotta from the villages.',
                    'items' => [
                        ['n' => '1', 'name' => 'Narayanganj (Sonargaon)', 'bn' => 'নারায়ণগঞ্জ (সোনারগাঁ)', 'craft' => 'Muslin and Jamdani cloth', 'href' => '/districts', 'cta_text' => 'See products →'],
                        ['n' => '2', 'name' => 'Tangail', 'bn' => 'টাঙ্গাইল', 'craft' => 'Tangail sarees and silk', 'href' => '/districts', 'cta_text' => 'See products →'],
                        ['n' => '3', 'name' => 'Bogura', 'bn' => 'বগুড়া', 'craft' => 'Brass and bell-metal ware', 'href' => '/districts', 'cta_text' => 'See products →'],
                        ['n' => '4', 'name' => 'Rangpur (Nisbetganj)', 'bn' => 'রংপুর (নিসবেতগঞ্জ)', 'craft' => 'Shatranji carpets', 'href' => '/districts', 'cta_text' => 'See products →'],
                    ],
                ],
            ],
            [
                'id' => 'sec_home_crafts',
                'type' => BlockType::HOME_CRAFTS->value,
                'is_active' => true,
                'settings' => [
                    'bg_color' => '#F0E6D0',
                    'text_color' => '#1F2A24',
                    'show_pattern' => true,
                    'eyebrow' => 'Shop by craft',
                    'heading' => 'Four old crafts, still made by hand',
                    'items' => [
                        ['name' => 'Cloth', 'line' => 'Muslin, Jamdani and silk', 'photo_label' => 'Photo: cloth', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#5B6FAE,#26356A)', 'pattern' => 'patCloth', 'href' => '/shop'],
                        ['name' => 'Brass and Bell Metal', 'line' => 'Kettles, plates and bowls', 'photo_label' => 'Photo: metalwork', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#D6A94A,#7A5710)', 'pattern' => 'patBrass', 'href' => '/shop'],
                        ['name' => 'Carpets and Mats', 'line' => 'Shatranji and woven floor art', 'photo_label' => 'Photo: carpets', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#C9795A,#7C3523)', 'pattern' => 'patCarpet', 'href' => '/shop'],
                        ['name' => 'Clay and Terracotta', 'line' => 'Coming soon', 'photo_label' => 'Photo: clay pots', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#B58A6A,#5E3C28)', 'pattern' => 'patClay', 'href' => '/shop'],
                    ],
                ],
            ],
            [
                'id' => 'sec_home_products',
                'type' => BlockType::HOME_PRODUCTS->value,
                'is_active' => true,
                'settings' => [
                    'bg_color' => '#F7F5F0',
                    'text_color' => '#1F2A24',
                    'eyebrow' => 'Our pieces',
                    'heading' => 'Popular right now',
                    'cta_text' => 'View all products →',
                    'cta_href' => '/shop',
                    'items' => (function () {
                        $real = Product::with(['artisan', 'district', 'media'])
                            ->where('status', 'published')
                            ->orderByDesc('is_featured')
                            ->orderBy('id', 'asc')
                            ->take(4)
                            ->get();

                        if ($real->isNotEmpty()) {
                            return $real->map(fn ($prod) => static::mapProductToCardItem($prod))->values()->all();
                        }

                        return [
                            ['district' => 'Narayanganj (Sonargaon)', 'name' => 'Imperial Sonargaon Muslin Scarf', 'maker' => 'Alimuddin', 'price' => '৳ 18,500', 'old_price' => '৳ 22,000', 'badge' => '16% off', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#C9B48A,#8E7545)', 'pattern' => 'patCloth', 'href' => '/shop', 'button_text' => 'Bring it home'],
                            ['district' => 'Tangail', 'name' => 'Heirloom Tangail Silk Jamdani', 'maker' => 'Rabeya Begum', 'price' => '৳ 24,500', 'old_price' => '৳ 29,000', 'badge' => '16% off', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#7A4B8A,#3B1F52)', 'pattern' => 'patCloth', 'href' => '/shop', 'button_text' => 'Bring it home'],
                            ['district' => 'Bogura', 'name' => 'Hand-Chiseled Bogura Kettle', 'maker' => 'Gourango Karmakar', 'price' => '৳ 12,800', 'old_price' => '', 'badge' => '', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#D8B25A,#8A6410)', 'pattern' => 'patBrass', 'href' => '/shop', 'button_text' => 'Bring it home'],
                            ['district' => 'Rangpur (Nisbetganj)', 'name' => 'Rangpur Shatranji Carpet', 'maker' => 'Mofizul Islam', 'price' => '৳ 15,600', 'old_price' => '', 'badge' => '', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#C9795A,#6E2E1E)', 'pattern' => 'patCarpet', 'href' => '/shop', 'button_text' => 'Bring it home'],
                        ];
                    })(),
                ],
            ],
            [
                'id' => 'sec_home_story_timeline',
                'type' => BlockType::HOME_STORY_TIMELINE->value,
                'is_active' => true,
                'settings' => [
                    'bg_color' => '#163829',
                    'text_color' => '#FBF6EC',
                    'show_bands' => true,
                    'show_pattern' => true,
                    'eyebrow' => 'The story of Muslin and Jamdani',
                    'heading' => 'A cloth so fine, the world wanted it',
                    'subtitle' => 'When you buy a piece from Sorolata, you help this story go on.',
                    'cta_text' => 'Read the full story',
                    'cta_href' => '/stories',
                    'items' => [
                        ['when' => '1600s', 'text' => 'Dhaka muslin is known across the world, and loved in royal courts.'],
                        ['when' => '1800s', 'text' => 'Cheap machine-made cloth arrives. The finest muslin almost disappears.'],
                        ['when' => '2013', 'text' => 'UNESCO lists Jamdani weaving as part of the world’s cultural heritage.'],
                        ['when' => 'Today', 'text' => 'Weavers in Sonargaon and Tangail still make it by hand, one thread at a time.'],
                    ],
                ],
            ],
            [
                'id' => 'sec_home_makers',
                'type' => BlockType::HOME_MAKERS->value,
                'is_active' => true,
                'settings' => [
                    'bg_color' => '#F7F5F0',
                    'text_color' => '#1F2A24',
                    'eyebrow' => 'Our makers',
                    'heading' => 'Meet the hands behind every piece',
                    'subtitle' => 'Each maker has spent a lifetime learning their craft.',
                    'items' => (function () {
                        $realArtisans = Artisan::with('district')
                            ->where('is_verified', true)
                            ->orderBy('id', 'asc')
                            ->take(4)
                            ->get();

                        if ($realArtisans->isNotEmpty()) {
                            return $realArtisans->map(fn ($a) => static::mapArtisanToCardItem($a))->values()->all();
                        }

                        return [
                            ['name' => 'Alimuddin', 'craft' => 'Muslin weaver', 'place' => 'Narayanganj (Sonargaon)', 'years' => '42', 'photo_label' => 'Real portrait photo', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#6E7FB8,#2A3A6B)', 'href' => '/artisans'],
                            ['name' => 'Rabeya Begum', 'craft' => 'Jamdani weaver', 'place' => 'Tangail', 'years' => '38', 'photo_label' => 'Real portrait photo', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#A06AB0,#4A2862)', 'href' => '/artisans'],
                            ['name' => 'Gourango Karmakar', 'craft' => 'Bell-metal maker', 'place' => 'Bogura', 'years' => '46', 'photo_label' => 'Real portrait photo', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#C89A3A,#7A5710)', 'href' => '/artisans'],
                            ['name' => 'Mofizul Islam', 'craft' => 'Shatranji weaver', 'place' => 'Rangpur (Nisbetganj)', 'years' => '32', 'photo_label' => 'Real portrait photo', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#D98A62,#7C3523)', 'href' => '/artisans'],
                        ];
                    })(),
                ],
            ],
            [
                'id' => 'sec_home_why',
                'type' => BlockType::HOME_WHY->value,
                'is_active' => true,
                'settings' => [
                    'bg_color' => '#F0E6D0',
                    'text_color' => '#1F2A24',
                    'show_pattern' => true,
                    'heading' => 'Why people buy from Sorolata',
                    'items' => [
                        ['title' => 'Made by hand', 'text' => 'No factory. Each piece is made slowly by one maker.', 'd' => 'M4 6h16M4 12h16M4 18h16M8 4v16M16 4v16'],
                        ['title' => 'Fair pay to makers', 'text' => 'The people who make your piece are paid fairly.', 'd' => 'M12 4a8 8 0 100 16 8 8 0 000-16zM12 8v8M9.5 10.5h3.5a1.5 1.5 0 010 3h-3a1.5 1.5 0 000 3h3.5'],
                        ['title' => 'Know where it is from', 'text' => 'Every piece shows its maker and its district.', 'd' => 'M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11zM12 12a2 2 0 100-4 2 2 0 000 4z'],
                        ['title' => 'Safe delivery', 'text' => 'Packed with care and insured, to Bangladesh and abroad.', 'd' => 'M3 8l9-5 9 5v8l-9 5-9-5V8zM3 8l9 5 9-5M12 13v8'],
                    ],
                ],
            ],
            [
                'id' => 'sec_home_gifts',
                'type' => BlockType::HOME_GIFTS->value,
                'is_active' => true,
                'settings' => [
                    'bg_color' => '#F7F5F0',
                    'text_color' => '#1F2A24',
                    'eyebrow' => 'Gift ideas',
                    'heading' => 'A gift with a story inside',
                    'subtitle' => 'Every piece arrives with the name of its maker.',
                    'items' => [
                        ['name' => 'Eid gifts', 'text' => 'Silk, scarves and brass for the ones you love.', 'cta_text' => 'See ideas →', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#2F6B50,#163829)', 'pattern' => 'patJaliLight', 'href' => '/shop'],
                        ['name' => 'Pohela Boishakh', 'text' => 'Bring red and white colour and craft into the new year.', 'cta_text' => 'See ideas →', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#C9654A,#7C2E1E)', 'pattern' => 'patCloth', 'href' => '/shop'],
                        ['name' => 'Wedding gifts', 'text' => 'A gift that will be kept for a lifetime.', 'cta_text' => 'See ideas →', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#6E7FB8,#2A3A6B)', 'pattern' => 'patCarpet', 'href' => '/shop'],
                        ['name' => 'Gifts from abroad', 'text' => 'Send a piece of home to family in Bangladesh, or to yourself.', 'cta_text' => 'See ideas →', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#B8892F,#6B4A0D)', 'pattern' => 'patBrass', 'href' => '/shop'],
                    ],
                ],
            ],
            [
                'id' => 'sec_home_reviews',
                'type' => BlockType::HOME_REVIEWS->value,
                'is_active' => true,
                'settings' => [
                    'bg_color' => '#F0E6D0',
                    'text_color' => '#1F2A24',
                    'eyebrow' => 'Customer reviews',
                    'heading' => 'What our customers say',
                    'note' => 'These are empty boxes for the demo. Fill them with real reviews and photos from your buyers.',
                    'items' => [
                        ['text' => 'A short review from a customer in Bangladesh goes here.', 'who' => 'Customer name, City'],
                        ['text' => 'A short review from a customer abroad goes here.', 'who' => 'Customer name, Country'],
                        ['text' => 'A photo of the piece in a customer’s home goes here, with a few words.', 'who' => 'Customer name, City'],
                    ],
                ],
            ],
            [
                'id' => 'sec_home_stories',
                'type' => BlockType::HOME_STORIES->value,
                'is_active' => true,
                'settings' => [
                    'bg_color' => '#F7F5F0',
                    'text_color' => '#1F2A24',
                    'eyebrow' => 'Stories',
                    'heading' => 'Read about the crafts',
                    'cta_text' => 'All stories →',
                    'cta_href' => '/stories',
                    'items' => (function () {
                        $realStories = Story::where('is_published', true)
                            ->orderByDesc('published_at')
                            ->orderBy('id', 'asc')
                            ->take(3)
                            ->get();

                        if ($realStories->isNotEmpty()) {
                            return $realStories->map(fn ($st) => static::mapStoryToCardItem($st))->values()->all();
                        }

                        return [
                            ['tag' => 'Cloth', 'read' => '4 min read', 'title' => 'How Jamdani and Muslin are coming back', 'photo_label' => 'Story photo', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#6E7FB8,#2A3A6B)', 'pattern' => 'patCloth', 'href' => '/stories'],
                            ['tag' => 'History', 'read' => '7 min read', 'title' => 'Phuti Karpas: the lost cotton behind fine muslin', 'photo_label' => 'Story photo', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#C89A3A,#7A5710)', 'pattern' => 'patBrass', 'href' => '/stories'],
                            ['tag' => 'Weaving', 'read' => '5 min read', 'title' => 'How weavers remember Jamdani patterns without drawing them', 'photo_label' => 'Story photo', 'image_url' => '', 'bg' => 'linear-gradient(160deg,#D98A62,#7C3523)', 'pattern' => 'patCarpet', 'href' => '/stories'],
                        ];
                    })(),
                ],
            ],
            [
                'id' => 'sec_home_newsletter',
                'type' => BlockType::HOME_NEWSLETTER->value,
                'is_active' => true,
                'settings' => [
                    'bg_color' => '#F7F5F0',
                    'card_bg' => '#1F4D3A',
                    'heading' => 'Get updates from Sorolata',
                    'subtitle' => 'We write when a maker finishes a new batch. No spam.',
                    'email_label' => 'Your email',
                    'email_placeholder' => 'name@example.com',
                    'button_text' => 'Join',
                ],
            ],
            [
                'id' => 'sec_home_footer',
                'type' => BlockType::HOME_FOOTER->value,
                'is_active' => true,
                'settings' => [
                    'bg_color' => '#163829',
                    'brand_name' => 'Sorolata',
                    'brand_bn' => 'সরলতা',
                    'tagline' => 'Handmade things from the districts of Bangladesh, sent from the maker to your home.',
                    'col1_title' => 'Shop',
                    'shop_links' => [
                        ['label' => 'Home', 'href' => '/'],
                        ['label' => 'All products', 'href' => '/shop'],
                        ['label' => 'By district', 'href' => '/districts'],
                        ['label' => 'Our makers', 'href' => '/artisans'],
                        ['label' => 'Stories', 'href' => '/stories'],
                        ['label' => 'Wishlist', 'href' => '/wishlist'],
                    ],
                    'col2_title' => 'Help',
                    'help_links' => [
                        ['label' => 'Track your order', 'href' => '/track-order'],
                        ['label' => 'My account', 'href' => '/account'],
                        ['label' => 'Delivery and returns', 'href' => '/contact'],
                        ['label' => 'FAQ & About', 'href' => '/about'],
                        ['label' => 'Contact us', 'href' => '/contact'],
                    ],
                    'col3_title' => 'Talk to us',
                    'contact_lines' => [
                        ['text' => 'WhatsApp: Chat with Sorolata', 'href' => '/contact'],
                        ['text' => 'Email: hello@sorolata.com', 'href' => 'mailto:hello@sorolata.com'],
                        ['text' => 'Facebook: /sorolatabangladesh', 'href' => 'https://facebook.com/sorolatabangladesh'],
                    ],
                    'copyright_text' => '© 2026 Sorolata. All rights reserved.',
                    'payment_text' => 'We accept bKash, Nagad, Visa and Mastercard',
                ],
            ],
        ];
    }

    public static function getSorolataProductDefaults(Product $product): array
    {
        $districtName = $product->district?->name_en ?? $product->district?->getLocalized('name') ?? 'Bangladesh';
        $artisanName = $product->artisan?->getLocalized('full_name') ?? 'Master Maker';
        $body = trim(implode("\n\n", array_filter([
            $product->getLocalized('description'),
            $product->getLocalized('heritage_story'),
        ])));

        $sections = [
            [
                'id' => 'sec_prod_showcase_' . uniqid(),
                'type' => BlockType::HOME_PRODUCT_SHOWCASE->value,
                'is_active' => true,
                'settings' => [
                    'bg_color' => '#F7F5F0',
                    'text_color' => '#1F2A24',
                    'show_pattern' => true,
                    'show_band' => true,
                    'slide_interval' => 5.5,
                    'district_label' => $districtName,
                    'title' => $product->getLocalized('title'),
                    'artisan_label' => $artisanName,
                    'subtitle' => $product->getLocalized('subtitle') ?? '',
                    'add_to_cart_text' => 'Bring it home',
                    'wishlist_text' => 'Save to Wishlist',
                    'wishlist_saved_text' => 'Saved in Wishlist',
                    'trust_items' => [
                        ['text' => 'Made by hand'],
                        ['text' => 'Fair pay to makers'],
                        ['text' => 'Ships worldwide'],
                    ],
                ],
            ],
        ];

        if ($body !== '') {
            $sections[] = [
                'id' => 'sec_prod_about_' . uniqid(),
                'type' => BlockType::HOME_RICH_TEXT->value,
                'is_active' => true,
                'settings' => [
                    'bg_color' => '#F7F5F0',
                    'text_color' => '#1F2A24',
                    'eyebrow' => 'Craft & Details',
                    'heading' => 'About this piece',
                    'body' => $body,
                    'cta_text' => '',
                    'cta_href' => '/shop',
                    'image_url' => '',
                ],
            ];
        }

        return $sections;
    }

    public static function getSorolataPageDefaults(string $slug, string $title = 'Sorolata'): array
    {
        $home = collect(static::getSorolataHomepageSections())->keyBy('type');

        if ($slug === 'about') {
            return [
                [
                    'id' => 'sec_page_header_' . uniqid(),
                    'type' => BlockType::HOME_PAGE_HEADER->value,
                    'is_active' => true,
                    'settings' => [
                        'bg_color' => '#F0E6D0',
                        'text_color' => '#1F2A24',
                        'show_pattern' => true,
                        'show_band' => true,
                        'breadcrumb' => 'About Us',
                        'eyebrow' => 'Heritage Manifesto',
                        'title' => 'Craft as a Sacred Living Continuum',
                        'subtitle' => 'We believe that true luxury is not machine precision; it is the patient, deliberate human touch passed down from parent to child.',
                    ],
                ],
                [
                    'id' => 'sec_rich_' . uniqid(),
                    'type' => BlockType::HOME_RICH_TEXT->value,
                    'is_active' => true,
                    'settings' => [
                        'bg_color' => '#F7F5F0',
                        'text_color' => '#1F2A24',
                        'eyebrow' => 'Our Mission',
                        'heading' => 'Rescuing What Power Looms Erased',
                        'body' => "Every piece at Sorolata names its maker and the district it comes from.\n\nFrom the humid morning river mist of Sonargaon to the bell-metal foundries of Bogura, we partner directly with master artisans so their craft—and their families—thrive.",
                        'cta_text' => 'Meet Our Makers →',
                        'cta_href' => '/artisans',
                        'image_url' => '',
                    ],
                ],
                array_merge($home[BlockType::HOME_STORY_TIMELINE->value], ['id' => 'sec_timeline_' . uniqid()]),
                array_merge($home[BlockType::HOME_WHY->value], ['id' => 'sec_why_' . uniqid()]),
                array_merge($home[BlockType::HOME_NEWSLETTER->value], ['id' => 'sec_newsletter_' . uniqid()]),
            ];
        }

        if ($slug === 'contact') {
            return [
                [
                    'id' => 'sec_page_header_' . uniqid(),
                    'type' => BlockType::HOME_PAGE_HEADER->value,
                    'is_active' => true,
                    'settings' => [
                        'bg_color' => '#F0E6D0',
                        'text_color' => '#1F2A24',
                        'show_pattern' => true,
                        'show_band' => false,
                        'breadcrumb' => 'Contact',
                        'eyebrow' => 'Curator Concierge',
                        'title' => 'Connect With Our Atelier',
                        'subtitle' => 'Whether you wish to commission a family heirloom, verify a district piece, or arrange international delivery—our desk is at your service.',
                    ],
                ],
                [
                    'id' => 'sec_rich_' . uniqid(),
                    'type' => BlockType::HOME_RICH_TEXT->value,
                    'is_active' => true,
                    'settings' => [
                        'bg_color' => '#F7F5F0',
                        'text_color' => '#1F2A24',
                        'eyebrow' => 'Dhaka & Worldwide',
                        'heading' => 'Direct Support for Collectors',
                        'body' => "Reach us on WhatsApp, phone, or email for custom weaving requests, wedding gifting, and insured courier tracking to Bangladesh, USA, UK, Canada, and Europe.",
                        'cta_text' => 'Track Your Order →',
                        'cta_href' => '/track-order',
                        'image_url' => '',
                    ],
                ],
                array_merge($home[BlockType::HOME_WHY->value], ['id' => 'sec_why_' . uniqid()]),
                array_merge($home[BlockType::HOME_NEWSLETTER->value], ['id' => 'sec_newsletter_' . uniqid()]),
            ];
        }

        return [
            [
                'id' => 'sec_page_header_' . uniqid(),
                'type' => BlockType::HOME_PAGE_HEADER->value,
                'is_active' => true,
                'settings' => [
                    'bg_color' => '#F0E6D0',
                    'text_color' => '#1F2A24',
                    'show_pattern' => true,
                    'show_band' => false,
                    'breadcrumb' => $title,
                    'eyebrow' => 'Sorolata Heritage',
                    'title' => $title,
                    'subtitle' => 'Handmade in Bangladesh. Passed down from parent to child.',
                ],
            ],
            [
                'id' => 'sec_rich_' . uniqid(),
                'type' => BlockType::HOME_RICH_TEXT->value,
                'is_active' => true,
                'settings' => [
                    'bg_color' => '#F7F5F0',
                    'text_color' => '#1F2A24',
                    'eyebrow' => 'Story & Craft',
                    'heading' => $title,
                    'body' => 'Every piece names its maker and the district it comes from.',
                    'cta_text' => 'Shop Collection →',
                    'cta_href' => '/shop',
                    'image_url' => '',
                ],
            ],
            array_merge($home[BlockType::HOME_PRODUCTS->value], ['id' => 'sec_products_' . uniqid()]),
            array_merge($home[BlockType::HOME_NEWSLETTER->value], ['id' => 'sec_newsletter_' . uniqid()]),
        ];
    }

    protected function loadDefaultPageSections(Page $page): void
    {
        if ($page->slug === 'home' || $page->page_type === 'home') {
            $this->sections = static::getSorolataHomepageSections();
            return;
        }

        $this->sections = static::getSorolataPageDefaults($page->slug, $page->title);
        return;

        if (false) {
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
            'catalogProducts' => Product::select('id', 'title', 'slug', 'price')->orderBy('title')->take(100)->get(),
            'catalogArtisans' => Artisan::with('district')->orderBy('full_name')->take(100)->get(),
            'catalogStories' => Story::select('id', 'title', 'slug', 'category')->orderBy('title')->take(100)->get(),
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
