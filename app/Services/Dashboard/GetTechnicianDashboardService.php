<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Services\Preventive\Execution\GetPreventiveExecutionService;

class GetTechnicianDashboardService
{
    public function __construct(
        private readonly GetPreventiveExecutionService $executionService
    ) {
    }

    /**
     * Retorna os indicadores necessários para o
     * dashboard do técnico.
     */
    public function execute(int $userId): array
    {
        $execution = $this->executionService->execute(
            $userId
        );

        return [
            'newCount' => $execution['newCount'],
            'inProgressCount' => $execution['inProgressCount'],
            'totalCount' => $execution['totalCount'],
        ];
    }
}
