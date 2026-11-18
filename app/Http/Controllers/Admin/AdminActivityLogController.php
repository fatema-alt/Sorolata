<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Contracts\View\View;

class AdminActivityLogController extends Controller
{
    public function index(): View
    {
        $logs = ActivityLog::with('user')
            ->latest('created_at')
            ->paginate(25);

        return view('admin.logs.index', [
            'logs' => $logs,
        ]);
    }
}
