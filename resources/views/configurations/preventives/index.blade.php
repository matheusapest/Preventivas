@extends('layout.app')

@section('title', 'Preventivas')

@section('content')

    <div class="w-full space-y-4 sm:space-y-6 px-4 sm:px-6 lg:px-8 py-4">

        {{-- PAGE HEADER --}}
        <x-layout.page-header
            title="Preventivas"
            description="Central de controle das preventivas de manutenção dos equipamentos."
        >

            <x-slot:breadcrumb>
                Dashboard / Preventivas
            </x-slot:breadcrumb>

            <x-slot:actions>
                {{-- Criar Preventiva --}}
            </x-slot:actions>

        </x-layout.page-header>


        {{-- FEEDBACK DE ERRO --}}
        @if (session('error'))
            <x-alerts.error title="Não foi possível realizar a operação.">
                {{ session('error') }}
            </x-alerts.error>
        @endif


        {{-- FEEDBACK DE SUCESSO --}}
        @if (session('success'))
            <x-alerts.success title="Operação concluída">
                {{ session('success') }}
            </x-alerts.success>
        @endif


        {{-- ERROS DE VALIDAÇÃO --}}
        @if ($errors->any())
            <x-alerts.error title="Não foi possível concluir a operação.">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alerts.error>
        @endif


        {{-- ESTATÍSTICAS --}}
        @include('configurations.preventives.partials.stats')


        {{-- AÇÕES RÁPIDAS --}}
        @include('configurations.preventives.partials.quick-actions')


        {{-- FILTROS --}}
        @include('configurations.preventives.partials.filters')


        {{-- PREVENTIVAS --}}
        @include('configurations.preventives.partials.preventives-table')

    </div>

@endsection

@vite('resources/js/preventive/index.js')
