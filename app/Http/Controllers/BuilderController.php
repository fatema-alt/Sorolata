<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Product;
use App\Services\Builder\DynamicDataParserService;
use App\Services\Builder\LayoutEngineService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BuilderController extends Controller
{
    public function __construct(
        protected LayoutEngineService $layoutEngine,
        protected DynamicDataParserService $parser
    ) {}

    /**
     * Launch the Visual Page Builder for an individual product landing page.
     */
    public function product(int $id): View
    {
        $product = Product::with(['artisan', 'district', 'media'])->findOrFail($id);

        return view('builder.editor', [
            'productId' => $product->id,
            'pageId' => null,
            'title' => "Editing: {$product->title}",
        ]);
    }

    /**
     * Launch the Visual Page Builder directly for the Homepage.
     */
    public function home(): View
    {
        $page = Page::firstOrCreate(['slug' => 'home'], [
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'Heritage Living Sanctuary (Homepage)',
            'page_type' => 'home',
            'status' => 'published',
            'version' => 1,
        ]);

        return view('builder.editor', [
            'productId' => null,
            'pageId' => $page->id,
            'title' => "Editing: {$page->title}",
        ]);
    }

    /**
     * Launch the Visual Page Builder for a generic CMS / Campaign landing page.
     */
    public function page(int $id): View
    {
        $page = Page::find($id) ?? Page::first();

        if (! $page) {
            $page = Page::create([
                'title' => 'Heritage Craft Exhibition',
                'slug' => 'heritage-craft-exhibition',
                'page_type' => 'campaign',
                'status' => 'draft',
                'layout_json' => [
                    'version' => 1,
                    'page_type' => 'campaign',
                    'sections' => [
                        [
                            'id' => 'sec_exhibit_1',
                            'type' => 'hero_cinematic',
                            'is_active' => true,
                            'settings' => [
                                'badge_text' => 'GI Certified Heritage Artifact',
                                'headline' => 'Living Traditions of Sonargaon',
                                'subheadline' => 'An archival voyage into centuries of Bengali master artisans.',
                                'primary_cta_text' => 'Explore Exhibition',
                            ],
                        ],
                    ],
                ],
                'seo_meta' => [
                    'title' => 'Heritage Craft Exhibition',
                    'description' => 'Bengal Craft Exhibition',
                ],
            ]);
        }

        return view('builder.editor', [
            'productId' => null,
            'pageId' => $page->id,
            'title' => "Editing: {$page->title}",
        ]);
    }

    /**
     * Render the public storefront landing page constructed via the JSON Builder Engine.
     */
    public function landing(string $slug): View
    {
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $sections = $page->layout_json['sections'] ?? [];
        $activeSections = array_filter($sections, fn ($s) => $s['is_active'] ?? true);

        return view('builder.landing', [
            'page' => $page,
            'sections' => $activeSections,
            'parser' => $this->parser,
        ]);
    }
}
