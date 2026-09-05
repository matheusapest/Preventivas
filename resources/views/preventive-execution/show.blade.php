@extends('layout.app')

@section('title', 'Execução de Preventiva')

@section('content')
@php
    /*
    |--------------------------------------------------------------------------
    | Controle de execução
    |--------------------------------------------------------------------------
    | $pendingUnits representa o histórico do ciclo carregado.
    | $canExecute representa se existe um ciclo atualmente disponível
    | para novas respostas.
    |
    | As unidades/atividades históricas continuam sendo exibidas mesmo
    | quando a execução está bloqueada.
    |--------------------------------------------------------------------------
    */
    $canExecute = $can_execute ?? false;
    $executionLockedReason = $execution_locked_reason ?? null;
@endphp

    <div class="w-full space-y-4 px-3 py-3 sm:space-y-6 sm:px-6 lg:px-8">

        {{-- ============================================================
             HEADER & AÇÕES SUPERIORES
        ============================================================= --}}

        <x-layout.page-header
            title="Execução de Preventiva"
            description="Execução da preventiva de manutenção."
        >
            <x-slot:breadcrumb>
                Dashboard / Execução / Preventiva #{{ $preventive->id }}
            </x-slot:breadcrumb>

            <x-slot:actions>
                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:items-center">

                    {{-- BOTÃO DE VOLTAR --}}
                    <a
                        href="{{ route('preventivas.execucao.index') }}"
                        class="inline-flex w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-medium text-slate-700 transition hover:bg-slate-50 active:scale-[0.98] sm:w-auto sm:text-sm"
                    >
                        Voltar
                    </a>

                    {{-- BOTÃO PRINCIPAL DE ALERTA PARA FINALIZAÇÃO COM PENDÊNCIAS --}}
                    @if ($canExecute && $pendingUnits->isNotEmpty())
                        <button
                            type="button"
                            id="open-finalize-pending-modal-header"
                            data-finalize-pending-open
                            class="hidden w-full items-center justify-center gap-2 rounded-lg border border-amber-300 bg-amber-500 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-amber-600 active:scale-[0.98] sm:inline-flex sm:w-auto sm:text-sm"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 shrink-0 text-white"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2.2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                />
                            </svg>

                            Finalizar com Pendências
                            ({{ $pendingUnits->count() }})
                        </button>
                    @endif

                </div>
            </x-slot:actions>
        </x-layout.page-header>


        {{-- ============================================================
             INFORMAÇÕES DA PREVENTIVA
        ============================================================= --}}

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-4 py-3 sm:px-5 sm:py-4">

                <h2 class="text-xs font-semibold text-slate-800 sm:text-sm">
                    Preventiva #{{ $preventive->id }}
                </h2>

                <p class="mt-0.5 text-[11px] text-slate-500 sm:text-xs">
                    Informações da preventiva em execução.
                </p>

            </div>

            <div class="grid grid-cols-2 gap-3 p-3.5 sm:grid-cols-3 sm:gap-4 sm:p-5 md:grid-cols-5 lg:gap-5">

                {{-- FILIAL --}}
                <div class="min-w-0">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 sm:text-xs">
                        Filial
                    </p>

                    <p class="mt-0.5 truncate text-xs font-semibold text-slate-800 sm:mt-1 sm:text-sm">
                        {{ $preventive->branch?->name ?? '—' }}
                    </p>
                </div>

                {{-- TIPO --}}
                <div class="min-w-0">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 sm:text-xs">
                        Tipo
                    </p>

                    <p class="mt-0.5 truncate text-xs font-semibold text-slate-800 sm:mt-1 sm:text-sm">
                        {{ $preventive->preventiveType?->name ?? '—' }}
                    </p>
                </div>

                {{-- PERFIL --}}
                <div class="min-w-0">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 sm:text-xs">
                        Perfil
                    </p>

                    <p class="mt-0.5 truncate text-xs font-semibold text-slate-800 sm:mt-1 sm:text-sm">
                        {{ $preventive->preventiveProfile?->name ?? '—' }}
                    </p>
                </div>

                {{-- RESPONSÁVEL --}}
                <div class="min-w-0">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 sm:text-xs">
                        Técnico responsável
                    </p>

                    <p class="mt-0.5 truncate text-xs font-semibold text-slate-800 sm:mt-1 sm:text-sm">
                        {{ $preventive->assignedUser?->name ?? '—' }}
                    </p>
                </div>

                {{-- STATUS --}}
                <div class="col-span-2 min-w-0 sm:col-span-1">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 sm:text-xs">
                        Status
                    </p>

                    <p class="mt-0.5 truncate text-xs font-semibold text-slate-800 sm:mt-1 sm:text-sm">
                        {{ $preventive->status?->label() ?? '—' }}
                    </p>
                </div>

            </div>
        </div>


        {{-- ============================================================
             EXECUÇÃO
        ============================================================= --}}

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-4 py-3 sm:px-5 sm:py-4">

                <h2 class="text-xs font-semibold text-slate-800 sm:text-sm">
                    Execução
                </h2>

                @if ($canExecute && $pendingUnits->isNotEmpty())
                    <p class="mt-0.5 text-[11px] text-slate-500 sm:text-xs">
                        Selecione a unidade operacional que deseja executar.
                    </p>
                @endif

            </div>

            <div class="p-3.5 sm:p-5">

               @if ($canExecute && $pendingUnits->isNotEmpty())

                    <div class="space-y-4">

                        <div>

                            <label
                                for="execution-unit"
                                class="block text-[10px] font-semibold uppercase tracking-wider text-slate-500 sm:text-xs"
                            >
                                Unidade operacional
                            </label>

                            <div class="mt-2 flex flex-col gap-2.5 sm:flex-row sm:items-center">

                                <select
                                    id="execution-unit"
                                    data-preventive-id="{{ $preventive->id }}"
                                    class="js-execution-unit block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-xs text-slate-800 focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500 sm:text-sm"
                                >
                                    <option value="" disabled selected>
                                        Selecione uma unidade operacional
                                    </option>

                                    @foreach ($pendingUnits as $unit)

                                        @php
                                            $cycleUnit = $unit['cycle_unit'];

                                            $pendingActivities = collect($unit['activities'] ?? [])
                                                ->filter(function ($activity) {
                                                    return is_array($activity)
                                                        && !($activity['answered'] ?? false);
                                                })
                                                ->map(function ($activity) {
                                                    $activityModel = $activity['activity'] ?? null;

                                                    $cycleUnitActivity =
                                                        $activity['cycle_unit_activity'] ?? null;

                                                    return [
                                                        'id' =>
                                                            $cycleUnitActivity?->snapshot_rule_activity_id
                                                            ?? ($activityModel?->id ?? null),

                                                        'name' =>
                                                            $activityModel?->activity_name
                                                            ?? 'Atividade',
                                                    ];
                                                })
                                                ->filter(
                                                    fn(array $activity): bool =>
                                                        !empty($activity['id'])
                                                )
                                                ->values()
                                                ->all();
                                        @endphp

                                        <option
                                            value="{{ $cycleUnit->id }}"
                                            data-identifier="{{ $unit['operational_unit_identifier'] ?? '' }}"
                                            data-activities='@json($pendingActivities)'
                                        >
                                            {{ $unit['unit_name'] ?? 'Unidade operacional' }}
                                        </option>

                                    @endforeach

                                </select>

                                {{-- BOTÃO INICIAR EXECUÇÃO --}}
                                <button
                                    type="button"
                                    id="start-unit-execution"
                                    class="js-start-execution flex w-full shrink-0 items-center justify-center rounded-lg bg-gray-900 px-5 py-2.5 text-xs font-medium text-white transition hover:bg-gray-700 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto sm:text-sm"
                                    disabled
                                >
                                    Iniciar execução
                                </button>

                            </div>

                        </div>

                        {{-- ALERTA/ATALHO AUXILIAR DE FINALIZAÇÃO NO MOBILE --}}
                        <div class="block rounded-lg border border-amber-200 bg-amber-50 p-3 sm:hidden">

                            <button
                                type="button"
                                id="open-finalize-pending-modal-mobile"
                                data-finalize-pending-open
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-amber-300 bg-amber-500 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-amber-600 active:scale-[0.98]"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 shrink-0 text-white"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2.2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                    />
                                </svg>

                                Finalizar com Pendências
                            </button>

                            <p class="mt-2 text-center text-[11px] font-medium text-amber-800">
                                Você ainda possui
                                <strong>
                                    {{ $pendingUnits->count() }}
                                </strong>
                                unidade(s) pendente(s).
                            </p>

                        </div>

                    </div>

                @elseif (! $canExecute)

                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3.5 sm:p-5">

                        <div class="flex items-start gap-3">

                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-600 sm:h-9 sm:w-9"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 sm:h-5 sm:w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 8v4l3 2m6-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                    />
                                </svg>
                            </div>

                            <div>

                                <p class="text-xs font-semibold text-slate-800 sm:text-sm">
                                    Execução indisponível
                                </p>

                                <p class="mt-0.5 text-xs text-slate-600 sm:mt-1 sm:text-sm">
                                    {{ $executionLockedReason ?? 'Esta preventiva não possui um ciclo disponível para execução.' }}
                                </p>

                                <p class="mt-1 text-[11px] text-slate-500 sm:text-xs">
                                    As informações das unidades e atividades permanecem disponíveis para consulta.
                                </p>

                            </div>

                        </div>

                    </div>

                @else

                    <div class="rounded-lg border border-green-200 bg-green-50 p-3.5 sm:p-5">

                        <div class="flex items-start gap-3">

                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-700 sm:h-9 sm:w-9"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 sm:h-5 sm:w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m5 12 4 4L19 6"
                                    />
                                </svg>
                            </div>

                            <div>

                                <p class="text-xs font-semibold text-green-800 sm:text-sm">
                                    Execução concluída
                                </p>

                                <p class="mt-0.5 text-xs text-green-700 sm:mt-1 sm:text-sm">
                                    Todas as unidades operacionais foram processadas.
                                </p>

                                <p class="mt-0.5 text-[11px] text-green-600 sm:mt-1 sm:text-xs">
                                    A preventiva foi encaminhada para aprovação do gestor.
                                </p>

                            </div>

                        </div>

                    </div>

                @endif

            </div>
        </div>


        {{-- ============================================================
             PROGRESSO GERAL
        ============================================================= --}}

        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3.5 sm:p-4">

            <div class="flex items-center justify-between gap-3">

                <div>

                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 sm:text-xs">
                        Progresso
                    </p>

                    <p class="mt-0.5 text-xs font-semibold text-slate-800 sm:mt-1 sm:text-sm">
                        {{ $progress['answered_activities'] }}
                        de
                        {{ $progress['total_activities'] }}
                        concluídas
                    </p>

                </div>

                <div class="shrink-0 text-right">

                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 sm:text-xs">
                        Pendentes
                    </p>

                    <p class="mt-0.5 text-xs font-semibold text-slate-800 sm:mt-1 sm:text-sm">
                        {{ $progress['pending_activities'] }}
                    </p>

                </div>

            </div>

            @php
                $totalActivities = $progress['total_activities'];
                $answeredActivities = $progress['answered_activities'];

                $percentage = $totalActivities > 0
                    ? round(($answeredActivities / $totalActivities) * 100)
                    : 0;
            @endphp

            <div class="mt-2.5 sm:mt-3">

                <div class="h-2 overflow-hidden rounded-full bg-slate-200">

                    <div
                        class="h-full rounded-full bg-slate-700 transition-all"
                        style="width: {{ $percentage }}%;"
                    ></div>

                </div>

                <div class="mt-1 text-right text-[11px] font-medium text-slate-500 sm:text-xs">
                    {{ $percentage }}%
                </div>

            </div>

        </div>


        {{-- ============================================================
             UNIDADES OPERACIONAIS
        ============================================================= --}}

        @if ($units->isNotEmpty())

            <div class="space-y-3 sm:space-y-4">

                <div>

                    <h2 class="text-xs font-semibold text-slate-800 sm:text-sm">
                        Unidades operacionais
                    </h2>

                    <p class="mt-0.5 text-[11px] text-slate-500 sm:text-xs">
                        Registro detalhado da execução de cada unidade.
                    </p>

                </div>

                <div class="space-y-4 sm:space-y-5">

                    @foreach ($units as $unit)

                        @php
                            $unitProgress = $unit['progress'] ?? [];

                            $total = $unitProgress['total'] ?? 0;
                            $answered = $unitProgress['answered'] ?? 0;
                            $completed = $unitProgress['completed'] ?? false;

                            $percentage = $total > 0
                                ? round(($answered / $total) * 100)
                                : 0;

                            $unitStatus = $unit['status'] ?? 'pending';

                            $unitStatusLabel = match ($unitStatus) {
                                'conforme' => 'Operando',
                                'nao_conforme' => 'Não conforme',
                                default => $completed
                                    ? 'Concluída'
                                    : 'Em execução',
                            };

                            $unitStatusClasses = match ($unitStatus) {
                                'conforme' =>
                                    'bg-green-50 border-green-200 text-green-700',

                                'nao_conforme' =>
                                    'bg-red-50 border-red-200 text-red-700',

                                default =>
                                    $completed
                                        ? 'bg-slate-50 border-slate-200 text-slate-700'
                                        : 'bg-amber-50 border-amber-200 text-amber-700',
                            };
                        @endphp

                        <div
                            class="space-y-3 overflow-hidden rounded-xl border border-slate-200 bg-white p-3.5 shadow-sm sm:space-y-4 sm:p-5"
                        >

                            {{-- ============================================================
                                 BLOCO 1 — INFORMAÇÕES DA UNIDADE
                            ============================================================= --}}

                            <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-3.5 sm:p-4">

                                <p class="mb-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:mb-3">
                                    Informações sobre a Unidade Operacional
                                </p>

                                <div class="grid grid-cols-2 gap-3 sm:gap-4">

                                    <div class="min-w-0">

                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                            Nome da Unidade
                                        </p>

                                        <p class="mt-0.5 truncate text-xs font-semibold text-slate-900 sm:text-sm">
                                            {{ $unit['unit_name'] ?? 'Unidade operacional' }}
                                        </p>

                                    </div>

                                    <div class="min-w-0">

                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                            Tipo da Unidade
                                        </p>

                                        <p class="mt-0.5 truncate text-xs font-semibold text-slate-800 sm:text-sm">
                                            {{ $unit['unit_type_name'] ?? ($preventive->preventiveProfile?->name ?? '—') }}
                                        </p>

                                    </div>

                                </div>

                                {{-- Barra de Progresso da Unidade --}}
                                <div class="mt-3 border-t border-slate-200/60 pt-2.5 sm:mt-4 sm:pt-3">

                                    <div class="flex items-center justify-between text-xs">

                                        <span class="text-[11px] text-slate-500 sm:text-xs">
                                            {{ $answered }} de {{ $total }} atividades
                                        </span>

                                        <span class="font-semibold text-slate-700">
                                            {{ $percentage }}%
                                        </span>

                                    </div>

                                    <div class="mt-1 h-1.5 overflow-hidden rounded-full bg-slate-200 sm:h-2">

                                        <div
                                            class="h-full rounded-full bg-slate-700 transition-all"
                                            style="width: {{ $percentage }}%;"
                                        ></div>

                                    </div>

                                </div>

                            </div>


                            {{-- ============================================================
                                 BLOCO 2 — ATIVIDADES
                            ============================================================= --}}

                            @if (!empty($unit['activities']))

                                <div class="space-y-3 sm:space-y-4">

                                    @foreach ($unit['activities'] as $activity)

                                        @php
                                            $activityType =
                                                $activity['activity_type'] ?? null;

                                            $activityTypeLabel =
                                                $activity['activity_type_label'] ?? '—';

                                            $activityModel =
                                                $activity['activity'] ?? null;

                                            $activityName =
                                                $activityModel?->activity_name
                                                ?? 'Atividade';

                                            $activityDescription =
                                                $activityModel?->activity_description
                                                ?? '—';

                                            $response =
                                                $activity['response'] ?? null;

                                            $answered =
                                                $activity['answered'] ?? false;

                                            $photo =
                                                $activity['photo'] ?? null;

                                            $observation =
                                                $activity['observation'] ?? null;

                                            $result =
                                                $activity['result'] ?? null;

                                            $resultLabel =
                                                $activity['result_label'] ?? null;

                                            $finalStatus =
                                                $activity['final_status'] ?? null;

                                            $finalStatusLabel =
                                                $activity['final_status_label'] ?? null;

                                            $failedComponents =
                                                $activity['failed_components'] ?? [];

                                            /*
                                            |--------------------------------------------------------------------------
                                            | Classes do resultado
                                            |--------------------------------------------------------------------------
                                            */

                                            $resultClasses = match ($result) {
                                                'conforme' =>
                                                    'bg-green-50 border-green-200 text-green-700',

                                                'nao_conforme' =>
                                                    'bg-red-50 border-red-200 text-red-700',

                                                default =>
                                                    'bg-slate-50 border-slate-200 text-slate-600',
                                            };
                                        @endphp


                                        <div
                                            class="space-y-3 rounded-xl border border-slate-200 bg-white p-3.5 sm:space-y-4 sm:p-4"
                                        >

                                            {{-- ====================================================
                                                 CABEÇALHO DA ATIVIDADE
                                            ===================================================== --}}

                                            <div>

                                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                    Atividade
                                                </p>

                                                <h4 class="mt-0.5 text-xs font-semibold text-slate-900 sm:text-sm">
                                                    {{ $activityName }}
                                                </h4>

                                            </div>


                                            {{-- ====================================================
                                                 INFORMAÇÕES DA ATIVIDADE
                                            ===================================================== --}}

                                            <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2 sm:gap-3">

                                                <div class="min-w-0">

                                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                        Tipo de atividade
                                                    </p>

                                                    <p class="mt-0.5 text-xs font-medium text-slate-700 sm:text-sm">
                                                        {{ $activityTypeLabel }}
                                                    </p>

                                                </div>

                                                <div class="min-w-0">

                                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                        Descrição da atividade
                                                    </p>

                                                    <p class="mt-0.5 break-words text-xs font-medium text-slate-700 sm:text-sm">
                                                        {{ $activityDescription }}
                                                    </p>

                                                </div>

                                            </div>


                                            {{-- ====================================================
                                                 ATIVIDADE: PHOTO
                                            ===================================================== --}}

                                            @if ($activityType === 'photo')

                                                <div class="space-y-3">

                                                    {{-- Evidência --}}
                                                    @if ($photo)

                                                        <div class="border-t border-slate-100 pt-3 sm:pt-4">

                                                            <span class="mb-2 block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                                Evidência fotográfica
                                                            </span>

                                                            <button
                                                                type="button"
                                                                data-photo-modal-open
                                                                data-photo-url="{{ route('preventivas.execucao.response-photo', [
                                                                    'preventive' => $preventive->id,
                                                                    'response' => $response?->id,
                                                                ]) }}"
                                                                class="group block w-full overflow-hidden rounded-lg border border-slate-200 bg-white transition hover:border-slate-300 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                                                            >

                                                                <img
                                                                    src="{{ route('preventivas.execucao.response-photo', [
                                                                        'preventive' => $preventive->id,
                                                                        'response' => $response?->id,
                                                                    ]) }}"
                                                                    alt="Evidência fotográfica da atividade {{ $activityName }}"
                                                                    class="max-h-80 w-full object-contain transition duration-200 group-hover:scale-[1.01]"
                                                                >

                                                            </button>

                                                            <p class="mt-1.5 text-[10px] text-slate-400">
                                                                Clique na imagem para visualizar em tamanho maior.
                                                            </p>

                                                        </div>

                                                    @else

                                                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5">

                                                            <p class="text-xs text-slate-500">
                                                                Nenhuma evidência fotográfica registrada.
                                                            </p>

                                                        </div>

                                                    @endif


                                                    {{-- Observação da atividade fotográfica --}}
                                                    @if ($observation)

                                                        <div class="border-t border-slate-100 pt-3 sm:pt-4">

                                                            <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                                Observação do Técnico
                                                            </span>

                                                            <div class="break-words rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-xs leading-relaxed text-slate-700 sm:px-3 sm:py-2">
                                                                {{ $observation }}
                                                            </div>

                                                        </div>

                                                    @endif

                                                </div>


                                            {{-- ====================================================
                                                 ATIVIDADE: OPERATIONAL COMPOSITION
                                            ===================================================== --}}

                                            @elseif ($activityType === 'operational_composition')

                                                <div class="space-y-3">

                                                    {{-- Resultado --}}
                                                    <div>

                                                        <p class="mb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                            Resultado da atividade
                                                        </p>

                                                        @if ($answered && $resultLabel)

                                                            <div class="inline-flex rounded-lg border px-2.5 py-1 text-xs font-semibold {{ $resultClasses }}">
                                                                {{ $resultLabel }}
                                                            </div>

                                                        @else

                                                            <div class="inline-flex rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                                                Pendente
                                                            </div>

                                                        @endif

                                                    </div>


                                                    {{-- Componentes com problema --}}
                                                    @if (!empty($failedComponents))

                                                        <div class="border-t border-slate-100 pt-2.5 sm:pt-3">

                                                            <p class="mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                                Componentes que apresentaram problema
                                                            </p>

                                                            <div class="space-y-1.5 sm:space-y-3">

                                                                @foreach ($failedComponents as $component)

                                                                    @php
                                                                        $componentName = is_array($component)
                                                                            ? ($component['component_name'] ?? 'Componente')
                                                                            : $component;
                                                                    @endphp

                                                                    <div class="flex min-w-0 items-center justify-between gap-2 rounded-lg border border-red-100 bg-red-50/80 px-2.5 py-1.5 sm:px-3 sm:py-2">

                                                                        <span class="min-w-0 truncate text-xs font-medium text-red-800">
                                                                            {{ $componentName }}
                                                                        </span>

                                                                        <span class="shrink-0 rounded-md bg-red-100 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wide text-red-700 sm:text-[10px]">
                                                                            Problema
                                                                        </span>

                                                                    </div>

                                                                @endforeach

                                                            </div>

                                                        </div>

                                                    @endif


                                                    {{-- Situação final --}}
                                                    @if ($finalStatus)

                                                        <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-3 space-y-2.5 sm:p-3.5 sm:space-y-3">

                                                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                                Situação final
                                                            </p>

                                                            <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2 sm:gap-3">

                                                                {{-- Status inicial da unidade --}}
                                                                <div class="min-w-0">

                                                                    <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                                        Status inicial do
                                                                        {{ $unit['unit_type_name'] ?? ($preventive->preventiveProfile?->name ?? '—') }}
                                                                    </span>

                                                                    <div class="inline-flex items-center rounded-lg border px-2.5 py-1 text-xs font-semibold {{ $unitStatusClasses }}">
                                                                        {{ $unitStatusLabel }}
                                                                    </div>

                                                                </div>


                                                                {{-- Status final da atividade --}}
                                                                <div class="min-w-0">

                                                                    <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                                        Status final do
                                                                        {{ $unit['unit_type_name'] ?? ($preventive->preventiveProfile?->name ?? '—') }}
                                                                    </span>

                                                                    <div class="inline-flex items-center rounded-lg border px-2.5 py-1 text-xs font-semibold {{ $finalStatus->colorClass() }}">
                                                                        {{ $finalStatusLabel ?? $finalStatus->label() }}
                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    @endif


                                                    {{-- Observação --}}
                                                    @if ($observation)

                                                        <div class="border-t border-slate-100 pt-2.5 sm:pt-3">

                                                            <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                                Observação do Técnico
                                                            </span>

                                                            <div class="break-words rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-xs leading-relaxed text-slate-700 sm:px-3 sm:py-2">
                                                                {{ $observation }}
                                                            </div>

                                                        </div>

                                                    @endif

                                                </div>


                                            {{-- ====================================================
                                                 ATIVIDADE: TEXT
                                            ===================================================== --}}

                                            @elseif ($activityType === 'text')

                                                <div class="space-y-3">

                                                    <div class="border-t border-slate-100 pt-3 sm:pt-4">

                                                        <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                            Resposta
                                                        </span>

                                                        <div class="break-words rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs leading-relaxed text-slate-700 sm:text-sm">

                                                            @if ($answered && $response?->response_data !== null)

                                                                {{ is_array($response->response_data)
                                                                    ? json_encode($response->response_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                                                                    : $response->response_data }}

                                                            @else

                                                                <span class="text-slate-400">
                                                                    Atividade pendente.
                                                                </span>

                                                            @endif

                                                        </div>

                                                    </div>

                                                    @if ($observation)

                                                        <div>

                                                            <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                                Observação do Técnico
                                                            </span>

                                                            <div class="break-words rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs leading-relaxed text-slate-700">
                                                                {{ $observation }}
                                                            </div>

                                                        </div>

                                                    @endif

                                                </div>


                                            {{-- ====================================================
                                                 ATIVIDADE: NUMBER
                                            ===================================================== --}}

                                            @elseif ($activityType === 'number')

                                                <div class="space-y-3">

                                                    <div class="border-t border-slate-100 pt-3 sm:pt-4">

                                                        <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                            Valor informado
                                                        </span>

                                                        <div class="inline-flex rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700">

                                                            @if ($answered && $response?->response_data !== null)

                                                                {{ is_array($response->response_data)
                                                                    ? json_encode($response->response_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                                                                    : $response->response_data }}

                                                            @else

                                                                <span class="text-slate-400">
                                                                    Pendente
                                                                </span>

                                                            @endif

                                                        </div>

                                                    </div>

                                                    @if ($observation)

                                                        <div>

                                                            <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                                Observação do Técnico
                                                            </span>

                                                            <div class="break-words rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs leading-relaxed text-slate-700">
                                                                {{ $observation }}
                                                            </div>

                                                        </div>

                                                    @endif

                                                </div>


                                            {{-- ====================================================
                                                 ATIVIDADE: BOOLEAN
                                            ===================================================== --}}

                                            @elseif ($activityType === 'boolean')

                                                <div class="space-y-3">

                                                    <div class="border-t border-slate-100 pt-3 sm:pt-4">

                                                        <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                            Resposta
                                                        </span>

                                                        <div class="inline-flex rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700">

                                                            @if ($answered && $response?->response_data !== null)

                                                                @php
                                                                    $booleanResponse = $response->response_data;

                                                                    if (is_array($booleanResponse)) {
                                                                        $booleanResponse = $booleanResponse['value']
                                                                            ?? $booleanResponse['response']
                                                                            ?? null;
                                                                    }
                                                                @endphp

                                                                @if ($booleanResponse === true || $booleanResponse === 1 || $booleanResponse === '1' || $booleanResponse === 'true')
                                                                    Sim
                                                                @elseif ($booleanResponse === false || $booleanResponse === 0 || $booleanResponse === '0' || $booleanResponse === 'false')
                                                                    Não
                                                                @else
                                                                    {{ $booleanResponse ?? '—' }}
                                                                @endif

                                                            @else

                                                                <span class="text-slate-400">
                                                                    Pendente
                                                                </span>

                                                            @endif

                                                        </div>

                                                    </div>

                                                    @if ($observation)

                                                        <div>

                                                            <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                                Observação do Técnico
                                                            </span>

                                                            <div class="break-words rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs leading-relaxed text-slate-700">
                                                                {{ $observation }}
                                                            </div>

                                                        </div>

                                                    @endif

                                                </div>


                                            {{-- ====================================================
                                                 TIPO DESCONHECIDO
                                            ===================================================== --}}

                                            @else

                                                <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5">

                                                    <p class="text-xs text-slate-500">
                                                        Tipo de atividade não possui uma apresentação configurada.
                                                    </p>

                                                </div>

                                            @endif

                                        </div>

                                    @endforeach

                                </div>

                            @endif

                        </div>

                    @endforeach

                </div>

            </div>

        @endif


        {{-- ============================================================
             PARTIALS
        ============================================================= --}}

        @include('preventive-execution.partials.activity-selection-modal', [
            'canExecute' => $canExecute,
        ])

        @include('preventive-execution.partials._finalize-pending-modal', [
            'preventive' => $preventive,
            'pendingUnits' => $pendingUnits,
            'progress' => $progress,
            'canExecute' => $canExecute,
        ])

        @include('preventive-execution.partials._photo-modal')

    </div>

@endsection


{{-- ============================================================
     JAVASCRIPT
============================================================= --}}

@vite('resources/js/preventive-execution/show.js')
@vite('resources/js/components/photo-modal.js')
