@extends('layout.app')

@section('title', 'Editar Número de Filial')

@section('content')

<x-layout.page-header
    title="Editar Número de Filial"
    description="Atualização do número da filial."
>

    <x-slot:breadcrumb>

        Dashboard /
        Cadastros /
        Filiais /
        Números de Filiais /
        Editar

    </x-slot:breadcrumb>

</x-layout.page-header>

<form
    action="{{ route('codigos-filiais.update', $branchCode) }}"
    method="POST"
>

    @csrf
    @method('PUT')

    @include('branch-codes._form', [
        'mode' => 'edit',
    ])

    <div class="mt-6 flex justify-end gap-3">

        <x-buttons.secondary
            :href="route('codigos-filiais.index')"
        >
            Cancelar
        </x-buttons.secondary>

        <x-buttons.primary type="submit">
            Atualizar
        </x-buttons.primary>

    </div>

</form>

@endsection
