<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Header --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">
        <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
            Equipamento
        </h2>
        <p class="mt-0.5 text-xs text-slate-500 sm:mt-1 sm:text-sm">
            Informações do equipamento vinculado à ordem de serviço.
        </p>
    </div>

    {{-- Dados --}}
    <div class="p-4 sm:p-6">

        {{-- 1. VISÃO MOBILE (Lista de itens com divisor fino) --}}
        <div class="block sm:hidden divide-y divide-slate-100 -my-2">

            {{-- Destaque Principal (Patrimônio e Nome) --}}
            <div class="py-3 bg-slate-50/80 -mx-4 px-4 rounded-lg border border-slate-100 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Patrimônio / Equipamento
                </span>
                <div class="mt-1 flex items-baseline justify-between gap-2">
                    <p class="text-base font-extrabold text-slate-900 font-mono">
                        {{ $maintenanceOrder->equipment->asset_number ?? 'N/A' }}
                    </p>
                    <p class="text-sm font-semibold text-slate-700 text-right truncate">
                        {{ $maintenanceOrder->equipment->name ?? 'Não informado' }}
                    </p>
                </div>
            </div>

            <div class="py-2.5 flex justify-between items-center gap-4">
                <span class="text-xs font-medium text-slate-500">Categoria</span>
                <span class="text-xs font-semibold text-slate-800 text-right">
                    {{ $maintenanceOrder->equipment->equipmentModel?->category?->name ?? 'Não informado' }}
                </span>
            </div>

            <div class="py-2.5 flex justify-between items-center gap-4">
                <span class="text-xs font-medium text-slate-500">Fabricante</span>
                <span class="text-xs font-semibold text-slate-800 text-right">
                    {{ $maintenanceOrder->equipment->equipmentModel?->manufacturer?->name ?? 'Não informado' }}
                </span>
            </div>

            <div class="py-2.5 flex justify-between items-center gap-4">
                <span class="text-xs font-medium text-slate-500">Modelo</span>
                <span class="text-xs font-semibold text-slate-800 text-right">
                    {{ $maintenanceOrder->equipment->equipmentModel?->name ?? 'Não informado' }}
                </span>
            </div>

            <div class="py-2.5 flex justify-between items-center gap-4">
                <span class="text-xs font-medium text-slate-500">Nº de Série</span>
                <span class="text-xs font-mono font-semibold text-slate-800 text-right">
                    {{ $maintenanceOrder->equipment->serial_number ?? 'Não informado' }}
                </span>
            </div>

            <div class="py-2.5 flex justify-between items-center gap-4">
                <span class="text-xs font-medium text-slate-500">Filial Atual</span>
                <span class="text-xs font-semibold text-slate-800 text-right">
                    {{ $maintenanceOrder->equipment->branch?->name ?? 'Não informado' }}
                </span>
            </div>

            <div class="py-2.5 flex justify-between items-center gap-4">
                <span class="text-xs font-medium text-slate-500">Status Operacional</span>
                <span class="text-xs font-semibold text-slate-800 text-right">
                    {{ $maintenanceOrder->equipment->operational_status?->label() ?? 'Não informado' }}
                </span>
            </div>

        </div>

        {{-- 2. VISÃO DESKTOP (Grid Tradicional) --}}
        <div class="hidden sm:grid grid-cols-2 gap-5 lg:grid-cols-4">

            {{-- Patrimônio --}}
            <div>
                <span class="text-xs font-medium text-slate-500 sm:text-sm">
                    Patrimônio
                </span>
                <p class="mt-0.5 text-sm font-bold text-slate-800 sm:text-base font-mono">
                    {{ $maintenanceOrder->equipment->asset_number ?? 'Não informado' }}
                </p>
            </div>

            {{-- Nome --}}
            <div>
                <span class="text-xs font-medium text-slate-500 sm:text-sm">
                    Equipamento
                </span>
                <p class="mt-0.5 text-sm font-semibold text-slate-800 sm:text-base">
                    {{ $maintenanceOrder->equipment->name ?? 'Não informado' }}
                </p>
            </div>

            {{-- Categoria --}}
            <div>
                <span class="text-xs font-medium text-slate-500 sm:text-sm">
                    Categoria
                </span>
                <p class="mt-0.5 text-sm text-slate-700">
                    {{ $maintenanceOrder->equipment->equipmentModel?->category?->name ?? 'Não informado' }}
                </p>
            </div>

            {{-- Fabricante --}}
            <div>
                <span class="text-xs font-medium text-slate-500 sm:text-sm">
                    Fabricante
                </span>
                <p class="mt-0.5 text-sm text-slate-700">
                    {{ $maintenanceOrder->equipment->equipmentModel?->manufacturer?->name ?? 'Não informado' }}
                </p>
            </div>

            {{-- Modelo --}}
            <div>
                <span class="text-xs font-medium text-slate-500 sm:text-sm">
                    Modelo
                </span>
                <p class="mt-0.5 text-sm text-slate-700">
                    {{ $maintenanceOrder->equipment->equipmentModel?->name ?? 'Não informado' }}
                </p>
            </div>

            {{-- Número de série --}}
            <div>
                <span class="text-xs font-medium text-slate-500 sm:text-sm">
                    Número de Série
                </span>
                <p class="mt-0.5 font-mono text-sm text-slate-700">
                    {{ $maintenanceOrder->equipment->serial_number ?? 'Não informado' }}
                </p>
            </div>

            {{-- Filial --}}
            <div>
                <span class="text-xs font-medium text-slate-500 sm:text-sm">
                    Filial Atual
                </span>
                <p class="mt-0.5 text-sm font-medium text-slate-800">
                    {{ $maintenanceOrder->equipment->branch?->name ?? 'Não informado' }}
                </p>
            </div>

            {{-- Status operacional --}}
            <div>
                <span class="text-xs font-medium text-slate-500 sm:text-sm">
                    Status Operacional
                </span>
                <p class="mt-0.5 text-sm font-medium text-slate-800">
                    {{ $maintenanceOrder->equipment->operational_status?->label() ?? 'Não informado' }}
                </p>
            </div>

        </div>

    </div>

</x-cards.card>
