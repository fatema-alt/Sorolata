<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminMediaController extends Controller
{
    public function index(Request $request): View
    {
        $query = MediaAsset::with('attachable');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where('file_path', 'like', "%{$term}%")
                  ->orWhere('alt_text', 'like', "%{$term}%");
        }

        $assets = $query->latest('created_at')->paginate(20)->withQueryString();

        return view('admin.media.index', [
            'assets' => $assets,
        ]);
    }
}
