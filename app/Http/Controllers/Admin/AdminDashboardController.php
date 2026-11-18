<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminDashboardService;
use App\Services\Admin\CommandPaletteService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected AdminDashboardService $dashboardService,
        protected CommandPaletteService $commandService
    ) {}

    public function index(): View
    {
        $metrics = $this->dashboardService->getMetrics();

        return view('admin.dashboard', [
            'metrics' => $metrics,
        ]);
    }

    public function commandSearch(Request $request): JsonResponse
    {
        $query = (string) $request->input('q', '');
        $results = $this->commandService->search($query);

        return response()->json($results);
    }
}
