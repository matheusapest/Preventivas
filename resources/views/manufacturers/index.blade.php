@extends('layout.app')

@section('title', 'Fabricantes')

@section('content')

    <div class="space-y-6">

        {{-- PAGE HEADER (Responsivo) --}}
        <x-layout.page-header
            title="Fabricantes"
            description="Gerencie os fabricantes disponíveis para utilização no cadastro dos equipamentos."
        >
            <x-slot:breadcrumb>
                Dashboard / Equipamentos / Fabricantes
            </x-slot:breadcrumb>

            <x-slot:actions>
                @can('create', App\Models\Manufacturer::class)
                    <div class="w-full sm:w-auto">
                        <x-buttons.primary
                            :href="route('fabricantes.create')"
                            class="w-full justify-center sm:w-auto"
                        >
                            Novo Fabricante
                        </x-buttons.primary>
                    </div>
                @endcan
            </x-slot:actions>
        </x-layout.page-header>

        {{-- CONTAINER DE DADOS --}}
        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- 1. VISÃO EM CARDS (Apenas Mobile: block md:hidden) --}}
            <div class="divide-y divide-slate-200 md:hidden">
                @forelse ($manufacturers as $manufacturer)
                    <div class="p-4 space-y-3">

                        {{-- Nome do Fabricante --}}
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-bold text-slate-900 text-base truncate">
                                {{ $manufacturer->name }}
                            </span>
                        </div>

                        {{-- Ações no Mobile: Botão Editar + Switch --}}
                        <div class="flex items-center justify-between gap-3 pt-2 border-t border-slate-100">

                            @can('update', $manufacturer)
                                <x-buttons.warning
                                    :href="route('fabricantes.edit', $manufacturer)"
                                    class="flex-1 justify-center text-xs py-2"
                                >
                                    Editar
                                </x-buttons.warning>
                            @endcan

                            @can('toggleActive', $manufacturer)
                                <form action="{{ route('fabricantes.toggle-active', $manufacturer) }}" method="POST" id="toggle-form-manufacturer-{{ $manufacturer->id }}">
                                    @csrf
                                    @method('PATCH')

                                    <label class="inline-flex cursor-pointer items-center gap-2.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 transition active:bg-slate-100">
                                        <span class="text-xs font-semibold {{ $manufacturer->active ? 'text-emerald-700' : 'text-slate-500' }}">
                                            {{ $manufacturer->active ? 'Ativo' : 'Inativo' }}
                                        </span>

                                        <input
                                            type="checkbox"
                                            class="peer sr-only"
                                            {{ $manufacturer->active ? 'checked' : '' }}
                                            onchange="document.getElementById('toggle-form-manufacturer-{{ $manufacturer->id }}').submit()"
                                        >

                                        <div class="peer relative h-5 w-9 rounded-full bg-slate-300 transition-colors duration-200 peer-checked:bg-emerald-500 after:absolute after:start-[2px] after:top-[2px] after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full"></div>
                                    </label>
                                </form>
                            @endcan

                        </div>

                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-slate-500">
                        Nenhum fabricante cadastrado.
                    </div>
                @endforelse
            </div>

            {{-- 2. VISÃO EM TABELA TRADICIONAL (Apenas Desktop: hidden md:block) --}}
            <div class="hidden max-w-full overflow-x-hidden md:block md:overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">

                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Nome do Fabricante
                            </th>
                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Ações
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($manufacturers as $manufacturer)
                            <tr class="transition hover:bg-slate-50/80">

                                <td class="px-6 py-4 font-medium text-slate-900">
                                    {{ $manufacturer->name }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($manufacturer->active)
                                        <x-badges.success>Ativo</x-badges.success>
                                    @else
                                        <x-badges.danger>Inativo</x-badges.danger>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        @can('update', $manufacturer)
                                            <x-buttons.warning :href="route('fabricantes.edit', $manufacturer)">
                                                Editar
                                            </x-buttons.warning>
                                        @endcan

                                        @can('toggleActive', $manufacturer)
                                            <form action="{{ route('fabricantes.toggle-active', $manufacturer) }}" method="POST">
                                                @csrf
                                                @method('PATCH')

                                                @if($manufacturer->active)
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
                                <td colspan="3" class="px-6 py-8 text-center text-slate-500">
                                    Nenhum fabricante cadastrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- PAGINAÇÃO --}}
            @if ($manufacturers->hasPages())
                <div class="border-t border-slate-200 px-4 py-3 sm:px-6">
                    {{ $manufacturers->links() }}
                </div>
            @endif

        </x-cards.card>

    </div>

@endsection
