<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enums\BlockType;

final readonly class LayoutBlockDTO
{
    /**
     * @param array<string, mixed> $settings
     */
    public function __construct(
        public string $id,
        public BlockType $type,
        public int $order,
        public bool $isActive,
        public array $settings = []
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['id'] ?? uniqid('blk_')),
            type: BlockType::from($data['type']),
            order: (int) ($data['order'] ?? 0),
            isActive: (bool) ($data['is_active'] ?? true),
            settings: (array) ($data['settings'] ?? [])
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type->value,
            'order' => $this->order,
            'is_active' => $this->isActive,
            'settings' => $this->settings,
        ];
    }
}
