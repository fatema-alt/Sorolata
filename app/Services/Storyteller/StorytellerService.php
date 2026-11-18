<?php

declare(strict_types=1);

namespace App\Services\Storyteller;

use App\Contracts\StorytellerContract;
use App\DTO\ArtisanStoryDTO;
use App\Models\Artisan;
use App\Models\District;

class StorytellerService implements StorytellerContract
{
    public function getArtisanStory(Artisan $artisan): ArtisanStoryDTO
    {
        return new ArtisanStoryDTO(
            artisanId: $artisan->uuid,
            fullName: $artisan->full_name,
            atelierName: $artisan->atelier_name ?? "Atelier {$artisan->full_name}",
            craftType: $artisan->craft_type,
            yearsOfMastery: $artisan->years_of_mastery,
            biography: $artisan->biography ?? '',
            districtName: $artisan->district?->name_en ?? 'Heritage Cluster',
            portraitUrl: $artisan->portrait_url ?? '/images/artisan-default.jpg',
            isVerified: $artisan->is_verified,
            quote: $artisan->quote,
            awards: $artisan->awards ?? [],
            galleryImages: []
        );
    }

    public function getDistrictProvenance(District $district): array
    {
        return [
            'district_id' => $district->uuid,
            'name_en' => $district->name_en,
            'name_bn' => $district->name_bn,
            'division' => $district->division->value,
            'gi_specialty' => $district->gi_specialty,
            'historical_narrative' => $district->historical_narrative,
            'river_basin' => $district->river_basin,
            'coordinates' => [
                'latitude' => $district->latitude,
                'longitude' => $district->longitude,
            ],
            'meta' => $district->meta ?? [],
        ];
    }
}
