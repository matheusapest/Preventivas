@extends('layout.app')

@section('title', 'Nova Filial')

@section('content')

<x-layout.page-header
    title="Nova Filial"
    description="Cadastro de uma nova filial."
>

    <x-slot:breadcrumb>

        Dashboard /
        Cadastros /
        Filiais /
        Novo

    </x-slot:breadcrumb>

</x-layout.page-header>

<form
    action="{{ route('filiais.store') }}"
    method="POST"
>

    @csrf

    @include('branches._form', [
        'mode' => 'create',
    ])

    <div class="mt-6 flex justify-end gap-3">

        <x-buttons.secondary
            :href="route('filiais.index')"
        >
            Cancelar
        </x-buttons.secondary>

        <x-buttons.primary
            type="submit"
        >
            Salvar
        </x-buttons.primary>

    </div>

</form>

@endsection
