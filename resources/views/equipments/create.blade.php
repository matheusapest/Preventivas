@extends('layout.app')

@section('title', 'Adicionar Novo Equipamento')

@section('content')

<x-layout.page-header
    title="Novo Equipamento"
    description="Cadastro de um novo equipamento."
>
    <x-slot:breadcrumb>

        Dashboard /
        Operação /
        Equipamentos /
        Cadastrar Novo Equipamento

    </x-slot:breadcrumb>

</x-layout.page-header>

<form
    action="{{ route('equipamentos.store') }}"
    method="POST"
>

    @csrf

    @include('equipments._form', [
        'mode' => 'create',
    ])

    <div class="mt-6 flex justify-end gap-3">

        <x-buttons.secondary
            :href="route('equipamentos.index')"
        >
            Cancelar
        </x-buttons.secondary>

        <x-buttons.primary type="submit">
            Salvar
        </x-buttons.primary>

    </div>

</form>

@endsection
