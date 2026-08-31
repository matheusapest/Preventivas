@extends('layout.app')

@section('title', 'Novo Fabricante')

@section('content')

<x-layout.page-header
    title="Novo Fabricante"
    description="Cadastro de um novo fabricante."
>

    <x-slot:breadcrumb>

        Dashboard /
        Equipamentos/
        Fabricantes/
        Novo Fabricante

    </x-slot:breadcrumb>

</x-layout.page-header>

<form
    action="{{ route('fabricantes.store') }}"
    method="POST"
>

    @csrf

    @include('manufacturers._form', [
        'mode' => 'create',
    ])

    <div class="mt-6 flex justify-end gap-3">

        <x-buttons.secondary
            :href="route('fabricantes.index')"
        >
            Cancelar
        </x-buttons.secondary>

        <x-buttons.primary type="submit">
            Salvar
        </x-buttons.primary>

    </div>

</form>

@endsection
