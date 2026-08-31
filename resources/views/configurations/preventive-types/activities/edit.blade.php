@extends('layout.app')

@section('title', 'Editar Atividade')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- CABEÇALHO                                                 --}}
        {{-- ========================================================= --}}

        <x-layout.page-header
            title="Editar Atividade"
            description="Atualize os dados da atividade."
        >
            <x-slot:breadcrumb>
                Dashboard / Configurações / Tipos de Preventiva / Atividades / Editar
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
                'configuracoes.tipos-preventivas.activities.update',
                [
                    'preventiveType' => $preventiveType,
                    'activity' => $activity,
                ]
            ) }}"
        >

            @csrf
            @method('PUT')

            @include(
                'configurations.preventive-types.activities._form',
                [
                    'mode' => 'edit',
                    'preventiveType' => $preventiveType,
                    'activity' => $activity,
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
                    Salvar Alterações
                </x-buttons.primary>

            </div>

        </form>

    </div>

@endsection
