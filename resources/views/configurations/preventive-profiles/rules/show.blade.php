@extends('layout.app')

@section('title', 'Visualizar Regra de Preventiva')

@section('content')

    <div class="space-y-6">

        {{-- ================================================================ --}}
        {{-- CABEÇALHO DE PÁGINA                                              --}}
        {{-- ================================================================ --}}

        <x-layout.page-header title="Detalhes da Regra"
            description="Visualização da configuração da regra do perfil de preventiva.">

            <x-slot:breadcrumb>
                Dashboard / Configurações / Perfis de Preventiva / {{ $preventiveProfile->name }} / Regras / Visualizar
            </x-slot:breadcrumb>

            <x-slot:actions>

                <div class="grid grid-cols-2 gap-2 sm:flex sm:items-center">

                    <x-buttons.secondary :href="route('configuracoes.perfis-preventivas.regras.index', $preventiveProfile)" class="w-full justify-center sm:w-auto">
                        Voltar
                    </x-buttons.secondary>

                    @can('update', $preventiveProfile)
                        <x-buttons.primary :href="route('configuracoes.perfis-preventivas.regras.edit', [$preventiveProfile, $rule])" class="w-full justify-center sm:w-auto">
                            Editar Regra
                        </x-buttons.primary>
                    @endcan

                </div>

            </x-slot:actions>

        </x-layout.page-header>

        {{-- MENSAGEM DE SUCESSO --}}
        @if (session('success'))
            <x-alerts.success title="Sucesso!">
                {{ session('success') }}
            </x-alerts.success>
        @endif

        {{-- MENSAGEM DE ERRO --}}
        @if (session('error'))
            <x-alerts.error title="Não foi possível concluir a operação">
                {{ session('error') }}
            </x-alerts.error>
        @endif

        {{-- ERROS DE VALIDAÇÃO --}}
        @if ($errors->any())
            <x-alerts.error title="Ops! Ocorreu um problema">
                <ul class="mt-1 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </x-alerts.error>
        @endif


        {{-- ================================================================ --}}
        {{-- INFORMAÇÕES GERAIS DA REGRA                                      --}}
        {{-- ================================================================ --}}

        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

                <h2 class="text-base font-semibold text-slate-900">
                    Informações da regra
                </h2>

            </div>

            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 sm:p-6 lg:grid-cols-4">

                <div>

                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Perfil de Preventiva
                    </span>

                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $preventiveProfile->name }}
                    </p>

                </div>

                <div>

                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Filial
                    </span>

                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $rule->preventiveProfileBranch->branch->name ?? 'Filial não identificada' }}
                    </p>

                </div>

                <div>

                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Tipo da Regra
                    </span>

                    <div class="mt-1">

                        @if (($rule->rule_type->value ?? $rule->rule_type) === 'all')
                            <span
                                class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700">
                                {{ method_exists($rule->rule_type, 'label') ? $rule->rule_type->label() : 'Padrão (ALL)' }}
                            </span>
                        @else
                            <span
                                class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">
                                {{ method_exists($rule->rule_type, 'label') ? $rule->rule_type->label() : 'Específica' }}
                            </span>
                        @endif

                    </div>

                </div>

                <div>

                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Identificador
                    </span>

                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        #{{ $rule->id }}
                    </p>

                </div>

            </div>

        </x-cards.card>


        {{-- ================================================================ --}}
        {{-- ATIVIDADES DA REGRA ALL                                          --}}
        {{-- ================================================================ --}}

        @php
            $allRule = $rule->preventiveProfileBranch?->rules?->first(
                fn($item) => ($item->rule_type->value ?? $item->rule_type) === 'all',
            );
        @endphp

        @if ($allRule)

            <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

                    <h2 class="text-base font-semibold text-slate-900">
                        Atividades da regra padrão (Default)
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
                        Atividades aplicadas por padrão a todas as unidades elegíveis desta filial.
                    </p>

                </div>

                @if ($allRule->activities->isNotEmpty())

                    {{-- MOBILE --}}
                    <div class="divide-y divide-slate-200 sm:hidden">

                        @foreach ($allRule->activities as $index => $ruleActivity)
                            <div class="p-4 flex items-center justify-between gap-3">

                                <span class="text-xs font-semibold text-slate-400">
                                    #{{ $index + 1 }}
                                </span>

                                <span class="text-sm font-medium text-slate-900 text-right">
                                    {{ $ruleActivity->activity->name ?? 'Atividade não identificada' }}
                                </span>

                            </div>
                        @endforeach

                    </div>

                    {{-- DESKTOP --}}
                    <div class="hidden sm:block overflow-x-auto">

                        <table class="w-full text-left border-collapse">

                            <thead class="border-b border-slate-200 bg-slate-50">

                                <tr>

                                    <th scope="col"
                                        class="w-16 px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                        #
                                    </th>

                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                        Atividade
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-slate-200 bg-white">

                                @foreach ($allRule->activities as $index => $ruleActivity)
                                    <tr class="hover:bg-slate-50">

                                        <td class="whitespace-nowrap px-6 py-3.5 text-sm font-medium text-slate-400">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-3.5 text-sm font-medium text-slate-900">
                                            {{ $ruleActivity->activity->name ?? 'Atividade não identificada' }}
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>
                @else
                    <div class="p-6 text-center text-xs text-slate-500 sm:text-sm">
                        Nenhuma atividade configurada para a regra padrão.
                    </div>

                @endif

            </x-cards.card>

        @endif


        {{-- ================================================================ --}}
        {{-- REGRAS ESPECÍFICAS                                               --}}
        {{-- ================================================================ --}}

        @php
            $specificRules =
                $rule->preventiveProfileBranch?->rules?->filter(
                    fn($item) => ($item->rule_type->value ?? $item->rule_type) === 'specific',
                ) ?? collect();
        @endphp

        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

                <h2 class="text-base font-semibold text-slate-900">
                    Exceções específicas
                </h2>

                <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
                    Regras direcionadas para unidades operacionais específicas desta filial.
                </p>

            </div>

            @if ($specificRules->isNotEmpty())

                <div class="space-y-4 p-4 sm:p-6">

                    @foreach ($specificRules as $specificRule)
                        <div class="rounded-lg border border-slate-200 bg-slate-50/50 p-4">

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                                <div class="space-y-3 min-w-0 flex-1">

                                    {{-- Unidades --}}
                                    <div>

                                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Unidades Afetadas ({{ $specificRule->units->count() }})
                                        </span>

                                        <div class="flex flex-wrap gap-1.5 pt-1">

                                            @forelse ($specificRule->units as $ruleUnit)
                                                <span
                                                    class="inline-flex max-w-full items-center rounded-md bg-white px-2.5 py-1 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-200">
                                                    <span class="truncate">
                                                        {{ $ruleUnit->operationalUnit->identifier ?? ($ruleUnit->operationalUnit->name ?? 'Unidade N/I') }}
                                                    </span>
                                                </span>

                                            @empty

                                                <span class="text-xs italic text-slate-400">
                                                    Nenhuma unidade associada.
                                                </span>
                                            @endforelse

                                        </div>

                                    </div>

                                    {{-- Atividades --}}
                                    <div>

                                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Atividades ({{ $specificRule->activities->count() }})
                                        </span>

                                        <div class="flex flex-wrap gap-1.5 pt-1">

                                            @forelse ($specificRule->activities as $ruleActivity)
                                                <span
                                                    class="inline-flex max-w-full items-center rounded-md bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                                                    <span class="truncate">
                                                        {{ $ruleActivity->activity->name ?? 'Atividade N/I' }}
                                                    </span>
                                                </span>

                                            @empty

                                                <span class="text-xs italic text-slate-400">
                                                    Nenhuma atividade configurada.
                                                </span>
                                            @endforelse

                                        </div>

                                    </div>

                                </div>

                                {{-- Ações --}}
                                @can('update', $preventiveProfile)
                                    <div class="shrink-0 pt-2 sm:pt-0">

                                        {{-- EDITAR --}}
                                        <button type="button" data-specific-rule-edit
                                            data-specific-rule-id="{{ $specificRule->id }}"
                                            data-update-url="{{ route('configuracoes.perfis-preventivas.regras.specific.update-from-show', [
                                                'preventiveProfile' => $preventiveProfile,
                                                'rule' => $rule,
                                                'specificRule' => $specificRule,
                                            ]) }}"
                                            data-operational-unit-id="{{ $specificRule->units->first()?->operational_unit_id }}"
                                            data-operational-unit-label="{{ $specificRule->units->first()?->operationalUnit?->identifier }}"
                                            data-activity-ids='@json($specificRule->activities->pluck('activity_id')->values())'
                                            class="inline-flex shrink-0 items-center justify-center rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            Editar Exceção
                                        </button>
                                    </div>
                                @endcan

                            </div>

                        </div>
                    @endforeach

                </div>
            @else
                <div class="p-6 text-center sm:p-8">

                    <p class="text-xs font-medium text-slate-600 sm:text-sm">
                        Nenhuma regra específica configurada.
                    </p>

                    <p class="mt-0.5 text-xs text-slate-500">
                        Todas as unidades operacionais desta filial utilizam o padrão ALL.
                    </p>

                </div>

            @endif

        </x-cards.card>

    </div>

@endsection

{{-- MODAL REGRA ESPECÍFICA --}}
@include('configurations.preventive-profiles.rules.partials._specific-modal', [
    'activities' => $activities,
    'operationalUnits' => $operationalUnits,
    'preventiveProfile' => $preventiveProfile,
    'rule' => $rule,
    'errors' => $errors,
])

@vite('resources/js/preventive-profiles/rules/edit.js')
