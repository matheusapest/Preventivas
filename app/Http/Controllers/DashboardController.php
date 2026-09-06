<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\GetManagerDashboardService;
use App\Services\Dashboard\GetTechnicianDashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly GetManagerDashboardService $managerDashboardService,
        private readonly GetTechnicianDashboardService $technicianDashboardService,
    ) {
    }

    /**
     * Exibe o painel principal do sistema.
     */
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $dashboard = $this->managerDashboardService->execute();

            return view(
                'dashboard.index',
                compact('dashboard')
            );
        }

        $dashboard = $this->technicianDashboardService->execute(
            $user->id
        );

        return view(
            'dashboard.technician',
            compact('dashboard')
        );
    }
}
