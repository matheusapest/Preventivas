@extends('layout.app')

@section('title', 'Editar Modelo de Equipamento')

@section('content')

    <div class="space-y-6">

        {{-- PAGE HEADER --}}
        <x-layout.page-header
            title="Editar Modelo"
            description="Atualização do modelo de equipamento."
        >
            <x-slot:breadcrumb>
                Dashboard / Equipamentos / Modelos / Editar
            </x-slot:breadcrumb>
        </x-layout.page-header>

        {{-- FORMULÁRIO --}}
        <form
            action="{{ route('modelos-equipamentos.update', $equipmentModel) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            @include('equipment-models._form', [
                'mode' => 'edit',
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
                    Atualizar
                </x-buttons.primary>

            </div>

        </form>

    </div>

@endsection
