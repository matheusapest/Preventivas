@extends('layout.app')

@section('title', 'Editar Regras da Filial')

@section('content')

    @php
        /**
         * Identifica explicitamente se o último POST
         * veio do formulário de regra específica.
         *
         * Não devemos usar activity_ids para identificar
         * o formulário, pois a regra Todos também possui
         * esse campo.
         */
        $specificFormHasErrors =
            old('_specific_form') === '1' &&
            ($errors->has('operational_unit_id') ||
                $errors->has('activity_ids') ||
                $errors->has('activity_ids.*') ||
                session()->has('error'));
    @endphp
    <div class="space-y-6" data-specific-modal-error="{{ $specificFormHasErrors ? 'true' : 'false' }}">

        {{-- ========================================================= --}}
        {{-- CABEÇALHO                                                 --}}
        {{-- ========================================================= --}}

        <x-layout.page-header title="Editar Regras da Filial" :description="$preventiveProfile->name">
            <x-slot:breadcrumb>
                Dashboard / Configurações / Perfis de Preventiva /
                {{ $preventiveProfile->name }} / Regras / Editar
            </x-slot:breadcrumb>

            <x-slot:actions>
                <x-buttons.secondary :href="route('configuracoes.perfis-preventivas.regras.index', $preventiveProfile)">
                    Voltar
                </x-buttons.secondary>
            </x-slot:actions>
        </x-layout.page-header>


        {{-- ========================================================= --}}
        {{-- MENSAGEM DE SUCESSO                                       --}}
        {{-- ========================================================= --}}

        @if (session('success'))
            <x-alerts.success title="Sucesso!">
                {{ session('success') }}
            </x-alerts.success>
        @endif

        {{-- ========================================================= --}}
        {{-- MENSAGEM DE ERRO                                          --}}
        {{-- ========================================================= --}}

        @if (session('error') && !$errors->has('operational_unit_id'))
            <x-alerts.error title="Não foi possível concluir a operação">
                {{ session('error') }}
            </x-alerts.error>
        @endif

        {{-- ========================================================= --}}
        {{-- ERROS GERAIS                                              --}}
        {{-- ========================================================= --}}

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


        {{-- ========================================================= --}}
        {{-- FILIAL + REGRA ALL                                        --}}
        {{-- ========================================================= --}}

        <form id="all-rule-form" method="POST"
            action="{{ route('configuracoes.perfis-preventivas.regras.update', [$preventiveProfile, $rule]) }}">

            @csrf
            @method('PUT')

            @include('configurations.preventive-profiles.rules.partials._branch', [
                'profileBranch' => $profileBranch,
                'rule' => $rule,
            ])

            @include('configurations.preventive-profiles.rules.partials._all-rule', [
                'activities' => $activities,
                'selectedActivityIds' => $selectedActivityIds,
                'errors' => $errors,
            ])

        </form>


        {{-- ========================================================= --}}
        {{-- REGRAS ESPECÍFICAS                                        --}}
        {{-- ========================================================= --}}

        @include('configurations.preventive-profiles.rules.partials._specific-rules', [
            'specificRules' => $specificRules,
            'operationalUnits' => $operationalUnits,
            'preventiveProfile' => $preventiveProfile,
            'rule' => $rule,
        ])


        {{-- ========================================================= --}}
        {{-- AÇÕES DA PÁGINA                                           --}}
        {{-- ========================================================= --}}

        @include('configurations.preventive-profiles.rules.partials._actions', [
            'preventiveProfile' => $preventiveProfile,
        ])


        {{-- ========================================================= --}}
        {{-- MODAL REGRA ESPECÍFICA                                    --}}
        {{-- ========================================================= --}}

        @include('configurations.preventive-profiles.rules.partials._specific-modal', [
            'activities' => $activities,
            'operationalUnits' => $operationalUnits,
            'preventiveProfile' => $preventiveProfile,
            'rule' => $rule,
            'errors' => $errors,
        ])

    </div>

@endsection

@vite('resources/js/preventive-profiles/rules/edit.js')
