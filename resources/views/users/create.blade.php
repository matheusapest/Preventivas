@extends('layout.app')

@section('title', 'Novo Usuário')

@section('content')

    <x-layout.page-header
        title="Novo Usuário"
        description="Cadastre um novo usuário para acessar o sistema."
    >

        <x-slot:breadcrumb>

            Dashboard /
            Configurações /
            Segurança /
            Usuários /
            Novo

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
        action="{{ route('users.store') }}"
        method="POST"
        class="space-y-6"
    >

        @csrf

        @include('users._form', [
            'mode' => 'create',
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
                Salvar Usuário
            </x-buttons.primary>

        </div>

    </form>

@endsection
