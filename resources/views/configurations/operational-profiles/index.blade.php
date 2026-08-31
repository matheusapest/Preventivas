@extends('layout.app')

@section('title', 'Perfis Operacionais')

@section('content')

    <div class="space-y-6">

        {{-- PAGE HEADER --}}
        <x-layout.page-header
            title="Perfis Operacionais"
            description="Gerencie os perfis operacionais e suas respectivas composições."
        >
            <x-slot:breadcrumb>
                Dashboard / Configurações / Perfis Operacionais
            </x-slot:breadcrumb>

            <x-slot:actions>
                @can('create', App\Models\OperationalProfile::class)
                    <div class="w-full sm:w-auto">
                        <x-buttons.primary
                            :href="route('configuracoes.perfis-operacionais.create')"
                            class="w-full justify-center sm:w-auto"
                        >
                            Novo Perfil Operacional
                        </x-buttons.primary>
                    </div>
                @endcan
            </x-slot:actions>
        </x-layout.page-header>

        {{-- MENSAGEM DE SUCESSO --}}
        @if (session('success'))
            <x-alerts.success title="Sucesso!">
                {{ session('success') }}
            </x-alerts.success>
        @endif

        {{-- ERROS --}}
        @if ($errors->any())
            <x-alerts.error title="Ops! Ocorreu um problema">
                <ul class="list-inside list-disc space-y-1 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alerts.error>
        @endif

        {{-- CONTAINER DE DADOS --}}
        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- VISÃO MOBILE --}}
            @include('configurations.operational-profiles.partials._mobile')

            {{-- VISÃO DESKTOP --}}
            @include('configurations.operational-profiles.partials._desktop')

            {{-- PAGINAÇÃO --}}
            @if (method_exists($operationalProfiles, 'hasPages') && $operationalProfiles->hasPages())
                <div class="border-t border-slate-200 px-4 py-3 sm:px-6">
                    {{ $operationalProfiles->links() }}
                </div>
            @endif

        </x-cards.card>

    </div>

@endsection
