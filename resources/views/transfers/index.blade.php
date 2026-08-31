@extends('layout.app')

@section('title', 'Transferências')

@section('content')

    <div class="space-y-6">

        {{-- PAGE HEADER --}}
        <x-layout.page-header
            title="Transferências"
            description="Central de controle das transferências de equipamentos entre filiais."
        >
            <x-slot:breadcrumb>
                Dashboard / Transferências
            </x-slot:breadcrumb>
        </x-layout.page-header>

        {{-- PARTIAL DE ESTATÍSTICAS / CARDS DE RESUMO --}}
        @include('transfers.partials.stats')

        {{-- PARTIAL DE AÇÕES RÁPIDAS (BOTOES / FILTROS) --}}
        @include('transfers.partials.quick-actions')

        {{-- PARTIAL DE TRANSFERÊNCIAS RECENTES (TABELA / CARDS MOBILE) --}}
        @include('transfers.partials.recent-transfers')

    </div>

@endsection
