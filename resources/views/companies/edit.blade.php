@extends('layout.app')

@section('title', 'Editar Empresa')

@section('content')

    <div class="space-y-6">

        <x-layout.page-header
            title="Editar Empresa"
            description="Atualize as informações da empresa."
        >
            <x-slot:breadcrumb>
                Dashboard / Cadastros / Empresas / Editar
            </x-slot:breadcrumb>

            <x-slot:actions>
                <div class="w-full sm:w-auto">
                    <x-buttons.secondary
                        :href="route('empresas.index')"
                        class="w-full justify-center sm:w-auto"
                    >
                        Voltar
                    </x-buttons.secondary>
                </div>
            </x-slot:actions>
        </x-layout.page-header>

        <form
            action="{{ route('empresas.update', $company) }}"
            method="POST"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            @include('companies._form', [
                'mode' => 'edit',
            ])

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <x-buttons.secondary
                    :href="route('empresas.index')"
                    class="w-full justify-center sm:w-auto"
                >
                    Cancelar
                </x-buttons.secondary>

                <x-buttons.primary
                    type="submit"
                    class="w-full justify-center sm:w-auto"
                >
                    Atualizar Empresa
                </x-buttons.primary>

            </div>

        </form>

    </div>

@endsection
