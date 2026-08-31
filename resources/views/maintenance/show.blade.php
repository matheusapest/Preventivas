@extends('layout.app')

@section('title', 'OS #' . $maintenanceOrder->id)

@section('content')

    <div class="space-y-6">

        {{-- Cabeçalho da OS --}}
        @include('maintenance.partials.order.header')


        {{-- Equipamento --}}
        @include('maintenance.partials.order.equipment')


        {{-- Envio atual --}}
        @include('maintenance.partials.order.shipment')


        {{-- Recebimento atual --}}
        @include('maintenance.partials.order.receipt')


        {{-- Validação atual --}}
        @include('maintenance.partials.order.validation')


        {{-- Histórico completo da OS --}}
        @include('maintenance.partials.order.history')

    </div>

@endsection
