@extends('layout.app')

@section('title', 'Novo Número de Filial')

@section('content')

<x-layout.page-header
    title="Novo Número de Filial"
    description="Cadastro de um novo número de filial."
>

    <x-slot:breadcrumb>

        Dashboard /
        Cadastros /
        Filiais /
        Números de Filiais /
        Novo

    </x-slot:breadcrumb>

</x-layout.page-header>

<form
    action="{{ route('codigos-filiais.store') }}"
    method="POST"
>

    @csrf

    @include('branch-codes._form', [
        'mode' => 'create',
    ])

    <div class="mt-6 flex justify-end gap-3">

        <x-buttons.secondary
            :href="route('codigos-filiais.index')"
        >
            Cancelar
        </x-buttons.secondary>

        <x-buttons.primary type="submit">
            Salvar
        </x-buttons.primary>

    </div>

</form>

@endsection
