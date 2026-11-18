<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\AdminSetting;
use Illuminate\Support\Facades\Cache;

class AdminSettingService
{
    protected const CACHE_KEY = 'sorolata_admin_settings_all';

    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->all();
        return $all[$key] ?? $default;
    }

    public function set(string $key, mixed $value, string $group = 'general'): void
    {
        $encoded = is_array($value) ? json_encode($value) : (string) $value;

        AdminSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $encoded, 'group' => $group]
        );

        Cache::forget(self::CACHE_KEY);
    }

    /**
     * @return array<string, mixed>
     */
    public function getGroup(string $group): array
    {
        $records = AdminSetting::where('group', $group)->get();
        $res = [];
        foreach ($records as $rec) {
            $val = $rec->value;
            $json = json_decode((string) $val, true);
            $res[$rec->key] = (json_last_error() === JSON_ERROR_NONE) ? $json : $val;
        }
        return $res;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return Cache::remember(self::CACHE_KEY, 3600, function (): array {
            $records = AdminSetting::all();
            $res = [];
            foreach ($records as $rec) {
                $val = $rec->value;
                $json = json_decode((string) $val, true);
                $res[$rec->key] = (json_last_error() === JSON_ERROR_NONE) ? $json : $val;
            }
            return $res;
        });
    }
}
