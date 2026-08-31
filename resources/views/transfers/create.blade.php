@extends('layout.app')

@section('title', 'Enviar Equipamento')

@section('content')

<div class="w-full min-w-0 flex-1 space-y-6 sm:space-y-8 p-4 sm:p-6">

    {{-- Page Header --}}
    <x-layout.page-header
        title="Enviar Equipamento"
        description="Realize a transferência de um equipamento entre filiais."
    >
        <x-slot:breadcrumb>
            <nav class="flex text-xs text-slate-500 gap-1.5 items-center">
                <span>Dashboard</span>
                <span>/</span>
                <span>Operação</span>
                <span>/</span>
                <a href="{{ route('transferencias.index') }}" class="hover:text-slate-800 transition">Transferências</a>
                <span>/</span>
                <span class="font-medium text-slate-800">Enviar</span>
            </nav>
        </x-slot:breadcrumb>

        <x-slot:actions>
            <x-buttons.secondary :href="route('transferencias.index')">
                Voltar
            </x-buttons.secondary>
        </x-slot:actions>
    </x-layout.page-header>

    {{-- Seções Interativas --}}
    <div class="w-full min-w-0 space-y-6 sm:space-y-8">

        {{-- 1. Busca --}}
        @include('transfers.partials.search-form')

        {{-- 2. Resultado da busca (Adicionado w-full min-w-0 aqui) --}}
        <div id="equipment-result" class="hidden w-full min-w-0">
            @include('transfers.partials.equipment-details')
        </div>

        {{-- 3. Formulário de envio --}}
        @include('transfers.partials.transfer-form')

    </div>

</div>

@endsection

@vite('resources/js/transfers/create.js')
