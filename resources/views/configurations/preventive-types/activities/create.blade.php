@extends('layout.app')

@section('title', 'Nova Atividade')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- CABEÇALHO                                                 --}}
        {{-- ========================================================= --}}

        <x-layout.page-header
            title="Nova Atividade"
            description="Cadastre uma nova atividade para este tipo de preventiva."
        >

            <x-slot:breadcrumb>
                Dashboard / Configurações / Tipos de Preventiva / Atividades / Nova
            </x-slot:breadcrumb>

            <x-slot:actions>

                <x-buttons.secondary
                    :href="route(
                        'configuracoes.tipos-preventivas.activities.index',
                        $preventiveType
                    )"
                >
                    Voltar
                </x-buttons.secondary>

            </x-slot:actions>

        </x-layout.page-header>


        {{-- ========================================================= --}}
        {{-- FORMULÁRIO                                                 --}}
        {{-- ========================================================= --}}

        <form
            method="POST"
            action="{{ route(
                'configuracoes.tipos-preventivas.activities.store',
                $preventiveType
            ) }}"
        >

            @csrf

            @include(
                'configurations.preventive-types.activities._form',
                [
                    'mode' => 'create',
                    'preventiveType' => $preventiveType,
                    'activityTypes' => $activityTypes,
                ]
            )


            {{-- ===================================================== --}}
            {{-- AÇÕES                                                   --}}
            {{-- ===================================================== --}}

            <div class="mt-6 flex items-center justify-end gap-3">

                <x-buttons.secondary
                    :href="route(
                        'configuracoes.tipos-preventivas.activities.index',
                        $preventiveType
                    )"
                >
                    Cancelar
                </x-buttons.secondary>

                <x-buttons.primary type="submit">
                    Cadastrar Atividade
                </x-buttons.primary>

            </div>

        </form>

    </div>

@endsection
