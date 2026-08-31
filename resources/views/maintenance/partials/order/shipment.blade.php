<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    {{-- Header --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

        <div class="flex flex-row items-center justify-between gap-3">

            <div>
                <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                    Envio para Reparo
                </h2>

                <p class="mt-0.5 text-xs text-slate-500 sm:mt-1 sm:text-sm">
                    Informações do envio do equipamento para a empresa terceirizada.
                </p>
            </div>

            @if ($maintenanceOrder->shipments->isNotEmpty())

                @php
                    $latestShipment = $maintenanceOrder->shipments->sortByDesc('sequence')->first();

                    $shipmentStatusClasses = match ($latestShipment->status) {
                        \App\Enums\MaintenanceShipmentStatus::SENT => 'bg-amber-100 text-amber-700',

                        \App\Enums\MaintenanceShipmentStatus::RETURNED => 'bg-emerald-100 text-emerald-700',
                    };
                @endphp

                <div class="flex shrink-0 items-center gap-2">

                    {{-- Editar envio --}}
                    @if ($latestShipment->status === \App\Enums\MaintenanceShipmentStatus::SENT)
                        <a href="{{ route('reparos_externos.envios.editar.form', $latestShipment) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 sm:text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>

                            Editar
                        </a>
                    @endif

                    {{-- Status --}}
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $shipmentStatusClasses }}">
                        {{ $latestShipment->status->label() }}
                    </span>

                </div>

            @endif

        </div>

    </div>


    @if ($maintenanceOrder->shipments->isNotEmpty())

        @php
            $latestShipment = $maintenanceOrder->shipments->sortByDesc('sequence')->first();
        @endphp

        <div class="p-4 sm:p-6">

            <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2 sm:gap-5 lg:grid-cols-4">


                {{-- Ciclo --}}
                <div>
                    <span class="text-xs font-medium text-slate-500">
                        Ciclo de Envio
                    </span>

                    <p class="mt-0.5 text-sm font-semibold text-slate-800">
                        #{{ $latestShipment->sequence }}
                    </p>
                </div>


                {{-- Empresa --}}
                <div>
                    <span class="text-xs font-medium text-slate-500">
                        Empresa Terceirizada
                    </span>

                    <p class="mt-0.5 text-sm font-semibold text-slate-800">
                        {{ $latestShipment->company?->name ?? 'Não informado' }}
                    </p>
                </div>


                {{-- Nota fiscal --}}
                <div>
                    <span class="text-xs font-medium text-slate-500">
                        Nota Fiscal de Envio
                    </span>

                    <p class="mt-0.5 text-sm text-slate-700">
                        {{ $latestShipment->invoice_number ?: 'Não informada' }}
                    </p>
                </div>


                {{-- Data do envio --}}
                <div>
                    <span class="text-xs font-medium text-slate-500">
                        Enviado em
                    </span>

                    <p class="mt-0.5 text-sm text-slate-700">
                        {{ $latestShipment->sent_at?->format('d/m/Y H:i') ?? 'Não informado' }}
                    </p>
                </div>


                {{-- Origem --}}
                <div>
                    <span class="text-xs font-medium text-slate-500">
                        Filial de Envio
                    </span>

                    <p class="mt-0.5 text-sm text-slate-700">
                        {{ $latestShipment->originBranch?->name ?? 'Não informado' }}
                    </p>
                </div>


                {{-- Enviado por --}}
                <div>
                    <span class="text-xs font-medium text-slate-500">
                        Enviado por
                    </span>

                    <p class="mt-0.5 text-sm font-medium text-slate-800">
                        {{ $latestShipment->sender?->name ?? 'Não informado' }}
                    </p>
                </div>


            </div>


            {{-- Defeito --}}
            <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-3.5 sm:mt-5 sm:p-4">

                <p class="text-xs font-semibold text-slate-600">
                    Defeito informado
                </p>

                <p class="mt-1 text-xs leading-relaxed text-slate-700 sm:text-sm">
                    {{ $latestShipment->defect_description ?: 'Não informado' }}
                </p>

            </div>


            {{-- Observação --}}
            @if ($latestShipment->observation)
                <div class="mt-2.5 rounded-lg border border-slate-200 bg-slate-50 p-3.5 sm:mt-3 sm:p-4">

                    <p class="text-xs font-semibold text-slate-600">
                        Observação do envio
                    </p>

                    <p class="mt-1 text-xs leading-relaxed text-slate-700 sm:text-sm">
                        {{ $latestShipment->observation }}
                    </p>

                </div>
            @endif

        </div>
    @else
        <div class="p-4 text-xs text-slate-500 sm:p-6 sm:text-sm">
            Nenhum envio registrado para esta ordem de serviço.
        </div>

    @endif

</x-cards.card>
