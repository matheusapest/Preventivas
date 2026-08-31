@extends('layout.app')

@section('title', 'Nova Empresa')

@section('content')

    <div class="space-y-6">

        {{-- PAGE HEADER RESPONSIVO --}}
        <x-layout.page-header
            title="Nova Empresa"
            description="Cadastre uma nova empresa no sistema."
        >
            <x-slot:breadcrumb>
                Dashboard / Cadastros / Empresas / Criar
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

        {{-- FORMULÁRIO --}}
        <form
            action="{{ route('empresas.store') }}"
            method="POST"
            class="space-y-6"
        >
            @csrf

            {{-- Formulário Base --}}
            @include('companies._form', [
                'mode' => 'create',
            ])

            {{-- BOTÕES DE AÇÃO INFERIORES --}}
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
                    Cadastrar Empresa
                </x-buttons.primary>
            </div>

        </form>

    </div>

@endsection
