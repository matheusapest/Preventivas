@extends('layout.app')

@section('title', 'Editar Categoria')

@section('content')

    <div class="space-y-6">

        {{-- PAGE HEADER --}}
        <x-layout.page-header
            title="Editar Categoria"
            description="Atualização do cadastro da categoria."
        >
            <x-slot:breadcrumb>
                Dashboard / Equipamentos / Categorias / Editar
            </x-slot:breadcrumb>
        </x-layout.page-header>

        {{-- FORMULÁRIO --}}
        <form action="{{ route('categorias.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            @include('categories._form', [
                'mode' => 'edit',
            ])

            {{-- BARRAS DE BOTÕES RESPONSIVA --}}
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
                    Atualizar
                </x-buttons.primary>

            </div>

        </form>

    </div>

@endsection
