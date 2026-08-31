<?php

namespace App\Http\Controllers;

use App\Enums\MaintenanceOrderStatus;
use App\Enums\MaintenanceShipmentStatus;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Maintenance\MaintenanceOrder;
use App\Models\Maintenance\MaintenanceShipment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceOrderController extends Controller
{
    /**
     * Lista as ordens de serviço.
     */
    public function index(
        Request $request
    ): View {
        $this->authorize(
            'viewAny',
            MaintenanceOrder::class
        );

        /*
         * Filtros recebidos pela URL.
         */
        $search = trim(
            (string) $request->input('search')
        );

        $status = $request->input('status');

        $companyId = $request->input('company_id');

        $branchId = $request->input('branch_id');

        $dateFrom = $request->input('date_from');

        $dateTo = $request->input('date_to');


        /*
         * Resumo geral das ordens.
         *
         * Os cards não são afetados pelos filtros da tabela.
         */
        $inRepairCount = MaintenanceOrder::query()
            ->where(
                'status',
                MaintenanceOrderStatus::IN_REPAIR
            )
            ->count();

        $inValidationCount = MaintenanceOrder::query()
            ->where(
                'status',
                MaintenanceOrderStatus::IN_VALIDATION
            )
            ->count();

        $awaitingResendCount = MaintenanceOrder::query()
            ->where(
                'status',
                MaintenanceOrderStatus::AWAITING_RESEND
            )
            ->count();

        $completedCount = MaintenanceOrder::query()
            ->where(
                'status',
                MaintenanceOrderStatus::COMPLETED
            )
            ->count();


        /*
         * Consulta principal das ordens.
         */
        $maintenanceOrders = MaintenanceOrder::query()
            ->with([
                'equipment',

                /*
                 * O shipment de maior sequence representa
                 * o ciclo mais recente da OS.
                 */
                'shipments' => function ($query) {
                    $query
                        ->with([
                            'company',
                            'originBranch',
                            'sender',
                            'receipt.receiver',
                        ])
                        ->orderByDesc('sequence');
                },
            ]);


        /*
         * Busca geral.
         *
         * Permite pesquisar por:
         *
         * - número da OS
         * - nome do equipamento
         * - patrimônio
         * - número de série
         */
        if ($search !== '') {

            $maintenanceOrders->where(function ($query) use ($search) {

                /*
                 * Número da OS.
                 */
                if (is_numeric($search)) {

                    $query->where(
                        'id',
                        (int) $search
                    );
                }

                /*
                 * Dados do equipamento.
                 */
                $query->orWhereHas(
                    'equipment',
                    function ($equipmentQuery) use ($search) {

                        $equipmentQuery
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'asset_number',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'serial_number',
                                'like',
                                "%{$search}%"
                            );
                    }
                );
            });
        }


        /*
         * Filtro por status da OS.
         */
        if ($status !== null && $status !== '') {

            /*
             * Verifica se o valor recebido realmente
             * corresponde a um status válido.
             */
            $validStatus = collect(
                MaintenanceOrderStatus::cases()
            )->firstWhere(
                'value',
                $status
            );

            if ($validStatus) {

                $maintenanceOrders->where(
                    'status',
                    $validStatus
                );
            }
        }


        /*
         * Filtro por empresa terceirizada.
         *
         * Considera somente o último ciclo da OS.
         */
        if ($companyId !== null && $companyId !== '') {

            $maintenanceOrders->whereHas(
                'shipments',
                function ($query) use ($companyId) {

                    $query
                        ->where(
                            'company_id',
                            $companyId
                        )
                        ->whereRaw(
                            'sequence = (
                                SELECT MAX(ms2.sequence)
                                FROM maintenance_shipments ms2
                                WHERE ms2.maintenance_order_id = maintenance_shipments.maintenance_order_id
                            )'
                        );
                }
            );
        }


        /*
         * Filtro por filial de envio.
         *
         * Considera somente o último ciclo da OS.
         */
        if ($branchId !== null && $branchId !== '') {

            $maintenanceOrders->whereHas(
                'shipments',
                function ($query) use ($branchId) {

                    $query
                        ->where(
                            'origin_branch_id',
                            $branchId
                        )
                        ->whereRaw(
                            'sequence = (
                                SELECT MAX(ms2.sequence)
                                FROM maintenance_shipments ms2
                                WHERE ms2.maintenance_order_id = maintenance_shipments.maintenance_order_id
                            )'
                        );
                }
            );
        }


        /*
         * Filtro pela data inicial de envio.
         *
         * Considera somente o último ciclo da OS.
         */
        if ($dateFrom !== null && $dateFrom !== '') {

            $maintenanceOrders->whereHas(
                'shipments',
                function ($query) use ($dateFrom) {

                    $query
                        ->whereDate(
                            'sent_at',
                            '>=',
                            $dateFrom
                        )
                        ->whereRaw(
                            'sequence = (
                                SELECT MAX(ms2.sequence)
                                FROM maintenance_shipments ms2
                                WHERE ms2.maintenance_order_id = maintenance_shipments.maintenance_order_id
                            )'
                        );
                }
            );
        }


        /*
         * Filtro pela data final de envio.
         *
         * Considera somente o último ciclo da OS.
         */
        if ($dateTo !== null && $dateTo !== '') {

            $maintenanceOrders->whereHas(
                'shipments',
                function ($query) use ($dateTo) {

                    $query
                        ->whereDate(
                            'sent_at',
                            '<=',
                            $dateTo
                        )
                        ->whereRaw(
                            'sequence = (
                                SELECT MAX(ms2.sequence)
                                FROM maintenance_shipments ms2
                                WHERE ms2.maintenance_order_id = maintenance_shipments.maintenance_order_id
                            )'
                        );
                }
            );
        }


        /*
         * Ordena pelas OS mais recentes.
         */
        $maintenanceOrders
            ->latest('id');


        /*
         * Paginação.
         *
         * withQueryString() mantém os filtros
         * durante a navegação entre páginas.
         */
        $maintenanceOrders =
            $maintenanceOrders
                ->paginate(15)
                ->withQueryString();


        /*
         * Empresas disponíveis para o filtro.
         */
        $companies = Company::query()
            ->active()
            ->outsourced()
            ->orderBy('name')
            ->get();


        /*
         * Filiais disponíveis para o filtro.
         */
        $branches = Branch::query()
            ->active()
            ->orderBy('state')
            ->orderBy('name')
            ->get();


        return view(
            'maintenance.index',
            compact(
                'inRepairCount',
                'inValidationCount',
                'awaitingResendCount',
                'completedCount',
                'maintenanceOrders',
                'companies',
                'branches',
                'search',
                'status',
                'companyId',
                'branchId',
                'dateFrom',
                'dateTo'
            )
        );
    }


    /**
     * Visualiza uma ordem de serviço.
     */
    public function show(
        MaintenanceOrder $maintenanceOrder
    ): View {
        $this->authorize(
            'view',
            $maintenanceOrder
        );

        $maintenanceOrder->load([
            'equipment.branch',

            'shipments.company',
            'shipments.originBranch',
            'shipments.sender',

            'shipments.receipt',
            'shipments.receipt.receiver',
            'shipments.receipt.receivingBranch',

            'shipments.receipt.validations.validator',
        ]);

        /*
         * O envio de maior sequence representa
         * o ciclo mais recente da OS.
         */
        $latestShipment = $maintenanceOrder->shipments
            ->sortByDesc('sequence')
            ->first();

        /*
         * Recebimento pertencente ao ciclo atual.
         */
        $maintenanceReceipt = $latestShipment?->receipt;

        /*
         * Histórico das validações do ciclo atual.
         */
        $validations = $maintenanceReceipt?->validations
            ->sortBy('validated_at')
            ->values()
            ?? collect();

        return view(
            'maintenance.show',
            compact(
                'maintenanceOrder',
                'latestShipment',
                'maintenanceReceipt',
                'validations'
            )
        );
    }


    /**
     * Gera o PDF da ordem de serviço referente
     * ao ciclo atual de envio.
     */
    public function pdf(
        MaintenanceOrder $maintenanceOrder
    ) {
        $this->authorize(
            'view',
            $maintenanceOrder
        );

        $maintenanceOrder->load([
            'equipment.branch',
            'equipment.equipmentModel.category',
            'equipment.equipmentModel.manufacturer',

            'shipments.company',
            'shipments.originBranch',
            'shipments.sender',

            'shipments.receipt',
        ]);

        /*
         * O envio de maior sequence representa
         * o ciclo atual da OS.
         */
        $latestShipment = $maintenanceOrder->shipments
            ->sortByDesc('sequence')
            ->first();

        /*
         * Neste momento, o PDF somente pode ser gerado
         * enquanto o equipamento estiver enviado para
         * a terceirizada.
         */
        if (
            ! $latestShipment ||
            $latestShipment->status !==
                MaintenanceShipmentStatus::SENT
        ) {
            abort(
                404,
                'A ordem de serviço não possui um envio ativo para impressão.'
            );
        }

        return Pdf::loadView(
            'maintenance.pdf',
            compact(
                'maintenanceOrder',
                'latestShipment'
            )
        )
            ->setPaper(
                'a4',
                'portrait'
            )
            ->stream(
                "OS-{$maintenanceOrder->id}-ciclo-{$latestShipment->sequence}.pdf"
            );
    }
}
