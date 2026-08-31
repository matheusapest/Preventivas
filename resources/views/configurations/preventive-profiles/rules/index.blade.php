@extends('layout.app')

@section('title', 'Regras do Perfil de Preventiva')

@section('content')

    <div class="space-y-6">

        {{-- CABEÇALHO DA PÁGINA --}}
        @include('configurations.preventive-profiles.rules.partials._header')

        {{-- ALERTAS DE SESSÃO E ERROS --}}
        @if (session('success'))
            <x-alerts.success title="Sucesso!">
                {{ session('success') }}
            </x-alerts.success>
        @endif

        @if (session('error'))
            <x-alerts.error title="Ops! Ocorreu um problema">
                {{ session('error') }}
            </x-alerts.error>
        @endif

        @if ($errors->any())
            <x-alerts.error title="Ops! Ocorreu um problema">
                <ul class="mt-1 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alerts.error>
        @endif

        {{-- CARD DE RESUMO (MÉTRICAS) --}}
        @include('configurations.preventive-profiles.rules.partials._summary')

        {{-- CARD DE FILTROS --}}
        @include('configurations.preventive-profiles.rules.partials._filters')

        {{-- LISTAGEM PRINCIPAL --}}
        @include('configurations.preventive-profiles.rules.partials._list')

    </div>

@endsection
