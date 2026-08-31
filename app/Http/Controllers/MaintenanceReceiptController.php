<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\MaintenanceOrderStatus;
use App\Enums\MaintenanceShipmentStatus;
use App\Models\Equipment;
use App\Models\MaintenanceOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMaintenanceReceiptRequest;
use App\Http\Requests\StoreMultipleMaintenanceReceiptRequest;
use App\Http\Requests\UpdateMaintenanceReceiptRequest;
use App\Models\Branch;
use App\Models\MaintenanceReceipt;
use App\Models\MaintenanceShipment;
use App\Services\MaintenanceReceiptService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MaintenanceReceiptController extends Controller
{
    /**
     * Exibe o formulário de recebimento individual
     * de um equipamento.
     */
    public function receive(
        MaintenanceShipment $maintenanceShipment
    ): View {
        $this->authorize(
            'receive',
            $maintenanceShipment
        );

        $maintenanceShipment->load([
            'maintenanceOrder.equipment.branch',
            'maintenanceOrder.equipment.equipmentModel.category',
            'maintenanceOrder.equipment.equipmentModel.manufacturer',
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
                fn(Branch $branch) => $branch->state->label()
            );

        return view(
            'maintenance.receipts.receive',
            compact(
                'maintenanceShipment',
                'branches'
            )
        );
    }

    /**
     * Registra o recebimento individual de um equipamento.
     */
    public function store(
        StoreMaintenanceReceiptRequest $request,
        MaintenanceShipment $maintenanceShipment,
        MaintenanceReceiptService $maintenanceReceiptService
    ): RedirectResponse {

        $this->authorize(
            'receive',
            $maintenanceShipment
        );

        $validated = $request->validated();

        $maintenanceReceiptService->create(
            maintenanceShipment: $maintenanceShipment,

            invoiceNumber: $validated['invoice_number'] ?? null,

            receivingObservation: $validated['receiving_observation'] ?? null,

            receivingBranchId: (int) $validated['receiving_branch_id'],

            userId: $request->user()->id,
        );

        return redirect()
            ->route(
                'reparos_externos.show',
                $maintenanceShipment->maintenanceOrder
            )
            ->with(
                'success',
                'Equipamento recebido com sucesso.'
            );
    }

    /**
     * Exibe os equipamentos que possuem recebimento pendente
     */

    public function pending(): View
    {
        $maintenanceShipments = MaintenanceShipment::query()
            ->where(
                'status',
                MaintenanceShipmentStatus::SENT
            )
            ->with([
                'maintenanceOrder.equipment',
                'maintenanceOrder.equipment.equipmentModel',
                'originBranch',
                'company',
                'sender',
            ])
            ->latest('sent_at')
            ->paginate(15);

        return view(
            'maintenance.receipts.index',
            compact('maintenanceShipments')
        );
    }
    /**
     * Exibe o formulário de edição dos dados logísticos
     * do recebimento.
     */
    public function edit(
        MaintenanceReceipt $maintenanceReceipt
    ): View {
        $this->authorize(
            'updateLogistics',
            $maintenanceReceipt
        );

        $maintenanceReceipt->load([
            'maintenanceShipment.maintenanceOrder',
            'maintenanceShipment.company',
            'maintenanceShipment.originBranch',
            'maintenanceShipment.sender',
            'receiver',
            'receivingBranch',
        ]);

        $branches = Branch::query()
            ->active()
            ->orderBy('state')
            ->orderBy('name')
            ->get()
            ->groupBy(
                fn(Branch $branch) => $branch->state->label()
            );

        return view(
            'maintenance.receipts.edit',
            compact(
                'maintenanceReceipt',
                'branches'
            )
        );
    }

    /**
     * Atualiza os dados logísticos do recebimento.
     */
    public function update(
        UpdateMaintenanceReceiptRequest $request,
        MaintenanceReceipt $maintenanceReceipt,
        MaintenanceReceiptService $maintenanceReceiptService
    ): RedirectResponse {
        $this->authorize(
            'updateLogistics',
            $maintenanceReceipt
        );

        $validated = $request->validated();

        $maintenanceReceiptService->updateLogistics(
            maintenanceReceipt: $maintenanceReceipt,

            receivingBranchId: (int) $validated['receiving_branch_id'],

            invoiceNumber: $validated['invoice_number'] ?? null,
        );

        return redirect()
            ->route(
                'reparos_externos.show',
                $maintenanceReceipt
                    ->maintenanceShipment
                    ->maintenanceOrder
            )
            ->with(
                'success',
                'Dados logísticos do recebimento atualizados com sucesso.'
            );
    }


    /**
     * Exibe o formulário de recebimento múltiplo.
     */
    public function multiple(): View
    {
        $branches = Branch::query()
            ->active()
            ->orderBy('state')
            ->orderBy('name')
            ->get()
            ->groupBy(
                fn(Branch $branch) => $branch->state->label()
            );

        return view(
            'maintenance.receipts.multiple',
            compact('branches')
        );
    }

    /**
     * Registra o recebimento múltiplo de equipamentos.
     */
    public function storeMultiple(
        StoreMultipleMaintenanceReceiptRequest $request,
        MaintenanceReceiptService $maintenanceReceiptService
    ): RedirectResponse {

        $this->authorize(
            'receiveMultiple',
            MaintenanceShipment::class
        );

        $validated = $request->validated();

        $result = $maintenanceReceiptService->createMultiple(
            shipmentIds: $validated['shipment_ids'],

            invoiceNumber: $validated['invoice_number'] ?? null,

            receivingObservation: $validated['receiving_observation'] ?? null,

            receivingBranchId: (int) $validated['receiving_branch_id'],

            userId: $request->user()->id,
        );

        /*
 * Nenhum equipamento foi recebido.
 *
 * Mantém o usuário na tela de recebimento
 * para que ele possa corrigir os itens.
 */
        if (empty($result['success'])) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'receipt_result',
                    $result
                )
                ->with(
                    'error',
                    'Nenhum equipamento foi recebido.'
                );
        }

        /*
        * Pelo menos um equipamento foi recebido.
        */
        $successCount = count($result['success']);

        $message = $successCount === 1
            ? '1 equipamento recebido com sucesso.'
            : "{$successCount} equipamentos recebidos com sucesso.";

        return redirect()
            ->route(
                'reparos_externos.recebimentos.index'
            )
            ->with(
                'success',
                $message
            );
    }
    /**
     * Busca um equipamento para recebimento.
     *
     * A busca verifica se o equipamento possui um envio
     * disponível para recebimento.
     */
    public function search(
        Request $request
    ): JsonResponse {

        $this->authorize(
            'viewAny',
            Equipment::class
        );

        $request->validate([
            'identifier' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        $identifier = trim(
            $request->input('identifier')
        );

        /*
     * Busca o equipamento por patrimônio
     * ou número de série.
     */
        $equipment = Equipment::query()
            ->with([
                'equipmentModel',
                'equipmentModel.manufacturer',
                'equipmentModel.category',
            ])
            ->where(function ($query) use ($identifier) {

                $query
                    ->where(
                        'asset_number',
                        $identifier
                    )
                    ->orWhere(
                        'serial_number',
                        $identifier
                    );
            })
            ->first();

        /*
     * Equipamento não encontrado.
     */
        if (! $equipment) {

            return response()->json([
                'success' => false,

                'message' =>
                'Equipamento não encontrado.',
            ], 404);
        }

        /*
     * Dados básicos apresentados no frontend.
     *
     * A filial do equipamento não é retornada
     * porque ela não representa a filial de origem
     * do ciclo de reparo.
     */
        $equipmentData = [
            'id' =>
            $equipment->id,

            'name' =>
            $equipment->name,

            'asset_number' =>
            $equipment->asset_number,

            'serial_number' =>
            $equipment->serial_number,

            'model' =>
            $equipment->equipmentModel?->name,

            'manufacturer' =>
            $equipment
                ->equipmentModel
                ?->manufacturer
                ?->name,

            'category' =>
            $equipment
                ->equipmentModel
                ?->category
                ?->name,

            'active' =>
            $equipment->active,

            'operational_status' =>
            $equipment->operational_status?->label(),
        ];

        /*
     * Busca a OS atualmente aberta do equipamento.
     */
        $maintenanceOrder = MaintenanceOrder::query()
            ->where(
                'equipment_id',
                $equipment->id
            )
            ->whereIn('status', [
                MaintenanceOrderStatus::IN_REPAIR,
                MaintenanceOrderStatus::IN_VALIDATION,
            ])
            ->latest('id')
            ->first();

        /*
     * O equipamento não possui OS aberta.
     */
        if (! $maintenanceOrder) {

            return response()->json([
                'success' => true,

                'equipment' =>
                $equipmentData,

                'receiving' => [
                    'can_receive' => false,

                    'shipment_id' => null,

                    'message' =>
                    'O equipamento não possui uma ordem de serviço disponível para recebimento.',
                ],
            ]);
        }

        /*
     * Busca o último ciclo da OS.
     *
     * originBranch representa a filial de onde
     * este ciclo foi efetivamente enviado.
     */
        $shipment = $maintenanceOrder
            ->shipments()
            ->with([
                'receipt',
                'originBranch',
            ])
            ->latest('sequence')
            ->first();

        /*
     * A OS não possui nenhum envio.
     */
        if (! $shipment) {

            return response()->json([
                'success' => true,

                'equipment' =>
                $equipmentData,

                'receiving' => [
                    'can_receive' => false,

                    'shipment_id' => null,

                    'message' =>
                    'A ordem de serviço não possui um envio registrado.',
                ],
            ]);
        }

        /*
     * O último envio já possui recebimento.
     */
        if ($shipment->receipt) {

            return response()->json([
                'success' => true,

                'equipment' =>
                $equipmentData,

                'receiving' => [
                    'can_receive' => false,

                    'shipment_id' =>
                    $shipment->id,

                    'message' =>
                    'Este equipamento já possui um recebimento registrado.',
                ],
            ]);
        }

        /*
     * O envio precisa estar como SENT.
     */
        if (
            $shipment->status
            !== MaintenanceShipmentStatus::SENT
        ) {

            return response()->json([
                'success' => true,

                'equipment' =>
                $equipmentData,

                'receiving' => [
                    'can_receive' => false,

                    'shipment_id' =>
                    $shipment->id,

                    'message' =>
                    'Este equipamento não está aguardando recebimento.',
                ],
            ]);
        }

        /*
     * Equipamento apto para ser adicionado ao lote.
     */
        return response()->json([
            'success' => true,

            'equipment' =>
            $equipmentData,

            'receiving' => [
                'can_receive' => true,

                'shipment_id' =>
                $shipment->id,

                /*
             * Dados logísticos do ciclo.
             */
                'shipment' => [
                    'sequence' =>
                    $shipment->sequence,

                    'origin_branch_id' =>
                    $shipment->origin_branch_id,

                    'origin_branch' =>
                    $shipment->originBranch?->name,
                ],

                'message' => null,
            ],
        ]);
    }
}
