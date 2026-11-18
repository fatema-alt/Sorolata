<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\LayoutBlockDTO;
use App\Models\Product;
use Illuminate\Support\Collection;

interface LayoutEngineContract
{
    /**
     * Parse raw JSON layout payload into typed DTO blocks.
     *
     * @return Collection<int, LayoutBlockDTO>
     */
    public function parseBlocks(array|string|null $payload): Collection;

    /**
     * Render the entire sequence of blocks for a product.
     *
     * @return array<int, string>
     */
    public function renderProductBlocks(Product $product): array;

    /**
     * Validates a layout payload against block schemas.
     *
     * @param array<string, mixed> $payload
     * @return array<string, string> Validation errors if any
     */
    public function validatePayload(array $payload): array;
}
