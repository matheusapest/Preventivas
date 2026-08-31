@extends('layout.app')

@section('title', 'Perfis de Preventiva')

@section('content')

    <div class="space-y-6">

        {{-- ================================================================
             CABEÇALHO
             ================================================================ --}}

        <x-layout.page-header
            title="Perfis de Preventiva"
            description="Gerencie os perfis de preventiva e suas respectivas configurações."
        >
            <x-slot:breadcrumb>
                Dashboard / Configurações / Perfis de Preventiva
            </x-slot:breadcrumb>

            <x-slot:actions>

                @can('create', App\Models\PreventiveProfile::class)

                    <div class="w-full sm:w-auto">

                        <x-buttons.primary
                            :href="route('configuracoes.perfis-preventivas.create')"
                            class="w-full justify-center sm:w-auto"
                        >
                            Novo Perfil de Preventiva
                        </x-buttons.primary>

                    </div>

                @endcan

            </x-slot:actions>
        </x-layout.page-header>


        {{-- ================================================================
             MENSAGEM DE SUCESSO
             ================================================================ --}}

        @if (session('success'))

            <x-alerts.success title="Sucesso!">
                {{ session('success') }}
            </x-alerts.success>

        @endif


        {{-- ================================================================
             ERROS
             ================================================================ --}}

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


        {{-- ================================================================
             LISTAGEM
             ================================================================ --}}

        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- MOBILE --}}
            <div class="block md:hidden">

                @include(
                    'configurations.preventive-profiles.partials._mobile',
                    [
                        'profiles' => $profiles,
                    ]
                )

            </div>


            {{-- DESKTOP --}}
            <div class="hidden md:block">

                @include(
                    'configurations.preventive-profiles.partials._desktop',
                    [
                        'profiles' => $profiles,
                    ]
                )

            </div>


            {{-- ============================================================
                 PAGINAÇÃO
                 ============================================================ --}}

            @if (method_exists($profiles, 'hasPages') && $profiles->hasPages())

                <div class="border-t border-slate-200 px-4 py-3 sm:px-6">

                    {{ $profiles->links() }}

                </div>

            @endif

        </x-cards.card>

    </div>

@endsection
