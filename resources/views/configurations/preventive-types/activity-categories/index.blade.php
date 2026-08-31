@extends('layout.app')

@section('title', 'Categorias de Atividades')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- CABEÇALHO                                                 --}}
        {{-- ========================================================= --}}

        <x-layout.page-header title="Categorias de Atividades"
            description="Gerencie as categorias utilizadas para organizar as atividades.">

            <x-slot:breadcrumb>
                Dashboard / Configurações / Categorias de Atividades
            </x-slot:breadcrumb>

            <x-slot:actions>

                <div class="grid grid-cols-2 gap-2 sm:flex sm:items-center">

                    <x-buttons.secondary :href="route('configuracoes.tipos-preventivas.index')" class="w-full justify-center sm:w-auto">
                        Voltar
                    </x-buttons.secondary>

                    <x-buttons.primary :href="route('configuracoes.activity-categories.create')" class="w-full justify-center sm:w-auto">
                        Nova Categoria
                    </x-buttons.primary>

                </div>

            </x-slot:actions>

        </x-layout.page-header>


        {{-- ========================================================= --}}
        {{-- LISTAGEM (CARDS MOBILE / TABELA DESKTOP)                  --}}
        {{-- ========================================================= --}}

        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- --------------------------------------------------------- --}}
            {{-- VIEW MOBILE (CARD-BASED)                                 --}}
            {{-- --------------------------------------------------------- --}}

            <div class="block divide-y divide-slate-200 md:hidden">

                @forelse ($activityCategories as $activityCategory)

                    <div class="p-4 space-y-3">

                        {{-- Nome e Status --}}
                        <div class="flex items-start justify-between gap-3">

                            <div>

                                <span class="text-xs text-slate-500 uppercase font-semibold tracking-wider">
                                    Categoria
                                </span>

                                <h3 class="text-sm font-semibold text-slate-900 mt-0.5">
                                    {{ $activityCategory->name }}
                                </h3>

                            </div>

                            @if ($activityCategory->active)
                                <span
                                    class="inline-flex shrink-0 items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                    Ativa
                                </span>
                            @else
                                <span
                                    class="inline-flex shrink-0 items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                    Inativa
                                </span>
                            @endif

                        </div>

                        {{-- Ações Mobile --}}
                        <div class="pt-2 flex flex-wrap items-center gap-2 border-t border-slate-100">

                            {{-- Editar --}}
                            @can('update', $activityCategory)
                                <x-buttons.secondary :href="route('configuracoes.activity-categories.edit', $activityCategory)" class="flex-1 justify-center">
                                    Editar
                                </x-buttons.secondary>
                            @endcan

                            {{-- Ativar / Inativar --}}
                            @can('toggleActive', $activityCategory)
                                @if ($activityCategory->active)
                                    <form method="POST"
                                        action="{{ route('configuracoes.activity-categories.destroy', $activityCategory) }}"
                                        class="flex-1">

                                        @csrf
                                        @method('DELETE')

                                        <x-buttons.danger type="submit" class="w-full justify-center">
                                            Inativar
                                        </x-buttons.danger>

                                    </form>
                                @else
                                    <form method="POST"
                                        action="{{ route('configuracoes.activity-categories.activate', $activityCategory) }}"
                                        class="flex-1">

                                        @csrf
                                        @method('PATCH')

                                        <x-buttons.primary type="submit" class="w-full justify-center">
                                            Ativar
                                        </x-buttons.primary>

                                    </form>
                                @endif
                            @endcan

                        </div>

                    </div>

                @empty

                    <div class="px-4 py-8 text-center text-xs text-slate-500">
                        Nenhuma categoria de atividade cadastrada.
                    </div>

                @endforelse

            </div>


            {{-- --------------------------------------------------------- --}}
            {{-- VIEW DESKTOP (TABLE-BASED)                               --}}
            {{-- --------------------------------------------------------- --}}

            <div class="hidden md:block overflow-x-auto">

                <table class="w-full text-left border-collapse">

                    <thead class="border-b border-slate-200 bg-slate-50">

                        <tr>

                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Nome
                            </th>

                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Status
                            </th>

                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Ações
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">

                        @forelse ($activityCategories as $activityCategory)

                            <tr class="hover:bg-slate-50">

                                {{-- Nome --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">
                                    {{ $activityCategory->name }}
                                </td>

                                {{-- Status --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    @if ($activityCategory->active)
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                            Ativa
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                            Inativa
                                        </span>
                                    @endif

                                </td>

                                {{-- Ações --}}
                                <td class="whitespace-nowrap px-6 py-4 text-right">

                                    <div class="flex items-center justify-end gap-2">

                                        {{-- Editar --}}
                                        @can('update', $activityCategory)
                                            <x-buttons.secondary :href="route(
                                                'configuracoes.activity-categories.edit',
                                                $activityCategory,
                                            )">
                                                Editar
                                            </x-buttons.secondary>
                                        @endcan

                                        {{-- Ativar / Inativar --}}
                                        @can('toggleActive', $activityCategory)
                                            @if ($activityCategory->active)
                                                <form method="POST"
                                                    action="{{ route('configuracoes.activity-categories.destroy', $activityCategory) }}"
                                                    class="inline-block">

                                                    @csrf
                                                    @method('DELETE')

                                                    <x-buttons.danger type="submit">
                                                        Inativar
                                                    </x-buttons.danger>

                                                </form>
                                            @else
                                                <form method="POST"
                                                    action="{{ route('configuracoes.activity-categories.activate', $activityCategory) }}"
                                                    class="inline-block">

                                                    @csrf
                                                    @method('PATCH')

                                                    <x-buttons.primary type="submit">
                                                        Ativar
                                                    </x-buttons.primary>

                                                </form>
                                            @endif
                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="3" class="px-6 py-12 text-center text-sm text-slate-500">
                                    Nenhuma categoria de atividade cadastrada.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </x-cards.card>

    </div>

@endsection
