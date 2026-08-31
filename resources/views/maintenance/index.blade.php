@extends('layout.app')

@section('title', 'Reparo Externo')

@section('content')

    <div class="space-y-4 sm:space-y-6">

        {{-- PAGE HEADER --}}
        <x-layout.page-header title="Reparo Externo"
            description="Central de controle de ordens de serviço relacionadas ao envio, recebimento e validação de equipamentos.">
            <x-slot:breadcrumb>
                Dashboard / Reparo Externo
            </x-slot:breadcrumb>
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

                <p class="mb-1.5 text-xs sm:mb-2 sm:text-sm">
                    Corrija os seguintes problemas antes de continuar:
                </p>

                <ul class="list-disc space-y-1 pl-4 text-xs sm:pl-5 sm:text-sm">

                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach

                </ul>

            </x-alerts.error>

        @endif

        {{-- ESTATÍSTICAS --}}
        @include('maintenance.partials.stats')

        {{-- AÇÕES RÁPIDAS --}}
        @include('maintenance.partials.quick-actions')

        {{-- FILTROS --}}
        @include('maintenance.partials.filters')

        {{-- ORDENS DE SERVIÇO --}}
        @include('maintenance.partials.orders-table')

    </div>

@endsection

@vite('resources/js/maintenance/order/index.js')
