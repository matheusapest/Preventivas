<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="px-4 py-4 sm:px-6 sm:py-5">

        {{-- Navegação e Ações Desktop --}}
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 text-xs text-slate-500">
                <a
                    href="{{ route('reparos_externos.index') }}"
                    class="transition hover:text-slate-800"
                >
                    Reparo Externo
                </a>
                <span>/</span>
                <span class="font-medium text-slate-800">
                    OS #{{ $maintenanceOrder->id }}
                </span>
            </nav>

            {{-- Ações (Full-width no mobile, inline no desktop) --}}
            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">

                {{-- Imprimir OS --}}
                @if (
                    $latestShipment &&
                    $latestShipment->status === \App\Enums\MaintenanceShipmentStatus::SENT
                )
                    <x-buttons.primary
                        :href="route('reparos_externos.os.pdf', $maintenanceOrder)"
                        target="_blank"
                        class="flex-1 sm:flex-initial justify-center text-center text-xs sm:text-sm"
                    >
                        Imprimir OS
                    </x-buttons.primary>
                @endif

                {{-- Reenviar equipamento --}}
                @if (
                    $maintenanceOrder->status === \App\Enums\MaintenanceOrderStatus::AWAITING_RESEND
                )
                    <x-buttons.primary
                        :href="route('reparos_externos.reenviar.form', $maintenanceOrder)"
                        class="flex-1 sm:flex-initial justify-center text-center text-xs sm:text-sm"
                    >
                        Reenviar equipamento
                    </x-buttons.primary>
                @endif

                {{-- Voltar --}}
                <x-buttons.secondary
                    :href="route('reparos_externos.index')"
                    class="flex-1 sm:flex-initial justify-center text-center text-xs sm:text-sm"
                >
                    Voltar
                </x-buttons.secondary>

            </div>

        </div>

        <hr class="border-slate-100 my-3 sm:hidden" />

        {{-- Identificação da OS e Status --}}
        <div class="flex flex-row items-center justify-between gap-4">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 sm:text-slate-500">
                    Ordem de Serviço
                </p>

                <h1 class="mt-0.5 text-lg font-bold text-slate-900 sm:mt-1 sm:text-2xl">
                    OS #{{ $maintenanceOrder->id }}
                </h1>
            </div>

            {{-- Status --}}
            @php
                $statusClasses = match ($maintenanceOrder->status) {
                    \App\Enums\MaintenanceOrderStatus::IN_REPAIR => 'bg-amber-100 text-amber-700',
                    \App\Enums\MaintenanceOrderStatus::IN_VALIDATION => 'bg-blue-100 text-blue-700',
                    \App\Enums\MaintenanceOrderStatus::AWAITING_RESEND => 'bg-orange-100 text-orange-700',
                    \App\Enums\MaintenanceOrderStatus::COMPLETED => 'bg-emerald-100 text-emerald-700',
                };
            @endphp

            <span class="inline-flex shrink-0 items-center rounded-full px-2.5 py-1 text-xs font-semibold sm:px-3 sm:py-1.5 {{ $statusClasses }}">
                {{ $maintenanceOrder->status->label() }}
            </span>

        </div>

    </div>

</x-cards.card>
