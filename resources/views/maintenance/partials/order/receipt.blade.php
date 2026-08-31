<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Header --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

        <div class="flex flex-row items-center justify-between gap-3">

            <div>
                <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                    Recebimento
                </h2>

                <p class="mt-0.5 text-xs text-slate-500 sm:mt-1 sm:text-sm">
                    Registro do recebimento físico do equipamento após o reparo.
                </p>
            </div>

            @php
                $latestShipment = $maintenanceOrder->shipments
                    ->sortByDesc('sequence')
                    ->first();

                $receipt = $latestShipment?->receipt;
            @endphp

            @if ($receipt)

                <div class="flex shrink-0 items-center gap-2">

                    {{-- Editar recebimento --}}
                    <a
                        href="{{ route(
                            'reparos_externos.recebimentos.editar.form',
                            $receipt
                        ) }}"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 sm:text-sm"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h5"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                            />
                        </svg>

                        Editar

                    </a>

                    {{-- Status --}}
                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                        Recebido
                    </span>

                </div>

            @endif

        </div>

    </div>


    @if ($receipt)

        <div class="p-4 sm:p-6">

            <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3">


                {{-- Nota fiscal --}}
                <div>
                    <span class="text-xs font-medium text-slate-500">
                        Nota Fiscal de Retorno
                    </span>

                    <p class="mt-0.5 text-sm font-semibold text-slate-800">
                        {{ $receipt->invoice_number ?: 'Não informada' }}
                    </p>
                </div>


                {{-- Recebido por --}}
                <div>
                    <span class="text-xs font-medium text-slate-500">
                        Recebido por
                    </span>

                    <p class="mt-0.5 text-sm font-semibold text-slate-800">
                        {{ $receipt->receiver?->name ?? 'Não informado' }}
                    </p>
                </div>


                {{-- Data --}}
                <div>
                    <span class="text-xs font-medium text-slate-500">
                        Recebido em
                    </span>

                    <p class="mt-0.5 text-sm text-slate-700">
                        {{ $receipt->received_at?->format('d/m/Y H:i') ?? 'Não informado' }}
                    </p>
                </div>


                {{-- Filial de Recebimento --}}
                <div>
                    <span class="text-xs font-medium text-slate-500">
                        Filial de Recebimento
                    </span>

                    <p class="mt-0.5 text-sm text-slate-700">
                        {{ $receipt->receivingBranch?->name ?? 'Não informado' }}
                    </p>
                </div>


            </div>


            {{-- Observação --}}
            @if ($receipt->receiving_observation)

                <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-3.5 sm:mt-5 sm:p-4">

                    <p class="text-xs font-semibold text-slate-600">
                        Observação do recebimento
                    </p>

                    <p class="mt-1 text-xs leading-relaxed text-slate-700 sm:text-sm">
                        {{ $receipt->receiving_observation }}
                    </p>

                </div>

            @endif

        </div>

    @else

        <div class="p-4 sm:p-6">

            <div class="rounded-lg border border-amber-200 bg-amber-50 p-3.5 sm:p-4">

                <p class="text-xs font-semibold text-amber-800 sm:text-sm sm:font-medium">
                    Equipamento ainda não recebido.
                </p>

                <p class="mt-0.5 text-xs text-amber-700">
                    O recebimento físico deste equipamento ainda não foi registrado no sistema.
                </p>

            </div>

        </div>

    @endif

</x-cards.card>
