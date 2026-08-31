@extends('layout.app')

@section('title', 'Atividades da Preventiva')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER                                                --}}
        {{-- ========================================================= --}}

        <x-layout.page-header title="Atividades da Preventiva"
            description="Gerencie as atividades vinculadas ao tipo de preventiva.">

            <x-slot:breadcrumb>
                Dashboard / Configurações / Tipos de Preventiva / Atividades
            </x-slot:breadcrumb>

            <x-slot:actions>

                <div class="flex flex-wrap items-center gap-2">

                    {{-- Voltar --}}

                    <x-buttons.secondary :href="route('configuracoes.tipos-preventivas.index')">
                        Voltar
                    </x-buttons.secondary>

                    {{-- Nova atividade --}}

                    @can('create', App\Models\Configuration\Preventive\Activity::class)
                        <x-buttons.primary :href="route('configuracoes.tipos-preventivas.activities.create', $preventiveType)">
                            Nova Atividade
                        </x-buttons.primary>
                    @endcan

                </div>

            </x-slot:actions>

        </x-layout.page-header>
        {{-- ========================================================= --}}
        {{-- MENSAGENS                                                 --}}
        {{-- ========================================================= --}}

        @if (session('success'))
            <x-alerts.success>
                {{ session('success') }}
            </x-alerts.success>
        @endif

        @if (session('error'))
            <x-alerts.error>
                {{ session('error') }}
            </x-alerts.error>
        @endif

        {{-- ========================================================= --}}
        {{-- TIPO DE PREVENTIVA                                         --}}
        {{-- ========================================================= --}}

        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="px-4 py-4 sm:px-6">

                <div class="flex flex-col gap-1">

                    <span class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Tipo de Preventiva
                    </span>

                    <span class="text-base font-semibold text-slate-900">
                        {{ $preventiveType->name }}
                    </span>

                    @if ($preventiveType->unitType)
                        <span class="text-sm text-slate-500">
                            Unidade:
                            {{ $preventiveType->unitType->name }}
                        </span>
                    @endif

                </div>

            </div>

        </x-cards.card>



        {{-- ========================================================= --}}
        {{-- LISTAGEM                                                   --}}
        {{-- ========================================================= --}}

        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    {{-- ================================================= --}}
                    {{-- CABEÇALHO                                          --}}
                    {{-- ================================================= --}}

                    <thead class="bg-slate-50">

                        <tr>

                            {{-- Nome --}}

                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600 sm:px-6">
                                Nome
                            </th>

                            {{-- Categoria --}}

                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600 sm:px-6">
                                Categoria
                            </th>

                            {{-- Tipo --}}

                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600 sm:px-6">
                                Tipo
                            </th>

                            {{-- Descrição --}}

                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600 sm:px-6">
                                Descrição
                            </th>

                            {{-- Status --}}

                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600 sm:px-6">
                                Status
                            </th>

                            {{-- Ações --}}

                            <th scope="col"
                                class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600 sm:px-6">
                                Ações
                            </th>

                        </tr>

                    </thead>


                    {{-- ================================================= --}}
                    {{-- CORPO DA TABELA                                    --}}
                    {{-- ================================================= --}}

                    <tbody class="divide-y divide-slate-100 bg-white">

                        @forelse ($activities as $activity)

                            <tr class="hover:bg-slate-50">

                                {{-- ================================================= --}}
                                {{-- NOME                                               --}}
                                {{-- ================================================= --}}

                                <td class="whitespace-nowrap px-4 py-4 sm:px-6">

                                    <div class="text-sm font-medium text-slate-900">
                                        {{ $activity->name }}
                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- CATEGORIA                                           --}}
                                {{-- ================================================= --}}

                                <td class="whitespace-nowrap px-4 py-4 sm:px-6">

                                    <div class="text-sm text-slate-700">

                                        @if ($activity->activityCategory)
                                            {{ $activity->activityCategory->name }}
                                        @else
                                            <span class="text-slate-400">
                                                —
                                            </span>
                                        @endif

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- TIPO                                                --}}
                                {{-- ================================================= --}}

                                <td class="whitespace-nowrap px-4 py-4 sm:px-6">

                                    <div class="text-sm text-slate-700">
                                        {{ $activity->type?->label() ?? '—' }}
                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- DESCRIÇÃO                                           --}}
                                {{-- ================================================= --}}

                                <td class="max-w-md px-4 py-4 sm:px-6">

                                    <div class="truncate text-sm text-slate-600">
                                        {{ $activity->description ?: '—' }}
                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- STATUS                                              --}}
                                {{-- ================================================= --}}

                                <td class="whitespace-nowrap px-4 py-4 sm:px-6">

                                    @if ($activity->active)
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


                                {{-- ================================================= --}}
                                {{-- AÇÕES                                               --}}
                                {{-- ================================================= --}}

                                <td class="whitespace-nowrap px-4 py-4 text-right sm:px-6">

                                    <div class="flex justify-end gap-2">

                                        {{-- Visualizar --}}

                                        @can('view', $activity)
                                            <a href="{{ route('configuracoes.tipos-preventivas.activities.show', [
                                                'preventiveType' => $preventiveType,
                                                'activity' => $activity,
                                            ]) }}"
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">
                                                Visualizar
                                            </a>
                                        @endcan


                                        {{-- Editar --}}

                                        @can('update', $activity)
                                            <a href="{{ route('configuracoes.tipos-preventivas.activities.edit', [
                                                'preventiveType' => $preventiveType,
                                                'activity' => $activity,
                                            ]) }}"
                                                class="inline-flex items-center justify-center rounded-lg border border-orange-500 bg-orange-500 px-3.5 py-2 text-sm font-medium text-white transition hover:border-orange-600 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-300">
                                                Editar
                                            </a>
                                        @endcan


                                        {{-- Ativar / Inativar --}}

                                        @can('toggleActive', $activity)
                                            @if ($activity->active)
                                                <form
                                                    action="{{ route('configuracoes.tipos-preventivas.activities.destroy', [
                                                        'preventiveType' => $preventiveType,
                                                        'activity' => $activity,
                                                    ]) }}"
                                                    method="POST">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center rounded-lg border border-red-500 bg-red-500 px-3.5 py-2 text-sm font-medium text-white transition hover:border-red-600 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                                                        Inativar
                                                    </button>

                                                </form>
                                            @else
                                                <form
                                                    action="{{ route('configuracoes.tipos-preventivas.activities.activate', [
                                                        'preventiveType' => $preventiveType,
                                                        'activity' => $activity,
                                                    ]) }}"
                                                    method="POST">

                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center rounded-lg border border-emerald-500 bg-emerald-500 px-3.5 py-2 text-sm font-medium text-white transition hover:border-emerald-600 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
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

                                <td colspan="6" class="px-6 py-12 text-center">

                                    <div class="text-sm text-slate-500">
                                        Nenhuma atividade cadastrada para este tipo de preventiva.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </x-cards.card>

    </div>

@endsection
