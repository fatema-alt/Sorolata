<?php

declare(strict_types=1);

namespace App\Traits;

trait HasBilingualAttributes
{
    /**
     * Get attribute based on active locale (en / bn) with graceful fallback.
     */
    public function getLocalized(string $attribute, ?string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();

        if ($locale === 'bn') {
            $bnValue = $this->getAttribute("{$attribute}_bn");
            if (!empty($bnValue)) {
                return (string) $bnValue;
            }
        }

        $val = $this->getAttribute($attribute);
        return $val !== null ? (string) $val : null;
    }
}
