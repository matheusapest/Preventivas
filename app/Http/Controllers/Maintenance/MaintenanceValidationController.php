<?php

declare(strict_types=1);

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;

use App\Enums\MaintenanceValidationStatus;
use App\Http\Requests\Maintenance\StoreMaintenanceValidationRequest;
use App\Models\Maintenance\MaintenanceReceipt;
use App\Models\Maintenance\MaintenanceValidation;
use App\Services\Maintenance\MaintenanceValidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MaintenanceValidationController extends Controller
{
    /**
     * Exibe o formulário de validação do equipamento.
     */
    public function create(
        MaintenanceReceipt $maintenanceReceipt
    ): View {
        $this->authorize(
            'create',
            MaintenanceValidation::class
        );

        $maintenanceReceipt->load([
            'maintenanceShipment.maintenanceOrder.equipment.branch',
            'maintenanceShipment.maintenanceOrder.equipment.equipmentModel.category',
            'maintenanceShipment.maintenanceOrder.equipment.equipmentModel.manufacturer',
            'maintenanceShipment.company',
            'maintenanceShipment.originBranch',
            'maintenanceShipment.sender',
            'receiver',
            'validations.validator',
        ]);

        return view(
            'maintenance.validation.validation',
            compact('maintenanceReceipt')
        );
    }

    /**
     * Registra uma nova validação técnica.
     */
    public function store(
        StoreMaintenanceValidationRequest $request,
        MaintenanceReceipt $maintenanceReceipt,
        MaintenanceValidationService $maintenanceValidationService
    ): RedirectResponse {
        $this->authorize(
            'create',
            MaintenanceValidation::class
        );

        $validated = $request->validated();

        /*
         * Registra a validação técnica.
         *
         * O Service permanece responsável pelas regras
         * de negócio e pela alteração do estado da OS.
         */
        $maintenanceValidation =
            $maintenanceValidationService->completeValidation(
                maintenanceReceipt: $maintenanceReceipt,
                validationStatus: MaintenanceValidationStatus::from(
                    $validated['validation_status']
                ),
                testsPerformed: $validated['tests_performed'],
                validationObservation:
                    $validated['validation_observation'] ?? null,
                closeWithoutResend:
                    $validated['close_without_resend'] ?? false,
                userId: $request->user()->id,
            );

        /*
         * Quando o reparo for REJECTED e o técnico não
         * escolher "close_without_resend", o Service coloca
         * a OS em AWAITING_RESEND.
         *
         * A decisão de reenviar imediatamente será apresentada
         * na própria tela de validação através de um modal.
         */
        if (
            $maintenanceValidation->validation_status
                === MaintenanceValidationStatus::REJECTED
            && ! $maintenanceValidation->close_without_resend
        ) {
            return redirect()
                ->route(
                    'reparos_externos.validar.form',
                    $maintenanceReceipt
                )
                ->with(
                    'ask_resend',
                    true
                )
                ->with(
                    'success',
                    'Validação do reparo registrada com sucesso.'
                );
        }

        /*
         * Para APPROVED, NO_REPAIR ou REJECTED com
         * close_without_resend, o fluxo segue normalmente
         * para a visualização da OS.
         */
        return redirect()
            ->route(
                'reparos_externos.show',
                $maintenanceReceipt
                    ->maintenanceShipment
                    ->maintenanceOrder
            )
            ->with(
                'success',
                'Validação do reparo registrada com sucesso.'
            );
    }
}
