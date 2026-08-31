@extends('layout.app')

@section('title', 'Execução de Preventivas')

@section('content')

    <div class="w-full space-y-4 sm:space-y-6 px-4 sm:px-6 lg:px-8 py-4">

        <x-layout.page-header
            title="Execução de Preventivas"
            description="Acompanhe e execute suas preventivas."
        >
            <x-slot:breadcrumb>
                Dashboard / Execução de Preventivas
            </x-slot:breadcrumb>

            <x-slot:actions>
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


        {{-- RESUMO --}}
        @include('preventive-execution.partials.summary')


        {{-- LISTAGEM --}}
        @include('preventive-execution.partials.preventives-table')

    </div>

@endsection
