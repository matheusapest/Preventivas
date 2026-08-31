@extends('layout.app')

@section('title', 'Editar Usuário')

@section('content')

    <x-layout.page-header
        title="Editar Usuário"
        description="Atualize as informações do usuário."
    >

        <x-slot:breadcrumb>

            Dashboard /
            Configurações /
            Segurança /
            Usuários /
            Editar

        </x-slot:breadcrumb>

        <x-slot:actions>

            <x-buttons.secondary
                :href="route('users.index')"
            >
                Voltar
            </x-buttons.secondary>

        </x-slot:actions>

    </x-layout.page-header>

    <form
        action="{{ route('users.update', $user) }}"
        method="POST"
        class="space-y-6"
    >

        @csrf
        @method('PUT')

        @include('users._form', [
            'mode' => 'edit',
        ])

        <div class="flex justify-end gap-3">

            <x-buttons.secondary
                :href="route('users.index')"
            >
                Cancelar
            </x-buttons.secondary>

            <x-buttons.primary
                type="submit"
            >
                Atualizar Usuário
            </x-buttons.primary>

        </div>

    </form>

@endsection
