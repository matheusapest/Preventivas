@extends('layout.app')

@section('title', 'Tipos de Preventiva')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER                                               --}}
        {{-- ========================================================= --}}

        <x-layout.page-header title="Tipos de Preventiva"
            description="Gerencie os tipos de preventiva cadastrados no sistema.">

            <x-slot:breadcrumb>
                Dashboard / Configurações / Tipos de Preventiva
            </x-slot:breadcrumb>

            <x-slot:actions>

                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:items-center">

                    {{-- Categorias de Atividades --}}

                    {{-- Categorias de Atividades --}}
                    <x-buttons.secondary :href="route('configuracoes.activity-categories.index')" class="w-full justify-center sm:w-auto">
                        Categorias Atividades
                    </x-buttons.secondary>

                    {{-- Novo Tipo de Preventiva --}}

                    @can('create', App\Models\PreventiveType::class)
                        <x-buttons.primary :href="route('configuracoes.tipos-preventivas.create')" class="w-full justify-center sm:w-auto">
                            Novo Tipo de Preventiva
                        </x-buttons.primary>
                    @endcan

                </div>

            </x-slot:actions>

        </x-layout.page-header>


        {{-- ========================================================= --}}
        {{-- MENSAGEM DE SUCESSO                                       --}}
        {{-- ========================================================= --}}

        @if (session('success'))
            <x-alerts.success>
                {{ session('success') }}
            </x-alerts.success>
        @endif


        {{-- ========================================================= --}}
        {{-- LISTAGEM (CARDS MOBILE / TABELA DESKTOP)                  --}}
        {{-- ========================================================= --}}

        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- --------------------------------------------------------- --}}
            {{-- VIEW MOBILE (CARD-BASED)                                 --}}
            {{-- --------------------------------------------------------- --}}

            <div class="block divide-y divide-slate-200 md:hidden">

                @forelse ($preventiveTypes as $type)

                    <div class="p-4 space-y-3">

                        {{-- Nome e Status --}}
                        <div class="flex items-start justify-between gap-3">

                            <div>

                                <span class="text-xs text-slate-500 uppercase font-semibold tracking-wider">
                                    Tipo de Preventiva
                                </span>

                                <h3 class="text-sm font-semibold text-slate-900 mt-0.5">
                                    {{ $type->name }}
                                </h3>

                            </div>

                            @if ($type->active)
                                <span
                                    class="inline-flex shrink-0 items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                    Ativa
                                </span>
                            @else
                                <span
                                    class="inline-flex shrink-0 items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                    Inativa
                                </span>
                            @endif

                        </div>

                        {{-- Unidade --}}
                        <div>

                            <span class="text-xs text-slate-500">
                                Unidade:
                            </span>

                            <span class="text-xs font-medium text-slate-700">
                                {{ $type->unitType?->name ?? '—' }}
                            </span>

                        </div>

                        {{-- Ações Mobile --}}
                        <div class="pt-2 flex flex-wrap items-center gap-2 border-t border-slate-100">

                            {{-- Ver Atividades --}}
                            <a href="{{ route('configuracoes.tipos-preventivas.activities.index', $type) }}"
                                class="flex-1 inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">
                                Atividades
                            </a>

                            {{-- Editar --}}
                            @can('update', $type)
                                <a href="{{ route('configuracoes.tipos-preventivas.edit', $type) }}"
                                    class="flex-1 inline-flex items-center justify-center rounded-lg border border-orange-500 bg-orange-500 px-3 py-2 text-xs font-semibold text-white transition hover:border-orange-600 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-300">
                                    Editar
                                </a>
                            @endcan

                            {{-- Ativar / Inativar --}}
                            @can('toggleActive', $type)
                                @if ($type->active)
                                    <form action="{{ route('configuracoes.tipos-preventivas.destroy', $type) }}" method="POST"
                                        class="flex-1">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center rounded-lg border border-red-500 bg-red-500 px-3 py-2 text-xs font-semibold text-white transition hover:border-red-600 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                                            Inativar
                                        </button>

                                    </form>
                                @else
                                    <form action="{{ route('configuracoes.tipos-preventivas.activate', $type) }}" method="POST"
                                        class="flex-1">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center rounded-lg border border-emerald-500 bg-emerald-500 px-3 py-2 text-xs font-semibold text-white transition hover:border-emerald-600 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                                            Ativar
                                        </button>

                                    </form>
                                @endif
                            @endcan

                        </div>

                    </div>

                @empty

                    <div class="px-4 py-8 text-center text-xs text-slate-500">
                        Nenhum tipo de preventiva cadastrado.
                    </div>

                @endforelse

            </div>


            {{-- --------------------------------------------------------- --}}
            {{-- VIEW DESKTOP (TABLE-BASED)                               --}}
            {{-- --------------------------------------------------------- --}}

            <div class="hidden md:block overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Nome
                            </th>

                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Unidade
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

                    <tbody class="divide-y divide-slate-100 bg-white">

                        @forelse ($preventiveTypes as $type)

                            <tr class="hover:bg-slate-50">

                                {{-- Nome --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <div class="text-sm font-medium text-slate-900">
                                        {{ $type->name }}
                                    </div>

                                </td>

                                {{-- Unidade --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <div class="text-sm text-slate-700">
                                        {{ $type->unitType?->name ?? '—' }}
                                    </div>

                                </td>

                                {{-- Status --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    @if ($type->active)
                                        <span
                                            class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                            Ativa
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                            Inativa
                                        </span>
                                    @endif

                                </td>

                                {{-- Ações --}}
                                <td class="whitespace-nowrap px-6 py-4 text-right">

                                    <div class="flex items-center justify-end gap-2">

                                        {{-- Ver Atividades --}}
                                        <a href="{{ route('configuracoes.tipos-preventivas.activities.index', $type) }}"
                                            class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">
                                            Atividades
                                        </a>

                                        {{-- Editar --}}
                                        @can('update', $type)
                                            <a href="{{ route('configuracoes.tipos-preventivas.edit', $type) }}"
                                                class="inline-flex items-center justify-center rounded-lg border border-orange-500 bg-orange-500 px-3.5 py-2 text-sm font-semibold text-white transition hover:border-orange-600 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-300">
                                                Editar
                                            </a>
                                        @endcan

                                        {{-- Ativar / Inativar --}}
                                        @can('toggleActive', $type)
                                            @if ($type->active)
                                                <form action="{{ route('configuracoes.tipos-preventivas.destroy', $type) }}"
                                                    method="POST" class="inline-block">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center rounded-lg border border-red-500 bg-red-500 px-3.5 py-2 text-sm font-semibold text-white transition hover:border-red-600 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                                                        Inativar
                                                    </button>

                                                </form>
                                            @else
                                                <form action="{{ route('configuracoes.tipos-preventivas.activate', $type) }}"
                                                    method="POST" class="inline-block">

                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center rounded-lg border border-emerald-500 bg-emerald-500 px-3.5 py-2 text-sm font-semibold text-white transition hover:border-emerald-600 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                                                        Ativar
                                                    </button>

                                                </form>
                                            @endif
                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">
                                    Nenhum tipo de preventiva cadastrado.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </x-cards.card>

    </div>

@endsection
