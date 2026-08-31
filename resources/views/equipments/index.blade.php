@extends('layout.app')

@section('title', 'Equipamentos')

@section('content')

    <div class="space-y-6">

        {{-- PAGE HEADER (Responsivo) --}}
        <x-layout.page-header title="Equipamentos" description="Gerenciamento de cadastro de equipamentos.">
            <x-slot:breadcrumb>
                Dashboard / Ativos / Equipamentos
            </x-slot:breadcrumb>

            <x-slot:actions>
                @can('create', App\Models\Equipment::class)
                    <div class="w-full sm:w-auto">
                        <x-buttons.primary :href="route('equipamentos.create')" class="w-full justify-center sm:w-auto">
                            Novo Equipamento
                        </x-buttons.primary>
                    </div>
                @endcan
            </x-slot:actions>
        </x-layout.page-header>

        {{-- CONTAINER DE DADOS --}}
        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- 1. VISÃO EM CARDS (Apenas Mobile: block md:hidden) --}}
            <div class="divide-y divide-slate-200 md:hidden">
                @forelse($equipments as $equipment)
                    <div class="p-4 space-y-3">

                        {{-- Cabeçalho do Card: Nome, ID e Badge de Status --}}
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span
                                    class="text-xs font-semibold uppercase tracking-wider text-slate-400">#{{ $equipment->id }}</span>
                                <h3 class="font-bold text-slate-900 text-base leading-snug">
                                    {{ $equipment->name }}
                                </h3>
                            </div>

                            @if ($equipment->active)
                                <x-badges.success>Ativo</x-badges.success>
                            @else
                                <x-badges.danger>Inativo</x-badges.danger>
                            @endif
                        </div>

                        {{-- Detalhes em Grid no Mobile --}}
                        <div
                            class="grid grid-cols-2 gap-2 text-xs text-slate-600 bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                            <div>
                                <span class="font-semibold text-slate-800 block">Filial:</span>
                                {{ $equipment->branch->name ?? '-' }}
                            </div>
                            <div>
                                <span class="font-semibold text-slate-800 block">Modelo:</span>
                                {{ $equipment->equipmentModel->name ?? '-' }}
                            </div>
                            <div>
                                <span class="font-semibold text-slate-800 block">Categoria:</span>
                                {{ $equipment->equipmentModel->category->name ?? '-' }}
                            </div>
                            <div>
                                <span class="font-semibold text-slate-800 block">Fabricante:</span>
                                {{ $equipment->equipmentModel->manufacturer->name ?? '-' }}
                            </div>
                            <div>
                                <span class="font-semibold text-slate-800 block">Patrimônio:</span>
                                {{ $equipment->asset_number ?? '-' }}
                            </div>
                            <div>
                                <span class="font-semibold text-slate-800 block">Nº Série:</span>
                                {{ $equipment->serial_number ?? '-' }}
                            </div>

                            <div>
                                <span class="font-semibold text-slate-800 block">Status Operacional</span>
                                {{ $equipment->operational_status?->label() ?? '-' }}
                            </div>
                        </div>

                        {{-- Ações no Mobile: Editar + Switch --}}
                        <div class="flex items-center justify-between gap-3 pt-2 border-t border-slate-100">

                            @can('update', $equipment)
                                <x-buttons.warning :href="route('equipamentos.edit', $equipment)" class="flex-1 justify-center text-xs py-2">
                                    Editar
                                </x-buttons.warning>
                            @endcan

                            @can('toggleActive', $equipment)
                                <form action="{{ route('equipamentos.toggle-active', $equipment) }}" method="POST"
                                    id="toggle-form-equipment-{{ $equipment->id }}">
                                    @csrf
                                    @method('PATCH')

                                    <label
                                        class="inline-flex cursor-pointer items-center gap-2.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 transition active:bg-slate-100">
                                        <span
                                            class="text-xs font-semibold {{ $equipment->active ? 'text-emerald-700' : 'text-slate-500' }}">
                                            {{ $equipment->active ? 'Ativo' : 'Inativo' }}
                                        </span>

                                        <input type="checkbox" class="peer sr-only" {{ $equipment->active ? 'checked' : '' }}
                                            onchange="document.getElementById('toggle-form-equipment-{{ $equipment->id }}').submit()">

                                        <div
                                            class="peer relative h-5 w-9 rounded-full bg-slate-300 transition-colors duration-200 peer-checked:bg-emerald-500 after:absolute after:start-[2px] after:top-[2px] after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full">
                                        </div>
                                    </label>
                                </form>
                            @endcan

                        </div>

                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-slate-500">
                        Nenhum equipamento cadastrado.
                    </div>
                @endforelse
            </div>

            {{-- 2. VISÃO EM TABELA TRADICIONAL (Apenas Desktop: hidden md:block) --}}
            <div class="hidden max-w-full overflow-x-hidden md:block md:overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">

                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Nome
                                Equipamento</th>
                            <th scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Modelo
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Filial
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Patrimônio
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Nº Série
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Fabricante
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Categoria
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Status Operacional</th>
                            <th scope="col"
                                class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Status </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($equipments as $equipment)
                            <tr class="transition hover:bg-slate-50/80">

                                <td class="px-6 py-4 font-medium text-slate-900">
                                    {{ $equipment->name }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $equipment->equipmentModel->name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $equipment->branch->name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $equipment->asset_number ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $equipment->serial_number ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $equipment->equipmentModel->manufacturer->name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $equipment->equipmentModel->category->name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    {{ $equipment->operational_status?->label() ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if ($equipment->active)
                                        <x-badges.success>Ativo</x-badges.success>
                                    @else
                                        <x-badges.danger>Inativo</x-badges.danger>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        @can('update', $equipment)
                                            <x-buttons.warning :href="route('equipamentos.edit', $equipment)">
                                                Editar
                                            </x-buttons.warning>
                                        @endcan

                                        @can('toggleActive', $equipment)
                                            <form action="{{ route('equipamentos.toggle-active', $equipment) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')

                                                @if ($equipment->active)
                                                    <x-buttons.danger type="submit">Inativar</x-buttons.danger>
                                                @else
                                                    <x-buttons.success type="submit">Ativar</x-buttons.success>
                                                @endif
                                            </form>
                                        @endcan
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-8 text-center text-slate-500">
                                    Nenhum equipamento cadastrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- PAGINAÇÃO --}}
            @if ($equipments->hasPages())
                <div class="border-t border-slate-200 px-4 py-3 sm:px-6">
                    {{ $equipments->links() }}
                </div>
            @endif

        </x-cards.card>

    </div>

@endsection
