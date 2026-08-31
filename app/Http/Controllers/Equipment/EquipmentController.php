<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\Controller;

use App\Http\Requests\Equipment\StoreEquipmentRequest;
use App\Http\Requests\Equipment\UpdateEquipmentRequest;
use App\Models\Organization\Branch;
use App\Models\Equipment\Equipment;
use App\Models\Equipment\EquipmentModel;
use App\Models\Equipment\Transfer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Enums\OperationalStatus;
use App\Enums\MaintenanceOrderStatus;
use App\Models\Maintenance\MaintenanceOrder;

class EquipmentController extends Controller
{
    /**
     * Lista os equipamentos.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Equipment::class);

        $equipments = Equipment::with([
            'branch',
            'equipmentModel',
            'equipmentModel.manufacturer',
            'equipmentModel.category',
        ])
            ->orderBy('name')
            ->paginate(15);

        return view(
            'equipments.index',
            compact('equipments')
        );
    }

    /**
     * Formulário de cadastro.
     */
    public function create(): View
    {
        $this->authorize('create', Equipment::class);

        $branches = Branch::active()
            ->orderBy('name')
            ->get();

        $equipmentModels = EquipmentModel::with([
            'manufacturer',
            'category',
        ])
            ->active()
            ->orderBy('name')
            ->get();

        return view(
            'equipments.create',
            compact(
                'branches',
                'equipmentModels'
            )
        );
    }

    /**
     * Cadastra um novo equipamento.
     */
    public function store(
        StoreEquipmentRequest $request
    ): RedirectResponse {

        $this->authorize('create', Equipment::class);

        $validated = $request->validated();

        $validated['active'] = $request->boolean('active');

        Equipment::create($validated);

        return redirect()
            ->route('equipamentos.index')
            ->with(
                'success',
                'Equipamento cadastrado com sucesso.'
            );
    }

    /**
     * Formulário de edição.
     */
    public function edit(
        Equipment $equipment
    ): View {

        $this->authorize('update', $equipment);

        $branches = Branch::active()
            ->orderBy('name')
            ->get();

        $equipmentModels = EquipmentModel::with([
            'manufacturer',
            'category',

        ])
            ->active()
            ->orderBy('name')
            ->get();
        $operationalStatuses = collect(OperationalStatus::cases())
            ->map(fn(OperationalStatus $status) => (object) [
                'value' => $status->value,
                'name' => $status->label(),
            ]);

        return view(
            'equipments.edit',
            compact(
                'equipment',
                'branches',
                'equipmentModels',
                'operationalStatuses'
            )
        );
    }

    /**
     * Atualiza um equipamento.
     */
    public function update(
        UpdateEquipmentRequest $request,
        Equipment $equipment
    ): RedirectResponse {

        $this->authorize('update', $equipment);

        $validated = $request->validated();

        $validated['active'] = $request->boolean('active');

        $equipment->update($validated);

        return redirect()
            ->route('equipamentos.index')
            ->with(
                'success',
                'Equipamento atualizado com sucesso.'
            );
    }

    /**
     * Ativa/Inativa um equipamento.
     */
    public function toggleActive(
        Equipment $equipment
    ): RedirectResponse {

        $this->authorize(
            'toggleActive',
            $equipment
        );

        $equipment->update([
            'active' => ! $equipment->active,
        ]);

        return redirect()
            ->route('equipamentos.index')
            ->with(
                'success',
                $equipment->active
                    ? 'Equipamento ativado com sucesso.'
                    : 'Equipamento inativado com sucesso.'
            );
    }

    public function search(
        Request $request
    ): JsonResponse {

        $this->authorize(
            'viewAny',
            Equipment::class
        );

        $request->validate([
            'identifier' => [
                'required_without:id',
                'string',
                'max:100',
            ],

            'id' => [
                'required_without:identifier',
                'integer',
                'exists:equipments,id',
            ],
        ]);

        /*
     * Relações necessárias para montar os dados
     * apresentados no frontend.
     */
        $equipmentQuery = Equipment::with([
            'branch',
            'equipmentModel',
            'equipmentModel.manufacturer',
            'equipmentModel.category',
        ]);

        /*
     * Consulta por ID interno.
     *
     * Utilizado principalmente para reconstruir
     * o equipamento após um erro de validação.
     */
        if ($request->filled('id')) {

            $equipment = $equipmentQuery
                ->find(
                    $request->integer('id')
                );
        } else {

            /*
         * Busca operacional por patrimônio
         * ou número de série.
         */
            $identifier = trim(
                $request->input('identifier')
            );

            $equipment = $equipmentQuery
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
        }

        /*
     * Equipamento não encontrado.
     */
        if (! $equipment) {

            return response()->json([
                'success' => false,
                'message' => 'Equipamento não encontrado.',
            ], 404);
        }

        /*
     * Última transferência do equipamento.
     */
        $lastTransfer = Transfer::with([
            'originBranch',
            'destinationBranch',
        ])
            ->where(
                'equipment_id',
                $equipment->id
            )
            ->latest('sent_at')
            ->first();

        /*
     * Transferência atualmente pendente.
     */
        $pendingTransfer = Transfer::with([
            'originBranch',
            'destinationBranch',
        ])
            ->where(
                'equipment_id',
                $equipment->id
            )
            ->sent()
            ->latest('sent_at')
            ->first();

        /*
     * Ordem de serviço atualmente aberta.
     *
     * Apenas IN_REPAIR e IN_VALIDATION bloqueiam
     * um novo envio inicial.
     *
     * Uma OS COMPLETED não é retornada e,
     * portanto, não bloqueia um novo processo.
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

        return response()->json([

            'success' => true,

            'equipment' => [

                'id' => $equipment->id,

                'name' => $equipment->name,

                'asset_number' =>
                $equipment->asset_number,

                'serial_number' =>
                $equipment->serial_number,

                'branch' =>
                $equipment->branch?->name,

                'branch_id' =>
                $equipment->branch_id,

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

                /*
             * OS aberta, quando existir.
             */
                'maintenance_order' =>
                $maintenanceOrder
                    ? [
                        'id' =>
                        $maintenanceOrder->id,

                        'status' =>
                        $maintenanceOrder
                            ->status
                            ->value,

                        'status_label' =>
                        $maintenanceOrder
                            ->status
                            ->label(),
                    ]
                    : null,

                /*
             * Transferência pendente.
             */
                'pending_transfer' =>
                $pendingTransfer
                    ? [
                        'id' =>
                        $pendingTransfer->id,

                        'origin_branch' =>
                        $pendingTransfer
                            ->originBranch
                            ?->name,

                        'destination_branch' =>
                        $pendingTransfer
                            ->destinationBranch
                            ?->name,

                        'sent_at' =>
                        $pendingTransfer
                            ->sent_at
                            ?->format(
                                'd/m/Y H:i'
                            ),
                    ]
                    : null,

                /*
             * Última transferência.
             */
                'last_transfer' =>
                $lastTransfer
                    ? [
                        'origin_branch' =>
                        $lastTransfer
                            ->originBranch
                            ?->name,

                        'destination_branch' =>
                        $lastTransfer
                            ->destinationBranch
                            ?->name,

                        'sent_at' =>
                        $lastTransfer
                            ->sent_at
                            ?->format(
                                'd/m/Y H:i'
                            ),
                    ]
                    : null,
            ],
        ]);
    }
}
