@extends('layout.app')

@section('title', 'Editar Tipo de Preventiva')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER                                               --}}
        {{-- ========================================================= --}}

        <x-layout.page-header
            title="Editar Tipo de Preventiva"
            description="Atualize as informações do tipo de preventiva."
        >

            <x-slot:breadcrumb>
                Dashboard / Configurações / Tipos de Preventiva / Editar
            </x-slot:breadcrumb>

            <x-slot:actions>

                <div class="flex items-center">

                    <x-buttons.secondary
                        :href="route('configuracoes.tipos-preventivas.index')"
                        class="w-full justify-center sm:w-auto"
                    >
                        Voltar
                    </x-buttons.secondary>

                </div>

            </x-slot:actions>

        </x-layout.page-header>


        {{-- ========================================================= --}}
        {{-- FORMULÁRIO                                                --}}
        {{-- ========================================================= --}}

        <form
            action="{{ route(
                'configuracoes.tipos-preventivas.update',
                $preventiveType
            ) }}"
            method="POST"
            class="space-y-6"
        >

            @csrf
            @method('PUT')

            {{-- Partial de Campos --}}
            @include('configurations.preventive-types._form', [
                'mode' => 'edit',
            ])

            {{-- Botões do Formulário --}}
            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:items-center sm:justify-end">

                <x-buttons.secondary
                    :href="route('configuracoes.tipos-preventivas.index')"
                    class="w-full justify-center sm:w-auto"
                >
                    Cancelar
                </x-buttons.secondary>

                <x-buttons.primary
                    type="submit"
                    class="w-full justify-center sm:w-auto"
                >
                    Salvar alterações
                </x-buttons.primary>

            </div>

        </form>

    </div>

@endsection
