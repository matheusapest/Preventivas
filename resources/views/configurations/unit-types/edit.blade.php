@extends('layout.app')

@section('title', 'Editar Tipo de Unidade')

@section('content')

    <div class="space-y-6">

        <x-layout.page-header
            title="Editar Tipo de Unidade"
            description="Altere as informações do tipo de unidade operacional."
        >
            <x-slot:breadcrumb>
                Dashboard / Configurações / Tipos de Unidade / Editar
            </x-slot:breadcrumb>
        </x-layout.page-header>

        <form
            action="{{ route('configuracoes.tipos-unidade.update', $unitType) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            @include('configurations.unit-types._form', [
                'mode' => 'edit',
                'unitType' => $unitType,
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
                    Salvar Alterações
                </x-buttons.primary>

            </div>

        </form>

    </div>

@endsection
