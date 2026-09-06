@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="w-full space-y-4 px-3 py-3 sm:space-y-6 sm:px-6 lg:px-8">

    <x-layout.page-header
        title="Dashboard"
        description="Acompanhe suas preventivas pendentes e em execução."
    >
        <x-slot:breadcrumb>
            Dashboard / Visão Geral
        </x-slot:breadcrumb>

        <x-slot:actions>
            <a
                href="{{ route('preventivas.execucao.index') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700"
            >
                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>

                Ver preventivas
            </a>
        </x-slot:actions>
    </x-layout.page-header>


    {{-- Indicadores principais --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

        {{-- Novas --}}
        <a
            href="{{ route('preventivas.execucao.index', ['status' => 'new']) }}"
            class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md"
        >
            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Novas
                    </p>

                    <p class="mt-2 text-3xl font-semibold text-slate-900">
                        {{ $dashboard['newCount'] }}
                    </p>

                    <p class="mt-2 text-sm text-slate-500">
                        Preventivas aguardando execução.
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>

            </div>

            <div class="mt-4 flex items-center gap-1 text-sm font-medium text-slate-600">
                Executar preventivas
                <svg
                    class="h-4 w-4 transition-transform group-hover:translate-x-1"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>
            </div>
        </a>


        {{-- Em andamento --}}
        <a
            href="{{ route('preventivas.execucao.index', ['status' => 'in_progress']) }}"
            class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md"
        >
            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Em andamento
                    </p>

                    <p class="mt-2 text-3xl font-semibold text-slate-900">
                        {{ $dashboard['inProgressCount'] }}
                    </p>

                    <p class="mt-2 text-sm text-slate-500">
                        Preventivas que você já iniciou.
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"
                        />
                    </svg>
                </div>

            </div>

            <div class="mt-4 flex items-center gap-1 text-sm font-medium text-slate-600">
                Continuar execução
                <svg
                    class="h-4 w-4 transition-transform group-hover:translate-x-1"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>
            </div>
        </a>

    </div>


    {{-- Resumo --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-900">
                Minhas preventivas
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Acesse a execução das preventivas atribuídas a você.
            </p>
        </div>

        <div class="grid grid-cols-1 divide-y divide-slate-200 sm:grid-cols-3 sm:divide-x sm:divide-y-0">

            {{-- Total --}}
            <a
                href="{{ route('preventivas.execucao.index') }}"
                class="group px-5 py-5 transition hover:bg-slate-50"
            >
                <p class="text-sm text-slate-500">
                    Total atribuídas
                </p>

                <div class="mt-2 flex items-center justify-between">
                    <span class="text-2xl font-semibold text-slate-900">
                        {{ $dashboard['totalCount'] }}
                    </span>

                    <svg
                        class="h-5 w-5 text-slate-400 transition-transform group-hover:translate-x-1"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                </div>
            </a>


            {{-- Novas --}}
            <a
                href="{{ route('preventivas.execucao.index', ['status' => 'new']) }}"
                class="group px-5 py-5 transition hover:bg-slate-50"
            >
                <p class="text-sm text-slate-500">
                    A executar
                </p>

                <div class="mt-2 flex items-center justify-between">
                    <span class="text-2xl font-semibold text-slate-900">
                        {{ $dashboard['newCount'] }}
                    </span>

                    <svg
                        class="h-5 w-5 text-slate-400 transition-transform group-hover:translate-x-1"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                </div>
            </a>


            {{-- Em andamento --}}
            <a
                href="{{ route('preventivas.execucao.index', ['status' => 'in_progress']) }}"
                class="group px-5 py-5 transition hover:bg-slate-50"
            >
                <p class="text-sm text-slate-500">
                    Em execução
                </p>

                <div class="mt-2 flex items-center justify-between">
                    <span class="text-2xl font-semibold text-slate-900">
                        {{ $dashboard['inProgressCount'] }}
                    </span>

                    <svg
                        class="h-5 w-5 text-slate-400 transition-transform group-hover:translate-x-1"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                        />
                    </svg>
                </div>
            </a>

        </div>
    </div>


    {{-- Orientação --}}
    @if ($dashboard['newCount'] === 0 && $dashboard['inProgressCount'] === 0)

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="flex flex-col items-center justify-center text-center">

                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                    <svg
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>

                <h2 class="mt-4 text-base font-semibold text-slate-900">
                    Nenhuma preventiva pendente
                </h2>

                <p class="mt-1 max-w-md text-sm text-slate-500">
                    No momento, não existem preventivas aguardando execução ou em andamento.
                </p>

                <a
                    href="{{ route('preventivas.execucao.index') }}"
                    class="mt-4 inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                >
                    Ver minhas preventivas
                </a>

            </div>

        </div>

    @endif

</div>
@endsection
