@extends('layout.app')

@section('title', 'Nova Categoria de Atividade')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- CABEÇALHO                                                 --}}
        {{-- ========================================================= --}}

        <x-layout.page-header
            title="Nova Categoria de Atividade"
            description="Cadastre uma categoria para organizar as atividades."
        >

            <x-slot:breadcrumb>
                Dashboard / Configurações / Categorias de Atividades / Nova
            </x-slot:breadcrumb>

            <x-slot:actions>

                <div class="flex items-center">

                    <x-buttons.secondary
                        :href="route('configuracoes.activity-categories.index')"
                        class="w-full justify-center sm:w-auto"
                    >
                        Voltar
                    </x-buttons.secondary>

                </div>

            </x-slot:actions>

        </x-layout.page-header>


        {{-- ========================================================= --}}
        {{-- FORMULÁRIO                                                 --}}
        {{-- ========================================================= --}}

        <form
            method="POST"
            action="{{ route('configuracoes.activity-categories.store') }}"
            class="space-y-6"
        >

            @csrf

            @include(
                'configurations.preventive-types.activity-categories._form',
                [
                    'mode' => 'create',
                ]
            )


            {{-- ===================================================== --}}
            {{-- AÇÕES                                                   --}}
            {{-- ===================================================== --}}

            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:items-center sm:justify-end">

                <x-buttons.secondary
                    :href="route('configuracoes.activity-categories.index')"
                    class="w-full justify-center sm:w-auto"
                >
                    Cancelar
                </x-buttons.secondary>

                <x-buttons.primary
                    type="submit"
                    class="w-full justify-center sm:w-auto"
                >
                    Salvar Categoria
                </x-buttons.primary>

            </div>

        </form>

    </div>

@endsection
