@extends('layout.app')

@section('title', 'Editar Filial')

@section('content')

<x-layout.page-header
    title="Editar Filial"
    description="Atualização dos dados da filial."
>

    <x-slot:breadcrumb>

        Dashboard /
        Cadastros /
        Filiais /
        Editar

    </x-slot:breadcrumb>

</x-layout.page-header>

<form
    action="{{ route('filiais.update', $branch) }}"
    method="POST"
>

    @csrf
    @method('PUT')

    @include('branches._form', [
        'mode' => 'edit',
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
            Atualizar
        </x-buttons.primary>

    </div>

</form>

@endsection
