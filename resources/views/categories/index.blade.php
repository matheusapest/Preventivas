@extends('layout.app')

@section('title', 'Categorias')

@section('content')

    <div class="space-y-6">

        {{-- PAGE HEADER --}}
        <x-layout.page-header
            title="Categorias"
            description="Gerencie as categorias disponíveis para utilização no cadastro dos equipamentos."
        >
            <x-slot:breadcrumb>
                Dashboard / Equipamentos / Categorias
            </x-slot:breadcrumb>

            <x-slot:actions>

                @can('create', App\Models\Configuration\Operational\Category::class)
                    <div class="w-full sm:w-auto">

                        <x-buttons.primary
                            :href="route('categorias.create')"
                            class="w-full justify-center sm:w-auto"
                        >
                            Nova Categoria
                        </x-buttons.primary>

                    </div>
                @endcan

            </x-slot:actions>

        </x-layout.page-header>


        {{-- CONTAINER DE DADOS --}}
        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- FILTROS --}}
            <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <form
                    action="{{ route('categorias.index') }}"
                    method="GET"
                    class="p-4 sm:p-5"
                >

                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-[minmax(0,1fr)_280px_auto]">

                        {{-- BUSCA POR NOME --}}
                        <div>

                            <label
                                for="search"
                                class="mb-2 block text-sm font-medium text-slate-700"
                            >
                                Buscar por nome
                            </label>

                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Digite o nome da categoria..."
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >

                        </div>


                        {{-- TIPO DE UNIDADE --}}
                        <div>

                            <label
                                for="unit_type_id"
                                class="mb-2 block text-sm font-medium text-slate-700"
                            >
                                Tipo de Unidade
                            </label>

                            <select
                                id="unit_type_id"
                                name="unit_type_id"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >

                                <option value="">
                                    Todos os tipos
                                </option>

                                @foreach ($unitTypes as $unitType)

                                    <option
                                        value="{{ $unitType->id }}"
                                        @selected(request('unit_type_id') == $unitType->id)
                                    >
                                        {{ $unitType->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- BOTÕES --}}
                        <div class="flex gap-2">

                            <x-buttons.primary
                                type="submit"
                                class="w-full justify-center md:w-auto"
                            >
                                Filtrar
                            </x-buttons.primary>

                            @if (request()->filled('search') || request()->filled('unit_type_id'))

                                <x-buttons.secondary
                                    :href="route('categorias.index')"
                                    class="w-full justify-center md:w-auto"
                                >
                                    Limpar
                                </x-buttons.secondary>

                            @endif

                        </div>

                    </div>

                </form>

            </x-cards.card>


            {{-- ================================================================ --}}
            {{-- VISÃO MOBILE --}}
            {{-- ================================================================ --}}

            <div class="divide-y divide-slate-200 md:hidden">

                @forelse ($categories as $category)

                    <div class="space-y-3 p-4">

                        {{-- NOME --}}
                        <div class="flex items-center justify-between gap-2">

                            <span class="truncate text-base font-bold text-slate-900">
                                {{ $category->name }}
                            </span>

                        </div>


                        {{-- TIPOS DE UNIDADE --}}
                        <div class="space-y-2">

                            <span class="text-xs font-medium text-slate-500">
                                Tipos de Unidade
                            </span>

                            <div class="flex flex-wrap gap-1.5">

                                @forelse ($category->unitTypes as $unitType)

                                    <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700">
                                        {{ $unitType->name }}
                                    </span>

                                @empty

                                    <span class="text-sm text-slate-500">
                                        Nenhum tipo definido
                                    </span>

                                @endforelse

                            </div>

                        </div>


                        {{-- STATUS --}}
                        <div class="flex items-center justify-between gap-2">

                            <span class="text-xs font-medium text-slate-500">
                                Status
                            </span>

                            @if ($category->active)

                                <x-badges.success>
                                    Ativo
                                </x-badges.success>

                            @else

                                <x-badges.danger>
                                    Inativo
                                </x-badges.danger>

                            @endif

                        </div>


                        {{-- AÇÕES --}}
                        <div class="flex items-center justify-between gap-3 border-t border-slate-100 pt-3">

                            @can('update', $category)

                                <x-buttons.warning
                                    :href="route('categorias.edit', $category)"
                                    class="flex-1 justify-center text-xs"
                                >
                                    Editar
                                </x-buttons.warning>

                            @endcan


                            @can('toggleActive', $category)

                                <form
                                    action="{{ route('categorias.toggle-active', $category) }}"
                                    method="POST"
                                    id="toggle-form-category-{{ $category->id }}"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <label class="inline-flex cursor-pointer items-center gap-2.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 transition active:bg-slate-100">

                                        <span
                                            class="text-xs font-semibold {{ $category->active ? 'text-emerald-700' : 'text-slate-500' }}"
                                        >
                                            {{ $category->active ? 'Ativo' : 'Inativo' }}
                                        </span>

                                        <input
                                            type="checkbox"
                                            class="peer sr-only"
                                            {{ $category->active ? 'checked' : '' }}
                                            onchange="document.getElementById('toggle-form-category-{{ $category->id }}').submit()"
                                        >

                                        <div class="peer relative h-5 w-9 rounded-full bg-slate-300 transition-colors duration-200 peer-checked:bg-emerald-500 after:absolute after:start-[2px] after:top-[2px] after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full">
                                        </div>

                                    </label>

                                </form>

                            @endcan

                        </div>

                    </div>

                @empty

                    <div class="p-6 text-center text-sm text-slate-500">

                        @if (request()->filled('search') || request()->filled('unit_type_id'))

                            Nenhuma categoria encontrada para os filtros informados.

                        @else

                            Nenhuma categoria cadastrada.

                        @endif

                    </div>

                @endforelse

            </div>


            {{-- ================================================================ --}}
            {{-- VISÃO DESKTOP --}}
            {{-- ================================================================ --}}

            <div class="hidden max-w-full overflow-x-hidden md:block md:overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">

                    <thead class="bg-slate-50">

                        <tr>

                            <th
                                scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500"
                            >
                                Nome da Categoria
                            </th>

                            <th
                                scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500"
                            >
                                Tipos de Unidade
                            </th>

                            <th
                                scope="col"
                                class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500"
                            >
                                Status
                            </th>

                            <th
                                scope="col"
                                class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500"
                            >
                                Ações
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-200 bg-white">

                        @forelse ($categories as $category)

                            <tr class="transition hover:bg-slate-50/80">

                                {{-- NOME --}}
                                <td class="px-6 py-4 font-medium text-slate-900">

                                    {{ $category->name }}

                                </td>


                                {{-- TIPOS DE UNIDADE --}}
                                <td class="px-6 py-4">

                                    <div class="flex flex-wrap gap-1.5">

                                        @forelse ($category->unitTypes as $unitType)

                                            <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700">
                                                {{ $unitType->name }}
                                            </span>

                                        @empty

                                            <span class="text-slate-500">
                                                Nenhum tipo definido
                                            </span>

                                        @endforelse

                                    </div>

                                </td>


                                {{-- STATUS --}}
                                <td class="px-6 py-4 text-center">

                                    @if ($category->active)

                                        <x-badges.success>
                                            Ativo
                                        </x-badges.success>

                                    @else

                                        <x-badges.danger>
                                            Inativo
                                        </x-badges.danger>

                                    @endif

                                </td>


                                {{-- AÇÕES --}}
                                <td class="px-6 py-4">

                                    <div class="flex justify-center gap-2">

                                        @can('update', $category)

                                            <x-buttons.warning
                                                :href="route('categorias.edit', $category)"
                                            >
                                                Editar
                                            </x-buttons.warning>

                                        @endcan


                                        @can('toggleActive', $category)

                                            <form
                                                action="{{ route('categorias.toggle-active', $category) }}"
                                                method="POST"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                @if ($category->active)

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

                                <td
                                    colspan="4"
                                    class="px-6 py-8 text-center text-slate-500"
                                >

                                    @if (request()->filled('search') || request()->filled('unit_type_id'))

                                        Nenhuma categoria encontrada para os filtros informados.

                                    @else

                                        Nenhuma categoria cadastrada.

                                    @endif

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINAÇÃO --}}
            @if ($categories->hasPages())

                <div class="border-t border-slate-200 px-4 py-3 sm:px-6">

                    {{ $categories->links() }}

                </div>

            @endif

        </x-cards.card>

    </div>

@endsection
