@extends('layout.app')

@section('title', 'Unidades Operacionais')

@section('content')

    <div class="space-y-6">

        {{-- PAGE HEADER --}}
        <x-layout.page-header
            title="Unidades Operacionais"
            description="Gerencie as unidades físicas utilizadas nas operações das filiais."
        >
            <x-slot:breadcrumb>
                Dashboard / Configurações / Unidades Operacionais
            </x-slot:breadcrumb>

            <x-slot:actions>

                @can('create', App\Models\Configuration\Operational\OperationalUnit::class)

                    <div class="w-full sm:w-auto">

                        <x-buttons.primary
                            :href="route('configuracoes.unidades-operacionais.create')"
                            class="w-full justify-center sm:w-auto"
                        >
                            Nova Unidade Operacional
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

                <ul class="mt-1 list-inside list-disc space-y-1">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </x-alerts.error>

        @endif


        {{-- FILTROS --}}
        @include(
            'configurations.operational-units.partials._filters'
        )


        {{-- CONTAINER DE DADOS --}}
        <x-cards.card
            class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
        >

            {{-- VISÃO MOBILE --}}
            @include(
                'configurations.operational-units.partials._mobile'
            )


            {{-- VISÃO DESKTOP --}}
            @include(
                'configurations.operational-units.partials._desktop'
            )


            {{-- PAGINAÇÃO --}}
            @if (
                method_exists($operationalUnits, 'hasPages')
                && $operationalUnits->hasPages()
            )

                <div class="border-t border-slate-200 px-4 py-3 sm:px-6">

                    {{ $operationalUnits->links() }}

                </div>

            @endif

        </x-cards.card>

    </div>

@endsection
