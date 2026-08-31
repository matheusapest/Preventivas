@extends('layout.app')

@section('title', 'Editar Fabricante')

@section('content')

<x-layout.page-header
    title="Editar Fabricante"
    description="Atualização do fabricante."
>

    <x-slot:breadcrumb>

        Dashboard /
        Cadastros /
        Filiais /
        Fabricantes /
        Editar Fabricante

    </x-slot:breadcrumb>

</x-layout.page-header>

<form
    action="{{ route('fabricantes.update', $manufacturer) }}"
    method="POST"
>

    @csrf
    @method('PUT')

    @include('manufacturers._form', [
        'mode' => 'edit',
    ])

    <div class="mt-6 flex justify-end gap-3">

        <x-buttons.secondary
            :href="route('fabricantes.index')"
        >
            Cancelar
        </x-buttons.secondary>

        <x-buttons.primary type="submit">
            Atualizar
        </x-buttons.primary>

    </div>

</form>

@endsection
