<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\ArtisanStoryDTO;
use App\Models\Artisan;
use App\Models\District;

interface StorytellerContract
{
    /**
     * Build the structured narrative for an artisan.
     */
    public function getArtisanStory(Artisan $artisan): ArtisanStoryDTO;

    /**
     * Get the historical, river-basin, and craft provenance for a heritage district.
     *
     * @return array<string, mixed>
     */
    public function getDistrictProvenance(District $district): array;
}
