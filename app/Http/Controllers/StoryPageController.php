<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Story;
use App\Services\Story\StoryParserService;
use Illuminate\Contracts\View\View;

class StoryPageController extends Controller
{
    public function __construct(
        protected StoryParserService $storyParser
    ) {}

    public function index(): View
    {
        $stories = Story::where('is_published', true)
            ->with(['products'])
            ->orderBy('published_at', 'desc')
            ->get();

        return view('stories.index', [
            'stories' => $stories,
        ]);
    }

    public function show(string $slug): View|\Illuminate\Http\RedirectResponse
    {
        $story = Story::where(function ($query) use ($slug): void {
                $query->where('slug', $slug)
                      ->orWhere('uuid', $slug);
            })
            ->where('is_published', true)
            ->with(['products.artisan', 'products.district', 'products.media'])
            ->firstOrFail();

        if ($slug === $story->uuid && !empty($story->slug)) {
            return redirect()->route('stories.show', $story->slug, 301);
        }

        $content = $story->getLocalized('content') ?? $story->content;
        $parsedContent = $this->storyParser->parse($content, $story);

        // Featured products from pivot or extracted from shortcodes
        $featuredProducts = $story->products;
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = $this->storyParser->extractProducts($content);
        }

        $relatedStories = Story::where('id', '!=', $story->id)
            ->where('is_published', true)
            ->take(3)
            ->get();

        return view('stories.show', [
            'story' => $story,
            'parsedContent' => $parsedContent,
            'featuredProducts' => $featuredProducts,
            'relatedStories' => $relatedStories,
        ]);
    }
}
