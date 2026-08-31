@extends('layout.app')

@section('title', 'Recebimento de Equipamentos')

@section('content')

    <div class="space-y-6">

        {{-- Header da Página --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-xl font-semibold text-slate-800 sm:text-2xl">
                    Recebimento de Equipamentos
                </h1>

                <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                    Consulte os equipamentos enviados para reparo externo que ainda aguardam recebimento.
                </p>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">

                {{-- Voltar ao painel de OS --}}
                <a href="{{ route('reparos_externos.index') }}"
                    class="inline-flex w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 sm:w-auto sm:text-sm">
                    Voltar ao Painel de OS
                </a>

                {{-- Recebimento múltiplo --}}
                <a href="{{ route('reparos_externos.recebimentos.multiplos') }}"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto sm:text-sm">
                    Receber Equipamentos
                </a>

            </div>

        </div>

        {{-- Mensagem de sucesso --}}
        @if (session('success'))
            <x-alerts.success title="Recebimento concluído">
                {{ session('success') }}
            </x-alerts.success>
        @endif

        {{-- Mensagem de erro --}}
        @if (session('error'))
            <x-alerts.error title="Não foi possível concluir o recebimento">
                {{ session('error') }}
            </x-alerts.error>
        @endif

        {{-- Card Resumo --}}
        <x-cards.card class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="p-4 sm:p-5">

                <div class="flex items-center justify-between gap-4">

                    <div>
                        <p class="text-xs font-medium text-slate-500 sm:text-sm">
                            Aguardando recebimento
                        </p>

                        <p class="mt-1 text-2xl font-bold text-slate-800">
                            {{ $maintenanceShipments->total() }}
                        </p>
                    </div>

                    <div class="rounded-lg bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-700">
                        Pendentes
                    </div>

                </div>

            </div>

        </x-cards.card>

        {{-- Lista de equipamentos --}}
        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- Cabeçalho da lista --}}
            <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

                <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                    Equipamentos Aguardando Recebimento
                </h2>

                <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
                    Equipamentos que foram enviados e ainda não possuem recebimento registrado.
                </p>

            </div>

            @if ($maintenanceShipments->isNotEmpty())

                {{-- ================================================================
                     VISUALIZAÇÃO MOBILE (Cards compactos)
                ================================================================= --}}
                <div class="divide-y divide-slate-200 sm:hidden">

                    @foreach ($maintenanceShipments as $shipment)
                        @php
                            $equipment = $shipment->maintenanceOrder?->equipment;
                        @endphp

                        <div class="space-y-3 p-4">

                            {{-- Equipamento + Status --}}
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800 break-words">
                                        {{ $equipment?->name ?? 'Não informado' }}
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        {{ $equipment?->equipmentModel?->name ?? 'Modelo não informado' }}
                                    </p>
                                </div>

                                <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-[11px] font-semibold text-amber-700 shrink-0">
                                    {{ $shipment->status->label() }}
                                </span>
                            </div>

                            {{-- Identificadores (Patrimônio + Serial) --}}
                            <div class="grid grid-cols-2 gap-2 rounded-lg bg-slate-50 p-2.5 text-xs">
                                <div>
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Patrimônio</span>
                                    <span class="font-mono font-medium text-slate-700">
                                        {{ $equipment?->asset_number ?? '—' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Serial</span>
                                    <span class="font-mono font-medium text-slate-700">
                                        {{ $equipment?->serial_number ?? '—' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Detalhes do Envio --}}
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Origem</span>
                                    <span class="text-slate-700 font-medium">
                                        {{ $shipment->originBranch?->name ?? '—' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Destino</span>
                                    <span class="text-slate-700 font-medium">
                                        {{ $shipment->company?->name ?? '—' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Ciclo</span>
                                    <span class="text-slate-700 font-medium">
                                        #{{ $shipment->sequence }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Enviado em</span>
                                    <span class="text-slate-700 font-medium whitespace-nowrap">
                                        {{ $shipment->sent_at?->format('d/m/Y H:i') ?? '—' }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    @endforeach

                </div>

                {{-- ================================================================
                     VISUALIZAÇÃO DESKTOP (Tabela Otimizada)
                ================================================================= --}}
                <div class="hidden overflow-x-auto sm:block">

                    <table class="w-full text-left border-collapse">

                        <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-500">

                            <tr>

                                <th class="px-4 py-3 pl-6">
                                    Equipamento / Modelo
                                </th>

                                <th class="px-3 py-3">
                                    Patrimônio / Serial
                                </th>

                                <th class="px-3 py-3">
                                    Origem / Destino
                                </th>

                                <th class="px-3 py-3">
                                    Ciclo / Enviado em
                                </th>

                                <th class="px-3 py-3 pr-6 text-right">
                                    Status
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white text-xs">

                            @foreach ($maintenanceShipments as $shipment)
                                @php
                                    $equipment = $shipment->maintenanceOrder?->equipment;
                                @endphp

                                <tr class="transition hover:bg-slate-50/80">

                                    {{-- Equipamento --}}
                                    <td class="px-4 py-3.5 pl-6">
                                        <div class="font-semibold text-slate-800 leading-tight">
                                            {{ $equipment?->name ?? 'Não informado' }}
                                        </div>
                                        <div class="mt-0.5 text-[11px] text-slate-500">
                                            {{ $equipment?->equipmentModel?->name ?? 'Modelo não informado' }}
                                        </div>
                                    </td>

                                    {{-- Patrimônio / Serial --}}
                                    <td class="px-3 py-3.5">
                                        <div class="font-mono text-slate-700 font-medium">
                                            Pat: {{ $equipment?->asset_number ?? '—' }}
                                        </div>
                                        <div class="mt-0.5 font-mono text-[11px] text-slate-500">
                                            SN: {{ $equipment?->serial_number ?? '—' }}
                                        </div>
                                    </td>

                                    {{-- Filial / Empresa --}}
                                    <td class="px-3 py-3.5">
                                        <div class="font-medium text-slate-800">
                                            {{ $shipment->originBranch?->name ?? '—' }}
                                        </div>
                                        <div class="mt-0.5 text-[11px] text-slate-500">
                                            Para: {{ $shipment->company?->name ?? '—' }}
                                        </div>
                                    </td>

                                    {{-- Ciclo / Data --}}
                                    <td class="px-3 py-3.5">
                                        <div class="font-semibold text-slate-700">
                                            Ciclo #{{ $shipment->sequence }}
                                        </div>
                                        <div class="mt-0.5 text-[11px] text-slate-500 whitespace-nowrap">
                                            {{ $shipment->sent_at?->format('d/m/Y H:i') ?? '—' }}
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-3 py-3.5 pr-6 text-right">
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-[11px] font-semibold text-amber-700">
                                            {{ $shipment->status->label() }}
                                        </span>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- Paginação --}}
                @if ($maintenanceShipments->hasPages())
                    <div class="border-t border-slate-200 px-4 py-3 sm:px-6">
                        {{ $maintenanceShipments->links() }}
                    </div>
                @endif

            @else

                {{-- Estado Vazio --}}
                <div class="p-6 sm:p-8">
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-center sm:text-left">
                        <p class="text-sm font-semibold text-emerald-800">
                            Nenhum equipamento aguardando recebimento.
                        </p>
                        <p class="mt-1 text-xs text-emerald-700 sm:text-sm">
                            Todos os equipamentos enviados para reparo externo estão com o recebimento registrado.
                        </p>
                    </div>
                </div>

            @endif

        </x-cards.card>

    </div>

@endsection
