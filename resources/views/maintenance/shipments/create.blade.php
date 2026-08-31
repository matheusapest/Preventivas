@extends('layout.app')

@section('title', 'Enviar Equipamento')

@section('content')

    <div class="w-full min-w-0 flex-1 space-y-4 p-3.5 sm:space-y-6 sm:p-6">

        <x-layout.page-header title="Enviar Equipamento"
            description="Envie um equipamento para reparo em uma empresa terceirizada.">
            <x-slot:breadcrumb>
                <nav class="flex flex-wrap items-center gap-1.5 text-xs text-slate-500">

                    <span class="hidden sm:inline">Dashboard</span>
                    <span class="hidden sm:inline">/</span>

                    <a href="{{ route('reparos_externos.index') }}" class="transition hover:text-slate-800">
                        Reparo Externo
                    </a>

                    <span>/</span>

                    <span class="font-medium text-slate-800">
                        Enviar Equipamento
                    </span>

                </nav>
            </x-slot:breadcrumb>

            <x-slot:actions>
                <x-buttons.secondary :href="route('reparos_externos.index')" class="w-full justify-center sm:w-auto">
                    Voltar
                </x-buttons.secondary>
            </x-slot:actions>
        </x-layout.page-header>


        {{-- ERROS DE VALIDAÇÃO --}}
        @if ($errors->any())

            <div class="rounded-xl border border-red-200 bg-red-50 p-3.5 shadow-sm sm:p-5">

                <div class="flex items-start gap-3">

                    <div
                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-red-100 text-xs font-bold text-red-700 sm:h-8 sm:w-8 sm:text-sm">
                        !
                    </div>

                    <div class="min-w-0 flex-1 break-words">

                        <h2 class="text-xs font-semibold text-red-900 sm:text-base">
                            Não foi possível enviar o equipamento.
                        </h2>

                        <p class="mt-0.5 text-xs text-red-700 sm:mt-1 sm:text-sm">
                            Corrija os seguintes problemas antes de tentar novamente:
                        </p>

                        <ul class="mt-2 list-disc space-y-1 pl-4 text-xs text-red-700 sm:mt-3 sm:pl-5 sm:text-sm">

                            @foreach ($errors->all() as $error)
                                <li class="break-words">
                                    {{ $error }}
                                </li>
                            @endforeach

                        </ul>

                    </div>

                </div>

            </div>

        @endif


        <div class="w-full min-w-0 space-y-4 sm:space-y-6">

            {{-- 1. Busca do equipamento --}}
            @include('maintenance.shipments.partials.search-form')


            {{-- 2. Informações do equipamento --}}
            <div id="equipment-result" class="hidden w-full min-w-0" data-old-equipment-id="{{ old('equipment_id') }}">
                @include('maintenance.shipments.partials.equipment-details')
            </div>

            {{-- 3. Formulário de envio --}}
            <div id="shipment-form-wrapper" data-old-equipment-id="{{ old('equipment_id') }}" class="w-full min-w-0">
                @include('maintenance.shipments.partials.shipment-form')
            </div>

        </div>

    </div>

    @vite('resources/js/maintenance/shipments/create.js')

@endsection
