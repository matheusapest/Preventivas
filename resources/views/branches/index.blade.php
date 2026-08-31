@extends('layout.app')

@section('title', 'Filiais')

@section('content')

    <div class="space-y-6">

        {{-- PAGE HEADER (Responsivo) --}}
        <x-layout.page-header title="Filiais" description="Cadastro de filiais do sistema.">

            <x-slot:breadcrumb>
                Dashboard / Cadastros / Filiais
            </x-slot:breadcrumb>

            <x-slot:actions>
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    @can('create', App\Models\BranchCode::class)
                        <x-buttons.secondary :href="route('codigos-filiais.index')" class="w-full justify-center sm:w-auto">
                            Gerenciar Números de Filiais
                        </x-buttons.secondary>
                    @endcan

                    @can('create', App\Models\Branch::class)
                        <x-buttons.primary :href="route('filiais.create')" class="w-full justify-center sm:w-auto">
                            Nova Filial
                        </x-buttons.primary>
                    @endcan
                </div>
            </x-slot:actions>

        </x-layout.page-header>

        {{-- CONTAINER DE DADOS --}}
        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- 1. VISÃO EM CARDS (Apenas Mobile: block md:hidden) --}}
            <div class="divide-y divide-slate-200 md:hidden">
                @forelse($branches as $branch)
                    <div class="p-4 space-y-3">

                        {{-- Nome da Filial + Número/Código --}}
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    {{ $branch->company->name }}
                                </span>
                                <h3 class="font-bold text-slate-900 text-base leading-tight">
                                    {{ $branch->name }}
                                </h3>
                            </div>
                            <span
                                class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-bold text-slate-700 border border-slate-200 shrink-0">
                                Nº {{ $branch->branchCode->code }}
                            </span>
                        </div>

                        {{-- Detalhes da Filial (Cidade, Estado e Tipo) --}}
                        <div
                            class="grid grid-cols-2 gap-2 text-xs text-slate-600 bg-slate-50/60 p-2.5 rounded-lg border border-slate-100">
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-semibold">Localização</span>
                                <span class="font-medium text-slate-800">{{ $branch->city }} /
                                    {{ $branch->state->value }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-semibold">Tipo</span>
                                <span class="font-medium text-slate-800">{{ $branch->type->label() }}</span>
                            </div>
                        </div>

                        {{-- Ações no Mobile: Editar + Switch Liga/Desliga --}}
                        <div class="flex items-center justify-between gap-3 pt-2 border-t border-slate-100">

                            {{-- Botão Editar --}}
                            @can('update', $branch)
                                <x-buttons.warning :href="route('filiais.edit', $branch)" class="flex-1 justify-center text-xs py-2">
                                    Editar
                                </x-buttons.warning>
                            @endcan

                            {{-- Switch Liga / Desliga --}}
                            @can('toggleActive', $branch)
                                <form action="{{ route('filiais.toggle-active', $branch) }}" method="POST"
                                    id="toggle-form-branch-{{ $branch->id }}">
                                    @csrf
                                    @method('PATCH')

                                    <label
                                        class="inline-flex cursor-pointer items-center gap-2.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 transition active:bg-slate-100">
                                        <span
                                            class="text-xs font-semibold {{ $branch->active ? 'text-emerald-700' : 'text-slate-500' }}">
                                            {{ $branch->active ? 'Ativa' : 'Inativa' }}
                                        </span>

                                        <input type="checkbox" class="peer sr-only" {{ $branch->active ? 'checked' : '' }}
                                            onchange="document.getElementById('toggle-form-branch-{{ $branch->id }}').submit()">

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
                        Nenhuma filial cadastrada.
                    </div>
                @endforelse
            </div>

            {{-- 2. VISÃO EM TABELA TRADICIONAL (Apenas Desktop: hidden md:block) --}}
            <div class="hidden max-w-full overflow-x-hidden md:block md:overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">

                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Empresa
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Nº Filial
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Filial
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">Cidade
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                UF</th>
                            <th scope="col"
                                class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Tipo</th>
                            <th scope="col"
                                class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($branches as $branch)
                            <tr class="transition hover:bg-slate-50/80">

                                <td class="px-6 py-4 text-slate-700">
                                    {{ $branch->company->name }}
                                </td>
                                
                                <td class="px-6 py-4 font-mono text-xs font-semibold text-slate-600">
                                    {{ $branch->branchCode->code }}
                                </td>

                                <td class="px-6 py-4 font-medium text-slate-900">
                                    {{ $branch->name }}
                                </td>

                                <td class="px-6 py-4 text-slate-600">
                                    {{ $branch->city }}
                                </td>

                                <td class="px-6 py-4 text-center font-semibold text-slate-600">
                                    {{ $branch->state->value }}
                                </td>

                                <td class="px-6 py-4 text-center text-slate-600">
                                    {{ $branch->type->label() }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if ($branch->active)
                                        <x-badges.success>Ativa</x-badges.success>
                                    @else
                                        <x-badges.danger>Inativa</x-badges.danger>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        @can('update', $branch)
                                            <x-buttons.warning :href="route('filiais.edit', $branch)">
                                                Editar
                                            </x-buttons.warning>
                                        @endcan

                                        @can('toggleActive', $branch)
                                            <form action="{{ route('filiais.toggle-active', $branch) }}" method="POST">
                                                @csrf
                                                @method('PATCH')

                                                @if ($branch->active)
                                                    <x-buttons.danger type="submit">
                                                        Inativar
                                                    </x-buttons.danger>
                                                @else
                                                    <x-buttons.success type="submit">
                                                        Ativar
                                                    </x-buttons.success>
                                                @endif
                                            </form>
                                        @endcan
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                    Nenhuma filial cadastrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- PAGINAÇÃO (Visível em ambas as telas) --}}
            @if ($branches->hasPages())
                <div class="border-t border-slate-200 px-4 py-3 sm:px-6">
                    {{ $branches->links() }}
                </div>
            @endif

        </x-cards.card>

    </div>

@endsection
