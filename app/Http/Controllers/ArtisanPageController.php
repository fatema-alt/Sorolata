<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Artisan;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ArtisanPageController extends Controller
{
    public function index(): View
    {
        $artisans = Artisan::with(['district', 'products.media'])
            ->where('is_verified', true)
            ->get();

        return view('artisans.index', [
            'artisans' => $artisans,
        ]);
    }

    public function show(string $uuid): View
    {
        $hasSlug = Schema::hasColumn('artisans', 'slug');

        $artisan = Artisan::with(['district', 'products.media', 'products.district'])
            ->where(function ($q) use ($uuid, $hasSlug) {
                if ($hasSlug) {
                    $q->where('slug', $uuid)->orWhere('uuid', $uuid);
                } else {
                    $q->where('uuid', $uuid);
                }
                if (is_numeric($uuid)) {
                    $q->orWhere('id', (int) $uuid);
                }
                $q->orWhere('full_name', $uuid);
            })
            ->first();

        if (!$artisan) {
            $targetSlug = Str::slug($uuid);
            $artisan = Artisan::with(['district', 'products.media', 'products.district'])
                ->get()
                ->first(fn (Artisan $a) => Str::slug((string) $a->full_name) === $targetSlug || Str::slug((string) $a->slug) === $targetSlug);
        }

        abort_if(!$artisan, 404);

        return view('artisans.show', [
            'artisan' => $artisan,
            'products' => $artisan->products,
        ]);
    }
}
