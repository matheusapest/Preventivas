@extends('layout.app')

@section('title', 'Preventivas')

@section('content')

    <div class="w-full space-y-4 px-3 py-3 sm:space-y-6 sm:px-6 lg:px-8">

        {{-- ============================================================
             CABEÇALHO DA PÁGINA
        ============================================================= --}}
        <x-layout.page-header
            title="Preventivas"
            description="Central de controle das preventivas de manutenção dos equipamentos."
        >
            <x-slot:breadcrumb>
                Dashboard / Preventivas
            </x-slot:breadcrumb>

            <x-slot:actions>
                {{-- Espaço reservado para ações globais (ex: Botão Criar Preventiva) --}}
            </x-slot:actions>
        </x-layout.page-header>


        {{-- ============================================================
             ALERTAS E FEEDBACKS DE SISTEMA
        ============================================================= --}}
        @if (session('error'))
            <x-alerts.error title="Não foi possível realizar a operação.">
                {{ session('error') }}
            </x-alerts.error>
        @endif

        @if (session('success'))
            <x-alerts.success title="Operação concluída">
                {{ session('success') }}
            </x-alerts.success>
        @endif

        @if ($errors->any())
            <x-alerts.error title="Não foi possível concluir a operação.">
                <ul class="list-disc space-y-1 pl-5 text-xs sm:text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alerts.error>
        @endif


        {{-- ============================================================
             MÉTRICAS / ESTATÍSTICAS
        ============================================================= --}}
        @include('configurations.preventives.partials.stats')


        {{-- ============================================================
             AÇÕES RÁPIDAS
        ============================================================= --}}
        @include('configurations.preventives.partials.quick-actions')


        {{-- ============================================================
             FILTROS DE PESQUISA
        ============================================================= --}}
        @include('configurations.preventives.partials.filters')


        {{-- ============================================================
             LISTAGEM / TABELA DE PREVENTIVAS
        ============================================================= --}}
        @include('configurations.preventives.partials.preventives-table')

    </div>

@endsection

@vite('resources/js/preventive/index.js')
