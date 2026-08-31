@extends('layout.app')

@section('title', 'Novo Perfil de Preventiva')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- CABEÇALHO                                                 --}}
        {{-- ========================================================= --}}

        <x-layout.page-header
            title="Novo Perfil de Preventiva"
            description="Configure o tipo de preventiva e as filiais que participarão deste perfil."
        >
            <x-slot:breadcrumb>
                Dashboard / Configurações / Perfis de Preventiva / Novo
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

            <x-alerts.error title="Não foi possível cadastrar o perfil">

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
        {{-- FORMULÁRIO                                                --}}
        {{-- ========================================================= --}}

        <form
            id="preventive-profile-form"
            action="{{ route('configuracoes.perfis-preventivas.store') }}"
            method="POST"
            class="space-y-6"
            data-eligible-branches-url="{{ route(
                'configuracoes.perfis-preventivas.filiais.eligible',
                ['preventiveType' => '__TYPE__']
            ) }}"
        >

            @csrf

            @include(
                'configurations.preventive-profiles.partials.forms.perfil',
                [
                    'mode' => 'create',
                    'preventiveProfile' => null,
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
                    Criar Perfil
                </x-buttons.primary>

            </div>

        </form>

    </div>

@endsection


{{-- ============================================================= --}}
{{-- JAVASCRIPT                                                     --}}
{{-- ============================================================= --}}

@vite('resources/js/preventive-profiles/create.js')
