@extends('layout.app')

@section('title', 'Dashboard')

@section('content')

    <div class="w-full space-y-4 px-3 py-3 sm:space-y-6 sm:px-6 lg:px-8">

        {{-- ============================================================
             CABEÇALHO DA PÁGINA
        ============================================================= --}}

        <x-layout.page-header
            title="Dashboard"
            description="Visão geral das manutenções e preventivas em andamento."
        >
            <x-slot:breadcrumb>
                Dashboard / Visão Geral
            </x-slot:breadcrumb>
        </x-layout.page-header>


        {{-- ============================================================
             SEÇÃO 1 — MANUTENÇÕES
        ============================================================= --}}

        <div class="space-y-3 sm:space-y-4">

            <div class="border-b border-slate-200/80 pb-2">

                <h2 class="text-xs font-semibold text-slate-800 sm:text-sm">
                    Manutenções
                </h2>

                <p class="mt-0.5 text-[11px] text-slate-500 sm:text-xs">
                    Acompanhamento de equipamentos enviados para manutenção.
                </p>

            </div>


            <div class="grid grid-cols-2 gap-3 sm:grid-cols-2 lg:grid-cols-4 sm:gap-4">

                {{-- =====================================================
                     RECEBIMENTO PENDENTE
                ====================================================== --}}

                <a
                    href="{{ route('reparos_externos.recebimentos.index') }}"
                    class="relative block overflow-hidden rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm transition hover:border-amber-300 hover:shadow-md sm:p-5"
                >

                    <div class="absolute left-0 top-0 h-full w-1 bg-amber-500"></div>

                    <div class="flex items-start justify-between gap-2">

                        <div class="min-w-0">

                            <p class="truncate text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                                Recebimento Pendente
                            </p>

                            <p class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:mt-2 sm:text-3xl">
                                {{ $dashboard['maintenance']['pending_receipt'] }}
                            </p>

                        </div>

                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600 sm:h-10 sm:w-10">

                            <svg
                                class="h-4 w-4 sm:h-5 sm:w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0l-3-3m3 3l-3 3M4 13l3-3m-3 3l3 3"
                                />
                            </svg>

                        </div>

                    </div>

                    <p class="mt-2 text-[10px] font-medium text-slate-500 sm:mt-3 sm:text-xs">
                        Enviados e não recebidos
                    </p>

                </a>


                {{-- =====================================================
                     ATRASADOS +7 DIAS
                ====================================================== --}}

                <a
                    href="{{ route('reparos_externos.recebimentos.index') }}"
                    class="relative block overflow-hidden rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm transition hover:border-red-300 hover:shadow-md sm:p-5"
                >

                    <div class="absolute left-0 top-0 h-full w-1 bg-red-500"></div>

                    <div class="flex items-start justify-between gap-2">

                        <div class="min-w-0">

                            <p class="truncate text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                                Atrasados +7 dias
                            </p>

                            <p class="mt-1 text-2xl font-bold tracking-tight text-red-600 sm:mt-2 sm:text-3xl">
                                {{ $dashboard['maintenance']['overdue_receipt'] }}
                            </p>

                        </div>

                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600 sm:h-10 sm:w-10">

                            <svg
                                class="h-4 w-4 sm:h-5 sm:w-5"
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

                    <p class="mt-2 text-[10px] font-medium text-slate-500 sm:mt-3 sm:text-xs">
                        Aguardando há +7 dias
                    </p>

                </a>

            </div>

        </div>


        {{-- ============================================================
             SEÇÃO 2 — PREVENTIVAS
        ============================================================= --}}

        <div class="space-y-3 sm:space-y-4">

            <div class="border-b border-slate-200/80 pb-2">

                <h2 class="text-xs font-semibold text-slate-800 sm:text-sm">
                    Preventivas
                </h2>

                <p class="mt-0.5 text-[11px] text-slate-500 sm:text-xs">
                    Situação atual das preventivas e seus ciclos de execução.
                </p>

            </div>


            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 sm:gap-4">

                {{-- =====================================================
                     PROGRAMADAS
                ====================================================== --}}

                <a
                    href="{{ route('preventivas.index', ['execution_state' => 'programmed']) }}"
                    class="block rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm transition hover:border-slate-300 hover:shadow-md sm:p-5"
                >

                    <p class="truncate text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                        Programadas
                    </p>

                    <p class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:mt-2 sm:text-3xl">
                        {{ $dashboard['preventives']['programmed'] }}
                    </p>

                    <p class="mt-2 truncate text-[10px] font-medium text-slate-500 sm:mt-3 sm:text-xs">
                        Previsão futura
                    </p>

                </a>


                {{-- =====================================================
                     PENDENTES DE EXECUÇÃO
                ====================================================== --}}

                <a
                    href="{{ route('preventivas.index', ['execution_state' => 'pending_execution']) }}"
                    class="block rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm transition hover:border-amber-300 hover:shadow-md sm:p-5"
                >

                    <p class="truncate text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                        Pendentes
                    </p>

                    <p class="mt-1 text-2xl font-bold tracking-tight text-amber-600 sm:mt-2 sm:text-3xl">
                        {{ $dashboard['preventives']['pending_execution'] }}
                    </p>

                    <p class="mt-2 truncate text-[10px] font-medium text-slate-500 sm:mt-3 sm:text-xs">
                        Data atingida
                    </p>

                </a>


                {{-- =====================================================
                     EM EXECUÇÃO
                ====================================================== --}}

                <a
                    href="{{ route('preventivas.index', ['status' => 'in_progress']) }}"
                    class="block rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm transition hover:border-blue-300 hover:shadow-md sm:p-5"
                >

                    <p class="truncate text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                        Em Execução
                    </p>

                    <p class="mt-1 text-2xl font-bold tracking-tight text-blue-600 sm:mt-2 sm:text-3xl">
                        {{ $dashboard['preventives']['in_execution'] }}
                    </p>

                    <p class="mt-2 truncate text-[10px] font-medium text-slate-500 sm:mt-3 sm:text-xs">
                        Técnico executando
                    </p>

                </a>


                {{-- =====================================================
                     AGUARDANDO APROVAÇÃO
                ====================================================== --}}

                <a
                    href="{{ route('preventivas.index', ['status' => 'pending_approval']) }}"
                    class="block rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm transition hover:border-purple-300 hover:shadow-md sm:p-5"
                >

                    <p class="truncate text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                        Aguard. Aprovação
                    </p>

                    <p class="mt-1 text-2xl font-bold tracking-tight text-purple-600 sm:mt-2 sm:text-3xl">
                        {{ $dashboard['preventives']['pending_approval'] }}
                    </p>

                    <p class="mt-2 truncate text-[10px] font-medium text-slate-500 sm:mt-3 sm:text-xs">
                        Análise do gestor
                    </p>

                </a>


                {{-- =====================================================
                     EXECUTADAS NO MÊS
                ====================================================== --}}

                <a
                    href="{{ route('preventivas.index', ['execution_state' => 'executed_current_month']) }}"
                    class="block rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm transition hover:border-slate-300 hover:shadow-md sm:p-5"
                >

                    <p class="truncate text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                        Executadas (Mês)
                    </p>

                    <p class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:mt-2 sm:text-3xl">
                        {{ $dashboard['preventives']['executed'] }}
                    </p>

                    <p class="mt-2 truncate text-[10px] font-medium text-slate-500 sm:mt-3 sm:text-xs">
                        Concluídas no mês
                    </p>

                </a>


                {{-- =====================================================
                     APROVADAS
                ====================================================== --}}

                <a
                    href="{{ route('preventivas.index', ['status' => 'approved']) }}"
                    class="block rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm transition hover:border-emerald-300 hover:shadow-md sm:p-5"
                >

                    <p class="truncate text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                        Aprovadas
                    </p>

                    <p class="mt-1 text-2xl font-bold tracking-tight text-emerald-600 sm:mt-2 sm:text-3xl">
                        {{ $dashboard['preventives']['approved'] }}
                    </p>

                    <p class="mt-2 truncate text-[10px] font-medium text-slate-500 sm:mt-3 sm:text-xs">
                        Total aprovado
                    </p>

                </a>


                {{-- =====================================================
                     REPROVADAS
                ====================================================== --}}

                <a
                    href="{{ route('preventivas.index', ['execution_state' => 'rejected_awaiting_cycle']) }}"
                    class="col-span-2 block rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm transition hover:border-red-300 hover:shadow-md sm:col-span-1 sm:p-5"
                >

                    <p class="truncate text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:text-xs">
                        Reprovadas
                    </p>

                    <p class="mt-1 text-2xl font-bold tracking-tight text-red-600 sm:mt-2 sm:text-3xl">
                        {{ $dashboard['preventives']['rejected_awaiting_cycle'] }}
                    </p>

                    <p class="mt-2 truncate text-[10px] font-medium text-slate-500 sm:mt-3 sm:text-xs">
                        Aguardando novo ciclo
                    </p>

                </a>

            </div>

        </div>


        {{-- ============================================================
             SEÇÃO 3 — ALERTAS E ATENÇÃO NECESSÁRIA
        ============================================================= --}}

        <div class="space-y-3 sm:space-y-4">

            <div class="border-b border-slate-200/80 pb-2">

                <h2 class="text-xs font-semibold text-slate-800 sm:text-sm">
                    Atenção necessária
                </h2>

                <p class="mt-0.5 text-[11px] text-slate-500 sm:text-xs">
                    Itens prioritários que demandam ação do gestor.
                </p>

            </div>


            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 sm:gap-5">


                {{-- =====================================================
                     CARD 1 — EQUIPAMENTOS ATRASADOS
                ====================================================== --}}

                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 bg-slate-50/50 px-4 py-3 sm:px-5">

                        <div class="flex items-center justify-between gap-2">

                            <h3 class="text-xs font-bold text-slate-800 sm:text-sm">
                                Equipamentos aguardando recebimento
                            </h3>

                            <span class="rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-semibold text-red-700">
                                {{ count($dashboard['alerts']['maintenance_overdue']) }}
                            </span>

                        </div>

                        <p class="mt-0.5 text-[11px] text-slate-500 sm:text-xs">
                            Enviados para manutenção há mais de 7 dias.
                        </p>

                    </div>


                    <div class="divide-y divide-slate-100">

                        @forelse ($dashboard['alerts']['maintenance_overdue'] as $shipment)

                            <a
                                href="{{ route('reparos_externos.receber.form', $shipment['id']) }}"
                                class="block p-3.5 transition hover:bg-slate-50/60 sm:p-4"
                            >

                                <div class="flex items-start justify-between gap-3">

                                    <div class="min-w-0 space-y-0.5">

                                        <p class="truncate text-xs font-semibold text-slate-900 sm:text-sm">
                                            {{ $shipment['equipment'] ?? 'Equipamento não identificado' }}
                                        </p>

                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] text-slate-500 sm:text-xs">

                                            <span>
                                                Patrimônio:
                                                <strong class="text-slate-700">
                                                    {{ $shipment['asset_number'] ?? '-' }}
                                                </strong>
                                            </span>

                                            <span>
                                                Filial:
                                                <strong class="text-slate-700">
                                                    {{ $shipment['branch'] ?? '-' }}
                                                </strong>
                                            </span>

                                        </div>

                                    </div>

                                    <span class="shrink-0 rounded-md border border-red-200 bg-red-50 px-2 py-1 text-[10px] font-bold text-red-700 sm:text-xs">
                                        {{ $shipment['days_away'] }} dias
                                    </span>

                                </div>

                            </a>

                        @empty

                            <div class="p-6 text-center">

                                <svg
                                    class="mx-auto h-8 w-8 text-slate-300"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>

                                <p class="mt-2 text-xs text-slate-500 sm:text-sm">
                                    Nenhum equipamento atrasado no momento.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>


                {{-- =====================================================
                     CARD 2 — PREVENTIVAS AGUARDANDO APROVAÇÃO
                ====================================================== --}}

                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 bg-slate-50/50 px-4 py-3 sm:px-5">

                        <div class="flex items-center justify-between gap-2">

                            <h3 class="text-xs font-bold text-slate-800 sm:text-sm">
                                Preventivas aguardando aprovação
                            </h3>

                            <span class="rounded-full bg-purple-100 px-2 py-0.5 text-[10px] font-semibold text-purple-700">
                                {{ count($dashboard['alerts']['preventives_pending_approval']) }}
                            </span>

                        </div>

                        <p class="mt-0.5 text-[11px] text-slate-500 sm:text-xs">
                            Finalizadas pelos técnicos e aguardando validação.
                        </p>

                    </div>


                    <div class="divide-y divide-slate-100">

                        @forelse ($dashboard['alerts']['preventives_pending_approval'] as $preventive)

                            <a
                                href="{{ route('preventivas.show', $preventive) }}"
                                class="block p-3.5 transition hover:bg-slate-50/60 sm:p-4"
                            >

                                <div class="flex items-start justify-between gap-3">

                                    <div class="min-w-0 space-y-0.5">

                                        <p class="truncate text-xs font-semibold text-slate-900 sm:text-sm">
                                            {{ $preventive->preventiveType?->name ?? 'Tipo não identificado' }}
                                        </p>

                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] text-slate-500 sm:text-xs">

                                            <span>
                                                Filial:
                                                <strong class="text-slate-700">
                                                    {{ $preventive->branch?->name ?? '-' }}
                                                </strong>
                                            </span>

                                            <span>
                                                Técnico:
                                                <strong class="text-slate-700">
                                                    {{ $preventive->assignedUser?->name ?? '-' }}
                                                </strong>
                                            </span>

                                        </div>

                                    </div>

                                    <span class="shrink-0 rounded-md border border-purple-200 bg-purple-50 px-2 py-1 text-[10px] font-bold text-purple-700 sm:text-xs">
                                        Aprovação
                                    </span>

                                </div>

                            </a>

                        @empty

                            <div class="p-6 text-center">

                                <svg
                                    class="mx-auto h-8 w-8 text-slate-300"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>

                                <p class="mt-2 text-xs text-slate-500 sm:text-sm">
                                    Nenhuma preventiva aguardando aprovação.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>


                {{-- =====================================================
                     CARD 3 — PREVENTIVAS REPROVADAS
                ====================================================== --}}

                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">

                    <div class="border-b border-slate-200 bg-slate-50/50 px-4 py-3 sm:px-5">

                        <div class="flex items-center justify-between gap-2">

                            <h3 class="text-xs font-bold text-slate-800 sm:text-sm">
                                Preventivas reprovadas aguardando novo ciclo
                            </h3>

                            <span class="rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-semibold text-red-700">
                                {{ count($dashboard['alerts']['preventives_rejected']) }}
                            </span>

                        </div>

                        <p class="mt-0.5 text-[11px] text-slate-500 sm:text-xs">
                            Preventivas que exigem intervenção do gestor.
                        </p>

                    </div>


                    <div class="divide-y divide-slate-100">

                        @forelse ($dashboard['alerts']['preventives_rejected'] as $preventive)

                            <a
                                href="{{ route('preventivas.show', $preventive) }}"
                                class="block p-3.5 transition hover:bg-slate-50/60 sm:p-4"
                            >

                                <div class="flex items-start justify-between gap-3">

                                    <div class="min-w-0 space-y-0.5">

                                        <p class="truncate text-xs font-semibold text-slate-900 sm:text-sm">
                                            {{ $preventive->preventiveType?->name ?? 'Tipo não identificado' }}
                                        </p>

                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] text-slate-500 sm:text-xs">

                                            <span>
                                                Filial:
                                                <strong class="text-slate-700">
                                                    {{ $preventive->branch?->name ?? '-' }}
                                                </strong>
                                            </span>

                                            <span>
                                                Técnico responsável:
                                                <strong class="text-slate-700">
                                                    {{ $preventive->assignedUser?->name ?? '-' }}
                                                </strong>
                                            </span>

                                        </div>

                                    </div>

                                    <span class="shrink-0 rounded-md border border-red-200 bg-red-50 px-2 py-1 text-[10px] font-bold text-red-700 sm:text-xs">
                                        Reprovada
                                    </span>

                                </div>

                            </a>

                        @empty

                            <div class="p-6 text-center">

                                <svg
                                    class="mx-auto h-8 w-8 text-slate-300"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>

                                <p class="mt-2 text-xs text-slate-500 sm:text-sm">
                                    Nenhuma preventiva reprovada no momento.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
