@extends('layout.app')

@section('title', 'Novo Perfil Operacional')
@section('page-title', 'Novo Perfil Operacional')

@section('content')

    <div class="space-y-6">

        {{-- Cabeçalho da Página --}}
        <x-layout.page-header
            title="Novo Perfil Operacional"
            description="Cadastre um novo perfil operacional para um tipo de unidade."
        >
            <x-slot:breadcrumb>
                Dashboard / Configurações / Perfis Operacionais / Novo
            </x-slot:breadcrumb>
        </x-layout.page-header>

        {{-- Erros de Validação --}}
        @if ($errors->any())
            <x-alerts.error title="Corrija os seguintes erros:">
                <ul class="list-inside list-disc space-y-1 mt-1 text-xs sm:text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alerts.error>
        @endif

        {{-- Formulário --}}
        <form
            action="{{ route('configuracoes.perfis-operacionais.store') }}"
            method="POST"
            class="space-y-6"
        >
            @csrf

            @include('configurations.operational-profiles._form', [
                'mode' => 'create',
            ])

            {{-- Ações Responsivas --}}
            <div class="flex flex-col-reverse gap-2.5 sm:flex-row sm:items-center sm:justify-end sm:gap-3">

                <a
                    href="{{ route('configuracoes.perfis-operacionais.index') }}"
                    class="inline-flex w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-xs font-semibold text-slate-700 shadow-2xs transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 sm:w-auto sm:text-sm"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-xs font-semibold text-white shadow-2xs transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto sm:text-sm"
                >
                    Salvar Perfil
                </button>

            </div>

        </form>

    </div>

@endsection
