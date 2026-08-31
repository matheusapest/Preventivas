@extends('layout.app')

@section('title', 'Editar Equipamento')

@section('content')

<x-layout.page-header
    title="Editar Equipamento"
    description="Atualização das informações do equipamento."
>

    <x-slot:breadcrumb>

        Dashboard /
        Equipamentos /
        Editar Equipamento

    </x-slot:breadcrumb>

</x-layout.page-header>

<form
    action="{{ route('equipamentos.update', $equipment) }}"
    method="POST"
>

    @csrf
    @method('PUT')

    @include('equipments._form', [
        'mode' => 'edit',
    ])

    <div class="mt-6 flex justify-end gap-3">

        <x-buttons.secondary
            :href="route('equipamentos.index')"
        >
            Cancelar
        </x-buttons.secondary>

        <x-buttons.primary type="submit">
            Atualizar
        </x-buttons.primary>

    </div>

</form>

@endsection
