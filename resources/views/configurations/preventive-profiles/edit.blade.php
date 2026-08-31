@extends('layout.app')

@section('title', 'Editar Perfil de Preventiva')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- CABEÇALHO                                                 --}}
        {{-- ========================================================= --}}

        <x-layout.page-header
            title="Editar Perfil de Preventiva"
            description="Atualize os dados e as filiais participantes deste perfil."
        >
            <x-slot:breadcrumb>
                Dashboard / Configurações / Perfis de Preventiva / Editar
            </x-slot:breadcrumb>

            <x-slot:actions>
                <div class="flex items-center">
                    <x-buttons.secondary
                        :href="route('configuracoes.perfis-preventivas.index')"
                        class="w-full justify-center sm:w-auto"
                    >
                        Voltar
                    </x-buttons.secondary>
                </div>
            </x-slot:actions>
        </x-layout.page-header>


        {{-- ========================================================= --}}
        {{-- ERROS DE VALIDAÇÃO                                        --}}
        {{-- ========================================================= --}}

        @if ($errors->any())

            <x-alerts.error title="Não foi possível atualizar o perfil">

                <div class="space-y-2">

                    <p>
                        Verifique os dados informados antes de continuar.
                    </p>

                    <ul class="list-inside list-disc space-y-1">

                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach

                    </ul>

                </div>

            </x-alerts.error>

        @endif


        {{-- ========================================================= --}}
        {{-- FORMULÁRIO                                                 --}}
        {{-- ========================================================= --}}

        <form
            id="preventive-profile-form"
            action="{{ route('configuracoes.perfis-preventivas.update', $preventiveProfile) }}"
            method="POST"
            class="space-y-6"
            data-eligible-branches-url="{{ route(
                'configuracoes.perfis-preventivas.filiais.eligible',
                ['preventiveType' => '__TYPE__']
            ) }}"
            data-selected-branches="{{ json_encode(
                old(
                    'branch_ids',
                    $preventiveProfile->branches
                        ->pluck('branch_id')
                        ->map(fn ($id) => (int) $id)
                        ->values()
                        ->all()
                )
            ) }}"
        >

            @csrf

            @method('PUT')

            @include(
                'configurations.preventive-profiles.partials.forms.perfil',
                [
                    'mode' => 'edit',
                    'preventiveProfile' => $preventiveProfile,
                    'preventiveTypes' => $preventiveTypes,
                    'branches' => $branches,
                ]
            )


            {{-- ===================================================== --}}
            {{-- AÇÕES                                                   --}}
            {{-- ===================================================== --}}

            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:items-center sm:justify-end">

                <x-buttons.secondary
                    :href="route('configuracoes.perfis-preventivas.index')"
                    class="w-full justify-center sm:w-auto"
                >
                    Cancelar
                </x-buttons.secondary>

                <x-buttons.primary
                    type="submit"
                    class="w-full justify-center sm:w-auto"
                >
                    Salvar Alterações
                </x-buttons.primary>

            </div>

        </form>

    </div>


    {{-- ============================================================= --}}
    {{-- JAVASCRIPT                                                     --}}
    {{-- ============================================================= --}}



@endsection

@vite('resources/js/preventive-profiles/edit.js')
