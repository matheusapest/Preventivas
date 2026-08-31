@extends('layout.app')

@section('title', 'Nova Categoria')

@section('content')

    <div class="space-y-6">

        {{-- PAGE HEADER --}}

        <x-layout.page-header
            title="Nova Categoria"
            description="Cadastro de uma nova categoria do sistema."
        >
            <x-slot:breadcrumb>
                Dashboard / Equipamentos / Categorias / Nova Categoria
            </x-slot:breadcrumb>
        </x-layout.page-header>

        {{-- FORMULÁRIO --}}

        <form action="{{ route('categorias.store') }}" method="POST">

            @csrf

            @include('categories._form', [
                'mode' => 'create',
            ])

            {{-- BARRA DE BOTÕES --}}

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <x-buttons.secondary
                    :href="route('categorias.index')"
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
