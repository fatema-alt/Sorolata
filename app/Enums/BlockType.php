<?php

declare(strict_types=1);

namespace App\Enums;

enum BlockType: string
{
    // Full Pre-built Architectural Blocks
    case HERO_CINEMATIC = 'hero_cinematic';
    case ARTISAN_SPOTLIGHT = 'artisan_spotlight';
    case HERITAGE_PROVENANCE = 'heritage_provenance';
    case INTERACTIVE_GALLERY = 'interactive_gallery';
    case SPECS_ANATOMY = 'specs_anatomy';
    case STICKY_PURCHASE_BOX = 'sticky_purchase_box';
    case EDITORIAL_NARRATIVE = 'editorial_narrative';
    case TESTIMONIAL_EDITORIAL = 'testimonial_editorial';
    case ACCORDION_DETAILS = 'accordion_details';
    case CURATED_PAIRING = 'curated_pairing';

    // Storefront Core Architectural Blocks
    case FEATURED_COLLECTIONS = 'featured_collections';
    case CATEGORY_GRID = 'category_grid';
    case PRODUCT_CAROUSEL = 'product_carousel';
    case BEST_SELLERS = 'best_sellers';
    case EDITOR_CHOICE = 'editor_choice';
    case EDITORIAL_MANIFESTO = 'editorial_manifesto';

    // Shajgoj Content-to-Commerce Magazine Blocks
    case MAGAZINE_HERO_GRID = 'magazine_hero_grid';
    case TOPIC_BUBBLES = 'topic_bubbles';
    case EDITORIAL_FEED_SIDEBAR = 'editorial_feed_sidebar';
    case SHOPPABLE_PRODUCT_SHELF = 'shoppable_product_shelf';
    case VIDEO_REELS_SHOWCASE = 'video_reels_showcase';
    case HERITAGE_CLUB_NEWSLETTER = 'heritage_club_newsletter';

    // Atomic Modular Elements (Elementor Widgets)
    case ELEMENT_HEADING = 'element_heading';
    case ELEMENT_PARAGRAPH = 'element_paragraph';
    case ELEMENT_IMAGE = 'element_image';
    case ELEMENT_VIDEO = 'element_video';
    case ELEMENT_BUTTON = 'element_button';
    case ELEMENT_PRODUCT_CARD = 'element_product_card';
    case ELEMENT_ARTISAN_QUOTE = 'element_artisan_quote';
    case ELEMENT_DIVIDER = 'element_divider';

    // Sorolata Heritage Homepage Blocks
    case HOME_HEADER = 'home_header';
    case HOME_HERO = 'home_hero';
    case HOME_DISTRICTS = 'home_districts';
    case HOME_CRAFTS = 'home_crafts';
    case HOME_PRODUCTS = 'home_products';
    case HOME_STORY_TIMELINE = 'home_story_timeline';
    case HOME_MAKERS = 'home_makers';
    case HOME_WHY = 'home_why';
    case HOME_GIFTS = 'home_gifts';
    case HOME_REVIEWS = 'home_reviews';
    case HOME_STORIES = 'home_stories';
    case HOME_NEWSLETTER = 'home_newsletter';
    case HOME_FOOTER = 'home_footer';
    case HOME_PAGE_HEADER = 'home_page_header';
    case HOME_RICH_TEXT = 'home_rich_text';

    public function defaultTitle(): string
    {
        return match ($this) {
            self::HERO_CINEMATIC => 'Cinematic Hero Showcase',
            self::ARTISAN_SPOTLIGHT => 'Meet the Master Artisan',
            self::HERITAGE_PROVENANCE => 'Geographical Indication & Regional Heritage',
            self::INTERACTIVE_GALLERY => 'Curated Visual Gallery & Macro Details',
            self::SPECS_ANATOMY => 'Craft Anatomy & Material Composition',
            self::STICKY_PURCHASE_BOX => 'Editorial Purchase & Collector Box',
            self::EDITORIAL_NARRATIVE => 'Editorial Story & Cultural Context',
            self::TESTIMONIAL_EDITORIAL => 'Collector Impressions & Authenticity',
            self::ACCORDION_DETAILS => 'Heritage Care & Provenance FAQ',
            self::CURATED_PAIRING => 'Curated Complementary Pieces',

            // Storefront Core Architectural Blocks
            self::FEATURED_COLLECTIONS => 'Curated Collections Showcase',
            self::CATEGORY_GRID => 'Shop by Traditional Guild Grid',
            self::PRODUCT_CAROUSEL => 'New Arrivals Product Carousel',
            self::BEST_SELLERS => 'Archival Best Sellers Grid',
            self::EDITOR_CHOICE => "Curator's Choice Hero Showcase",
            self::EDITORIAL_MANIFESTO => 'Living Heritage Manifesto Banner',

            // Shajgoj Content-to-Commerce Magazine Blocks
            self::MAGAZINE_HERO_GRID => 'Editorial Magazine Hero Showcase',
            self::TOPIC_BUBBLES => 'Topic & Craft Circle Highlights',
            self::EDITORIAL_FEED_SIDEBAR => 'Editorial Stories & Category Directory',
            self::SHOPPABLE_PRODUCT_SHELF => 'In-Story Shoppable Artifact Shelf',
            self::VIDEO_REELS_SHOWCASE => 'Artisan In-Motion Video Reels',
            self::HERITAGE_CLUB_NEWSLETTER => 'Guild Gazette & Newsletter Invitation',

            // Element Titles
            self::ELEMENT_HEADING => 'Heading Element',
            self::ELEMENT_PARAGRAPH => 'Paragraph Text Element',
            self::ELEMENT_IMAGE => 'Image Element (Upload)',
            self::ELEMENT_VIDEO => 'Video Player Element',
            self::ELEMENT_BUTTON => 'Action Button Element',
            self::ELEMENT_PRODUCT_CARD => 'Shoppable Product Card',
            self::ELEMENT_ARTISAN_QUOTE => 'Artisan Quote & Atelier Seal',
            self::ELEMENT_DIVIDER => 'Ornamental Divider & Spacer',

            // Sorolata Heritage Homepage Blocks
            self::HOME_HEADER => 'Top Bar & Navigation Header',
            self::HOME_HERO => 'Hero Showcase & Arch Frame',
            self::HOME_DISTRICTS => 'Shop by District & Map',
            self::HOME_CRAFTS => 'Shop by Craft Grid',
            self::HOME_PRODUCTS => 'Popular Products Grid',
            self::HOME_STORY_TIMELINE => 'Muslin & Jamdani Timeline',
            self::HOME_MAKERS => 'Our Makers Portraits',
            self::HOME_WHY => 'Why Sorolata Pillars',
            self::HOME_GIFTS => 'Gift Ideas Grid',
            self::HOME_REVIEWS => 'Customer Reviews',
            self::HOME_STORIES => 'Craft Stories Grid',
            self::HOME_NEWSLETTER => 'Newsletter Banner',
            self::HOME_FOOTER => 'Heritage Footer',
            self::HOME_PAGE_HEADER => 'Heritage Page Header Banner',
            self::HOME_RICH_TEXT => 'Heritage Story & Media Block',
        };
    }

    public function isElement(): bool
    {
        return str_starts_with($this->value, 'element_');
    }
}
