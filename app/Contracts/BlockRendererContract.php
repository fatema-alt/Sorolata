<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\LayoutBlockDTO;
use App\Models\Product;
use Illuminate\Contracts\View\View;

interface BlockRendererContract
{
    /**
     * Determines whether this renderer handles the given block type.
     */
    public function handles(string $blockType): bool;

    /**
     * Render the block to a Blade view or HTML string.
     */
    public function render(LayoutBlockDTO $block, Product $product): View|string;

    /**
     * Returns the JSON configuration schema for the admin builder.
     *
     * @return array<string, mixed>
     */
    public function schema(): array;
}
