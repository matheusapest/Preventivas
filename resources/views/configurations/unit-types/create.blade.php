@extends('layout.app')

@section('title', 'Novo Tipo de Unidade')

@section('content')

    <div class="space-y-6">

        <x-layout.page-header
            title="Novo Tipo de Unidade"
            description="Cadastre um novo tipo de unidade operacional."
        >
            <x-slot:breadcrumb>
                Dashboard / Configurações / Tipos de Unidade / Novo
            </x-slot:breadcrumb>
        </x-layout.page-header>

        <form
            action="{{ route('configuracoes.tipos-unidade.store') }}"
            method="POST"
        >
            @csrf

            @include('configurations.unit-types._form', [
                'mode' => 'create',
                'unitType' => null,
                'branches' => $branches,
            ])

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <x-buttons.secondary
                    :href="route('configuracoes.tipos-unidade.index')"
                    class="w-full justify-center sm:w-auto"
                >
                    Cancelar
                </x-buttons.secondary>

                <x-buttons.primary
                    type="submit"
                    class="w-full justify-center sm:w-auto"
                >
                    Salvar Tipo de Unidade
                </x-buttons.primary>

            </div>

        </form>

    </div>

@endsection
