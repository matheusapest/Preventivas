<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaintenanceResendRequest;
use App\Http\Requests\StoreMaintenanceShipmentRequest;
use App\Http\Requests\UpdateMaintenanceShipmentRequest;
use App\Models\Organization\Branch;
use App\Models\Organization\Company;
use App\Models\Maintenance\MaintenanceOrder;
use App\Models\Maintenance\MaintenanceShipment;
use App\Services\MaintenanceShipmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use RuntimeException;

class MaintenanceShipmentController extends Controller
{
    /**
     * Lista os envios de equipamentos.
     */
    public function index(): View
    {
        $this->authorize(
            'viewAny',
            MaintenanceShipment::class
        );

        $maintenanceShipments = MaintenanceShipment::query()
            ->with([
                'maintenanceOrder',
                'company',
                'originBranch',
                'sender',
            ])
            ->latest('sent_at')
            ->paginate(15);

        return view(
            'maintenance.shipments.index',
            compact('maintenanceShipments')
        );
    }

    /**
     * Exibe o formulário de envio de equipamento.
     */
    public function create(): View
    {
        $this->authorize(
            'create',
            MaintenanceShipment::class
        );

        $companies = Company::query()
            ->active()
            ->outsourced()
            ->orderBy('name')
            ->get();

        $branches = Branch::query()
            ->active()
            ->orderBy('state')
            ->orderBy('name')
            ->get()
            ->groupBy(
                fn (Branch $branch) => $branch->state->label()
            );

        return view(
            'maintenance.shipments.create',
            compact(
                'companies',
                'branches'
            )
        );
    }

    /**
     * Visualiza um envio de equipamento.
     */
    public function show(
        MaintenanceShipment $maintenanceShipment
    ): View {
        $this->authorize(
            'view',
            $maintenanceShipment
        );

        $maintenanceShipment->load([
            'maintenanceOrder',
            'company',
            'originBranch',
            'sender',
        ]);

        return view(
            'maintenance.shipments.show',
            compact('maintenanceShipment')
        );
    }

    /**
     * Exibe o formulário de edição dos dados logísticos do envio.
     */
    public function edit(
        MaintenanceShipment $maintenanceShipment
    ): View {
        $this->authorize(
            'updateLogistics',
            $maintenanceShipment
        );

        $maintenanceShipment->load([
            'maintenanceOrder',
            'company',
            'originBranch',
            'sender',
        ]);

        $branches = Branch::query()
            ->active()
            ->orderBy('state')
            ->orderBy('name')
            ->get()
            ->groupBy(
                fn (Branch $branch) => $branch->state->label()
            );

        return view(
            'maintenance.shipments.edit',
            compact(
                'maintenanceShipment',
                'branches'
            )
        );
    }

    /**
     * Envia um equipamento para reparo externo.
     */
    public function store(
        StoreMaintenanceShipmentRequest $request,
        MaintenanceShipmentService $maintenanceShipmentService
    ): RedirectResponse {
        $this->authorize(
            'create',
            MaintenanceShipment::class
        );

        $validated = $request->validated();

        try {

            $maintenanceShipment =
                $maintenanceShipmentService->create(
                    equipmentId:
                        (int) $validated['equipment_id'],

                    companyId:
                        (int) $validated['company_id'],

                    originBranchId:
                        (int) $validated['origin_branch_id'],

                    sentAt:
                        $validated['sent_at'],

                    invoiceNumber:
                        $validated['invoice_number'] ?? null,

                    defectDescription:
                        $validated['defect_description'],

                    observation:
                        $validated['observation'] ?? null,

                    userId:
                        $request->user()->id,
                );

        } catch (RuntimeException $exception) {

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'shipment' =>
                        $exception->getMessage(),
                ]);
        }

        return redirect()
            ->route(
                'reparos_externos.show',
                $maintenanceShipment->maintenanceOrder
            )
            ->with(
                'success',
                'Equipamento enviado para reparo externo com sucesso.'
            );
    }

    /**
     * Atualiza os dados logísticos do envio.
     */
    public function update(
        UpdateMaintenanceShipmentRequest $request,
        MaintenanceShipment $maintenanceShipment,
        MaintenanceShipmentService $maintenanceShipmentService
    ): RedirectResponse {
        $this->authorize(
            'updateLogistics',
            $maintenanceShipment
        );

        $validated = $request->validated();

        try {

            $maintenanceShipmentService->updateLogistics(
                maintenanceShipment:
                    $maintenanceShipment,

                originBranchId:
                    (int) $validated['origin_branch_id'],

                invoiceNumber:
                    $validated['invoice_number'] ?? null,
            );

        } catch (RuntimeException $exception) {

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'shipment' =>
                        $exception->getMessage(),
                ]);
        }

        return redirect()
            ->route(
                'reparos_externos.show',
                $maintenanceShipment->maintenanceOrder
            )
            ->with(
                'success',
                'Dados logísticos do envio atualizados com sucesso.'
            );
    }

    /**
     * Exibe o formulário de reenvio de um equipamento.
     */
    public function resend(
        MaintenanceOrder $maintenanceOrder
    ): View {
        /*
         * Carrega os envios da OS em ordem decrescente.
         */
        $maintenanceOrder->load([
            'equipment',
            'shipments' => function ($query) {
                $query
                    ->orderByDesc('sequence');
            },
        ]);

        /*
         * O reenvio sempre utiliza o último ciclo.
         */
        $latestShipment = $maintenanceOrder->shipments
            ->first();

        /*
         * Não existe ciclo anterior.
         */
        if (! $latestShipment) {
            abort(
                404,
                'A ordem de serviço não possui um envio anterior.'
            );
        }

        /*
         * A autorização é feita sobre o último shipment.
         */
        $this->authorize(
            'resend',
            $latestShipment
        );

        $companies = Company::query()
            ->active()
            ->outsourced()
            ->orderBy('name')
            ->get();

        $branches = Branch::query()
            ->active()
            ->orderBy('state')
            ->orderBy('name')
            ->get()
            ->groupBy(
                fn (Branch $branch) => $branch->state->label()
            );

        return view(
            'maintenance.shipments.resend',
            compact(
                'maintenanceOrder',
                'latestShipment',
                'companies',
                'branches'
            )
        );
    }

    /**
     * Registra um novo ciclo de envio dentro da mesma OS.
     */
    public function storeResend(
        StoreMaintenanceResendRequest $request,
        MaintenanceOrder $maintenanceOrder,
        MaintenanceShipmentService $maintenanceShipmentService
    ): RedirectResponse {
        /*
         * Carrega o último ciclo da OS.
         */
        $maintenanceOrder->load([
            'shipments' => function ($query) {
                $query
                    ->orderByDesc('sequence');
            },
        ]);

        $latestShipment = $maintenanceOrder->shipments
            ->first();

        /*
         * A OS precisa possuir um ciclo anterior.
         */
        if (! $latestShipment) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'shipment' =>
                        'A ordem de serviço não possui um envio anterior para realizar o reenvio.',
                ]);
        }

        /*
         * Autoriza o reenvio do último ciclo.
         */
        $this->authorize(
            'resend',
            $latestShipment
        );

        $validated = $request->validated();

        try {

            $maintenanceShipment =
                $maintenanceShipmentService->resend(
                    maintenanceOrder:
                        $maintenanceOrder,

                    companyId:
                        (int) $validated['company_id'],

                    originBranchId:
                        (int) $validated['origin_branch_id'],

                    sentAt:
                        $validated['sent_at'],

                    invoiceNumber:
                        $validated['invoice_number'] ?? null,

                    defectDescription:
                        $validated['defect_description'],

                    observation:
                        $validated['observation'] ?? null,

                    userId:
                        $request->user()->id,
                );

        } catch (RuntimeException $exception) {

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'shipment' =>
                        $exception->getMessage(),
                ]);
        }

        return redirect()
            ->route(
                'reparos_externos.show',
                $maintenanceOrder
            )
            ->with(
                'success',
                'Equipamento reenviado para reparo externo com sucesso.'
            );
    }
}
