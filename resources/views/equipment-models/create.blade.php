@extends('layout.app')

@section('title', 'Novo Modelo de Equipamento')

@section('content')

    <div class="space-y-6">

        {{-- PAGE HEADER --}}
        <x-layout.page-header
            title="Novo Modelo"
            description="Cadastro de um novo modelo de equipamento."
        >
            <x-slot:breadcrumb>
                Dashboard / Equipamentos / Modelos / Novo
            </x-slot:breadcrumb>
        </x-layout.page-header>

        {{-- FORMULÁRIO --}}
        <form
            action="{{ route('modelos-equipamentos.store') }}"
            method="POST"
        >
            @csrf

            @include('equipment-models._form', [
                'mode' => 'create',
            ])

            {{-- BARRAS DE BOTÕES RESPONSIVA --}}
            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <x-buttons.secondary
                    :href="route('modelos-equipamentos.index')"
                    class="w-full justify-center sm:w-auto"
                >
                    Cancelar
                </x-buttons.secondary>

                <x-buttons.primary
                    type="submit"
                    class="w-full justify-center sm:w-auto"
                >
                    Salvar
                </x-buttons.primary>

            </div>

        </form>

    </div>

@endsection
