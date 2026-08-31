@php

    use App\Enums\MaintenanceOrderStatus;

    /*
     * Status atualmente selecionado.
     */
    $currentStatus = request('status');


    /*
     * Gera a URL do card de status.
     *
     * Mantém os demais filtros ativos:
     *
     * - busca
     * - empresa
     * - filial
     * - data inicial
     * - data final
     *
     * Se o status clicado já estiver selecionado,
     * o filtro de status é removido.
     */
    $buildStatusUrl = function (string $status) use ($currentStatus) {

        $query = request()->query();

        if ($currentStatus === $status) {

            unset($query['status']);

        } else {

            $query['status'] = $status;

        }

        return route(
            'reparos_externos.index',
            $query
        );
    };


    /*
     * Status.
     */
    $inRepairStatus =
        MaintenanceOrderStatus::IN_REPAIR->value;

    $inValidationStatus =
        MaintenanceOrderStatus::IN_VALIDATION->value;

    $awaitingResendStatus =
        MaintenanceOrderStatus::AWAITING_RESEND->value;

    $completedStatus =
        MaintenanceOrderStatus::COMPLETED->value;

@endphp


<div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4">


    {{-- ========================================================= --}}
    {{-- OS EM REPARO --}}
    {{-- ========================================================= --}}

    <a
        href="{{ $buildStatusUrl($inRepairStatus) }}"
        @class([
            'block rounded-xl transition',
            'ring-2 ring-amber-400 ring-offset-2' =>
                $currentStatus === $inRepairStatus,
        ])
    >

        <x-cards.card
            class="h-full p-3.5 transition hover:-translate-y-0.5 hover:shadow-md sm:p-6"
        >

            <div class="flex items-center justify-between gap-3">

                <div>

                    <p class="text-xs font-medium text-slate-500 sm:text-sm">
                        Em Reparo
                    </p>

                    <p class="mt-1 text-2xl font-bold text-amber-600 sm:mt-2 sm:text-3xl">
                        {{ $inRepairCount }}
                    </p>

                </div>


                <div class="shrink-0 rounded-full bg-amber-100 p-2.5 sm:p-4">

                    <svg
                        class="h-5 w-5 text-amber-600 sm:h-8 sm:w-8"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4l3 3M12 3a9 9 0 100 18 9 9 0 000-18z"
                        />

                    </svg>

                </div>

            </div>

        </x-cards.card>

    </a>



    {{-- ========================================================= --}}
    {{-- OS AGUARDANDO VALIDAÇÃO --}}
    {{-- ========================================================= --}}

    <a
        href="{{ $buildStatusUrl($inValidationStatus) }}"
        @class([
            'block rounded-xl transition',
            'ring-2 ring-blue-400 ring-offset-2' =>
                $currentStatus === $inValidationStatus,
        ])
    >

        <x-cards.card
            class="h-full p-3.5 transition hover:-translate-y-0.5 hover:shadow-md sm:p-6"
        >

            <div class="flex items-center justify-between gap-3">

                <div>

                    <p class="text-xs font-medium text-slate-500 sm:text-sm">
                        Aguardando Validação
                    </p>

                    <p class="mt-1 text-2xl font-bold text-blue-600 sm:mt-2 sm:text-3xl">
                        {{ $inValidationCount }}
                    </p>

                </div>


                <div class="shrink-0 rounded-full bg-blue-100 p-2.5 sm:p-4">

                    <svg
                        class="h-5 w-5 text-blue-600 sm:h-8 sm:w-8"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"
                        />

                    </svg>

                </div>

            </div>

        </x-cards.card>

    </a>



    {{-- ========================================================= --}}
    {{-- OS AGUARDANDO REENVIO --}}
    {{-- ========================================================= --}}

    <a
        href="{{ $buildStatusUrl($awaitingResendStatus) }}"
        @class([
            'block rounded-xl transition',
            'ring-2 ring-orange-400 ring-offset-2' =>
                $currentStatus === $awaitingResendStatus,
        ])
    >

        <x-cards.card
            class="h-full p-3.5 transition hover:-translate-y-0.5 hover:shadow-md sm:p-6"
        >

            <div class="flex items-center justify-between gap-3">

                <div>

                    <p class="text-xs font-medium text-slate-500 sm:text-sm">
                        Aguardando Reenvio
                    </p>

                    <p class="mt-1 text-2xl font-bold text-orange-600 sm:mt-2 sm:text-3xl">
                        {{ $awaitingResendCount }}
                    </p>

                </div>


                <div class="shrink-0 rounded-full bg-orange-100 p-2.5 sm:p-4">

                    <svg
                        class="h-5 w-5 text-orange-600 sm:h-8 sm:w-8"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 12a8 8 0 0113.657-5.657L20 8M20 4v4h-4M20 12a8 8 0 01-13.657 5.657L4 16M4 20v-4h4"
                        />

                    </svg>

                </div>

            </div>

        </x-cards.card>

    </a>



    {{-- ========================================================= --}}
    {{-- OS FINALIZADAS --}}
    {{-- ========================================================= --}}

    <a
        href="{{ $buildStatusUrl($completedStatus) }}"
        @class([
            'block rounded-xl transition',
            'ring-2 ring-emerald-400 ring-offset-2' =>
                $currentStatus === $completedStatus,
        ])
    >

        <x-cards.card
            class="h-full p-3.5 transition hover:-translate-y-0.5 hover:shadow-md sm:p-6"
        >

            <div class="flex items-center justify-between gap-3">

                <div>

                    <p class="text-xs font-medium text-slate-500 sm:text-sm">
                        Finalizadas
                    </p>

                    <p class="mt-1 text-2xl font-bold text-emerald-600 sm:mt-2 sm:text-3xl">
                        {{ $completedCount }}
                    </p>

                </div>


                <div class="shrink-0 rounded-full bg-emerald-100 p-2.5 sm:p-4">

                    <svg
                        class="h-5 w-5 text-emerald-600 sm:h-8 sm:w-8"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />

                    </svg>

                </div>

            </div>

        </x-cards.card>

    </a>

</div>
