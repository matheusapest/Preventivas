<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Header --}}
    <div class="border-b border-slate-200 px-3.5 py-3 sm:px-6 sm:py-4">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                    Ordens de Serviço
                </h2>
                <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
                    Acompanhe os equipamentos em processo de reparo externo.
                </p>
            </div>
        </div>
    </div>

    {{-- ================================================================
         VISUALIZAÇÃO MOBILE
    ================================================================= --}}

    <div class="divide-y divide-slate-200 md:hidden">

        @forelse ($maintenanceOrders as $maintenanceOrder)

            @php
                $shipment = $maintenanceOrder->shipments->first();
                $maintenanceReceipt = $shipment?->receipt;

                $statusClasses = match ($maintenanceOrder->status) {
                    \App\Enums\MaintenanceOrderStatus::IN_REPAIR => 'bg-amber-100 text-amber-700',
                    \App\Enums\MaintenanceOrderStatus::IN_VALIDATION => 'bg-blue-100 text-blue-700',
                    \App\Enums\MaintenanceOrderStatus::AWAITING_RESEND => 'bg-orange-100 text-orange-700',
                    \App\Enums\MaintenanceOrderStatus::COMPLETED => 'bg-emerald-100 text-emerald-700',
                };
            @endphp

            <div class="space-y-3.5 p-3.5">

                {{-- OS + Status --}}
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600">
                        OS #{{ $maintenanceOrder->id }}
                    </span>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClasses }}">
                        {{ $maintenanceOrder->status->label() }}
                    </span>
                </div>

                {{-- Equipamento Principal --}}
                <div class="rounded-lg bg-slate-50 p-2.5">
                    <p class="text-xs font-semibold text-slate-800 break-words">
                        {{ $maintenanceOrder->equipment?->name ?? 'Não informado' }}
                    </p>
                    <p class="mt-0.5 text-xs text-slate-500">
                        Patrimônio:
                        <span class="font-mono font-medium text-slate-700">
                            {{ $maintenanceOrder->equipment?->asset_number ?? '—' }}
                        </span>
                    </p>
                </div>

                {{-- Empresa Destino --}}
                <div class="border-t border-slate-100 pt-3">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                        Empresa Destino
                    </p>
                    <p class="mt-0.5 text-xs font-semibold text-slate-800 break-words">
                        {{ $shipment?->company?->name ?? 'Sem Empresa' }}
                    </p>
                </div>

                {{-- Informações em Linhas Lógicas (Envio / Recebimento) --}}
                <div class="space-y-3 border-t border-slate-100 pt-3">

                    {{-- Envio --}}
                    <div class="space-y-2">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Envio</p>
                        <div class="grid grid-cols-2 gap-3 rounded-lg bg-slate-50/60 p-2.5">
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Filial de Envio</p>
                                <p class="mt-0.5 text-xs font-medium text-slate-700 break-words">{{ $shipment?->originBranch?->name ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Enviado por</p>
                                <p class="mt-0.5 text-xs font-medium text-slate-700 break-words">{{ $shipment?->sender?->name ?? '—' }}</p>
                            </div>
                            <div class="col-span-2 border-t border-slate-200/60 pt-1.5">
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Data de Envio</p>
                                <p class="mt-0.5 text-xs font-medium text-slate-700 whitespace-nowrap">{{ $shipment?->sent_at?->format('d/m/Y H:i') ?? '—' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Recebimento --}}
                    <div class="space-y-2 border-t border-dashed border-slate-200 pt-3">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Recebimento</p>
                        <div class="grid grid-cols-2 gap-3 rounded-lg bg-slate-50/60 p-2.5">
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Filial Recebimento</p>
                                <p class="mt-0.5 text-xs font-medium text-slate-700 break-words">{{ $maintenanceReceipt?->receivingBranch?->name ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Recebido por</p>
                                <p class="mt-0.5 text-xs font-medium text-slate-700 break-words">{{ $maintenanceReceipt?->receiver?->name ?? '—' }}</p>
                            </div>
                            <div class="col-span-2 border-t border-slate-200/60 pt-1.5">
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Data de Recebimento</p>
                                <p class="mt-0.5 text-xs font-medium text-slate-700 whitespace-nowrap">{{ $maintenanceReceipt?->received_at?->format('d/m/Y H:i') ?? '—' }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Ações --}}
                <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-3">
                    <x-buttons.secondary :href="route('reparos_externos.show', $maintenanceOrder)" class="w-full justify-center text-xs py-2 sm:w-auto">
                        Visualizar
                    </x-buttons.secondary>

                    @if ($maintenanceOrder->status === \App\Enums\MaintenanceOrderStatus::IN_REPAIR && $shipment && $shipment->status === \App\Enums\MaintenanceShipmentStatus::SENT && !$maintenanceReceipt)
                        <x-buttons.primary :href="route('reparos_externos.receber.form', $shipment)" class="w-full justify-center text-xs py-2 sm:w-auto">
                            Receber
                        </x-buttons.primary>
                    @endif

                    @if ($maintenanceOrder->status === \App\Enums\MaintenanceOrderStatus::IN_VALIDATION && $maintenanceReceipt && $maintenanceReceipt->validated_at === null)
                        <x-buttons.primary :href="route('reparos_externos.validar.form', $maintenanceReceipt)" class="w-full justify-center text-xs py-2 sm:w-auto">
                            Validar
                        </x-buttons.primary>
                    @endif
                </div>

            </div>

        @empty
            <div class="p-6 text-center">
                <p class="text-sm font-medium text-slate-700">Nenhuma ordem de serviço encontrada.</p>
                <p class="mt-1 text-xs text-slate-500">Os equipamentos enviados para reparo externo aparecerão aqui.</p>
            </div>
        @endforelse

    </div>

    {{-- ================================================================
         VISUALIZAÇÃO DESKTOP (Todas as 12 colunas sem unificar)
    ================================================================= --}}

    <div class="hidden overflow-x-auto md:block">

        <table class="w-full min-w-[1450px] divide-y divide-slate-200 text-left text-xs">

            <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="whitespace-nowrap px-3 py-3 pl-4 sm:pl-6">OS</th>
                    <th class="whitespace-nowrap px-3 py-3 max-w-[200px]">Equipamento</th>
                    <th class="whitespace-nowrap px-3 py-3">Patrimônio</th>
                    <th class="whitespace-nowrap px-3 py-3">Filial Envio</th>
                    <th class="whitespace-nowrap px-3 py-3">Empresa Destino</th>
                    <th class="whitespace-nowrap px-3 py-3">Enviado por</th>
                    <th class="whitespace-nowrap px-3 py-3">Enviado em</th>
                    <th class="whitespace-nowrap px-3 py-3">Recebido por</th>
                    <th class="whitespace-nowrap px-3 py-3">Recebido em</th>
                    <th class="whitespace-nowrap px-3 py-3">Filial Recebimento</th>
                    <th class="whitespace-nowrap px-3 py-3">Status</th>
                    <th class="sticky right-0 bg-slate-50 whitespace-nowrap px-3 py-3 text-right pr-4 sm:pr-6 shadow-[-6px_0_10px_-4px_rgba(0,0,0,0.05)]">Ações</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200 bg-white">

                @forelse ($maintenanceOrders as $maintenanceOrder)

                    @php
                        $shipment = $maintenanceOrder->shipments->first();
                        $maintenanceReceipt = $shipment?->receipt;

                        $statusClasses = match ($maintenanceOrder->status) {
                            \App\Enums\MaintenanceOrderStatus::IN_REPAIR => 'bg-amber-100 text-amber-700',
                            \App\Enums\MaintenanceOrderStatus::IN_VALIDATION => 'bg-blue-100 text-blue-700',
                            \App\Enums\MaintenanceOrderStatus::AWAITING_RESEND => 'bg-orange-100 text-orange-700',
                            \App\Enums\MaintenanceOrderStatus::COMPLETED => 'bg-emerald-100 text-emerald-700',
                        };
                    @endphp

                    <tr class="transition-colors hover:bg-slate-50/80">

                        <td class="whitespace-nowrap px-3 py-3 pl-4 sm:pl-6 font-bold text-slate-800">
                            OS {{ $maintenanceOrder->id }}
                        </td>

                        <td class="px-3 py-3 font-semibold text-slate-800 whitespace-nowrap overflow-hidden text-ellipsis max-w-[200px]" title="{{ $maintenanceOrder->equipment?->name }}">
                            {{ $maintenanceOrder->equipment?->name ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap px-3 py-3 font-mono text-slate-600">
                            {{ $maintenanceOrder->equipment?->asset_number ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap px-3 py-3 text-slate-700">
                            {{ $shipment?->originBranch?->name ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap px-3 py-3 text-slate-700">
                            {{ $shipment?->company?->name ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap px-3 py-3 text-slate-700">
                            {{ $shipment?->sender?->name ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap px-3 py-3 text-slate-600">
                            {{ $shipment?->sent_at?->format('d/m/Y H:i') ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap px-3 py-3 text-slate-700">
                            {{ $maintenanceReceipt?->receiver?->name ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap px-3 py-3 text-slate-600">
                            {{ $maintenanceReceipt?->received_at?->format('d/m/Y H:i') ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap px-3 py-3 text-slate-700">
                            {{ $maintenanceReceipt?->receivingBranch?->name ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap px-3 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $statusClasses }}">
                                {{ $maintenanceOrder->status->label() }}
                            </span>
                        </td>

                        {{-- Coluna de Ações Fixa à Direita --}}
                        <td class="sticky right-0 bg-white group-hover:bg-slate-50 whitespace-nowrap px-3 py-3 text-right pr-4 sm:pr-6 shadow-[-6px_0_10px_-4px_rgba(0,0,0,0.05)]">
                            <div class="flex items-center justify-end gap-1.5">
                                <x-buttons.secondary :href="route('reparos_externos.show', $maintenanceOrder)" class="text-xs px-2.5 py-1">
                                    Visualizar
                                </x-buttons.secondary>

                                @if ($maintenanceOrder->status === \App\Enums\MaintenanceOrderStatus::IN_REPAIR && $shipment && $shipment->status === \App\Enums\MaintenanceShipmentStatus::SENT && !$maintenanceReceipt)
                                    <x-buttons.primary :href="route('reparos_externos.receber.form', $shipment)" class="text-xs px-2.5 py-1">
                                        Receber
                                    </x-buttons.primary>
                                @endif

                                @if ($maintenanceOrder->status === \App\Enums\MaintenanceOrderStatus::IN_VALIDATION && $maintenanceReceipt && $maintenanceReceipt->validated_at === null)
                                    <x-buttons.primary :href="route('reparos_externos.validar.form', $maintenanceReceipt)" class="text-xs px-2.5 py-1">
                                        Validar
                                    </x-buttons.primary>
                                @endif
                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="12" class="px-6 py-12 text-center">
                            <p class="text-sm font-medium text-slate-700">Nenhuma ordem de serviço encontrada.</p>
                            <p class="mt-1 text-xs text-slate-500">Os equipamentos enviados para reparo externo aparecerão aqui.</p>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Paginação --}}
    @if ($maintenanceOrders->hasPages())
        <div class="border-t border-slate-200 px-4 py-3 sm:px-6">
            {{ $maintenanceOrders->links() }}
        </div>
    @endif

</x-cards.card>
