{{-- ============================================================
    CICLOS DA PREVENTIVA
============================================================= --}}

@if ($cycles->isNotEmpty())

    <div class="space-y-5">

        @foreach ($cycles as $cycle)

            @php

                $units = $cycle['units'] ?? collect();

                $summary = $cycle['summary'] ?? [];

                $totalUnits =
                    $summary['total_units']
                    ?? $units->count();

                $completedUnits =
                    $summary['completed_units']
                    ?? 0;

                $cycleProgress =
                    $totalUnits > 0
                        ? round(($completedUnits / $totalUnits) * 100)
                        : 0;

            @endphp


            {{-- ====================================================
                CICLO
            ===================================================== --}}

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                {{-- CABEÇALHO DO CICLO --}}

                <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">

                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <h3 class="text-base font-semibold text-slate-900">
                                Ciclo {{ $cycle['sequence'] ?? '—' }}
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ $totalUnits }}

                                {{ $totalUnits === 1 ? 'unidade' : 'unidades' }}
                            </p>

                        </div>


                        <div class="text-right">

                            <span
                                class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600"
                            >
                                {{ $cycleProgress }}%
                            </span>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    UNIDADES
                ================================================== --}}

                <div class="divide-y divide-slate-200">

                    @forelse ($units as $unit)

                        @php

                            $activities =
                                $unit['activities']
                                ?? collect();

                            $progress =
                                $unit['progress']
                                ?? [];

                            $totalActivities =
                                $progress['total']
                                ?? $activities->count();

                            $answeredActivities =
                                $progress['answered']
                                ?? $activities
                                    ->where('answered', true)
                                    ->count();

                            $pendingActivities =
                                $progress['pending']
                                ?? $totalActivities - $answeredActivities;

                            $unitProgress =
                                $totalActivities > 0
                                    ? round(
                                        ($answeredActivities / $totalActivities) * 100
                                    )
                                    : 0;

                            $unitStatus =
                                $unit['status']
                                ?? 'pending';

                            $unitName =
                                $unit['unit_name']
                                ?? 'Unidade operacional';

                            $unitIdentifier =
                                $unit['operational_unit_identifier']
                                ?? null;

                            $unitType =
                                $unit['unit_type_name']
                                ?? null;

                            $unitProfile =
                                $unit['operational_profile_name']
                                ?? null;

                        @endphp


                        {{-- =================================================
                            UNIDADE
                        ================================================== --}}

                        <div class="p-5">

                            {{-- CABEÇALHO DA UNIDADE --}}

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

                                <div>

                                    <div class="flex items-center gap-2">

                                        <h4 class="text-sm font-semibold text-slate-900">
                                            {{ $unitName }}
                                        </h4>

                                    </div>


                                    @if ($unitType)

                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $unitType }}
                                        </p>

                                    @endif


                                    @if ($unitProfile)

                                        <p class="text-xs text-slate-400">
                                            {{ $unitProfile }}
                                        </p>

                                    @endif

                                </div>


                                {{-- STATUS DA UNIDADE --}}

                                <div>

                                    @if ($unitStatus === 'conforme')

                                        <span
                                            class="inline-flex w-fit rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700"
                                        >
                                            Conforme
                                        </span>

                                    @elseif ($unitStatus === 'nao_conforme')

                                        <span
                                            class="inline-flex w-fit rounded-full border border-red-200 bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700"
                                        >
                                            Não conforme
                                        </span>

                                    @elseif ($unitStatus === 'pending')

                                        <span
                                            class="inline-flex w-fit rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700"
                                        >
                                            Pendente
                                        </span>

                                    @else

                                        <span
                                            class="inline-flex w-fit rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-600"
                                        >
                                            {{ $unitStatus }}
                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- =================================================
                                PROGRESSO DA UNIDADE
                            ================================================== --}}

                            <div class="mt-4">

                                <div class="mb-2 flex items-center justify-between text-xs">

                                    <span class="text-slate-500">

                                        {{ $answeredActivities }}

                                        de

                                        {{ $totalActivities }}

                                        {{ $totalActivities === 1 ? 'atividade' : 'atividades' }}

                                    </span>

                                    <span class="font-medium text-slate-600">
                                        {{ $unitProgress }}%
                                    </span>

                                </div>


                                <div class="h-1.5 overflow-hidden rounded-full bg-slate-100">

                                    <div
                                        class="h-full rounded-full bg-slate-800 transition-all"
                                        style="width: {{ $unitProgress }}%"
                                    ></div>

                                </div>

                            </div>


                            {{-- =================================================
                                ATIVIDADES
                            ================================================== --}}

                            <div class="mt-5 space-y-3">

                                @forelse ($activities as $activityData)

                                    @php

                                        $activity =
                                            $activityData['activity']
                                            ?? null;

                                        $response =
                                            $activityData['response']
                                            ?? null;

                                        $activityStatus =
                                            $activityData['status']
                                            ?? 'pending';

                                        $failedComponents =
                                            $activityData['failed_components']
                                            ?? [];

                                        /*
                                         * Garante que a Blade sempre trabalhe
                                         * com um array simples.
                                         */

                                        if (
                                            $failedComponents instanceof \Illuminate\Support\Collection
                                        ) {

                                            $failedComponents =
                                                $failedComponents->all();

                                        }

                                        if (
                                            !is_array($failedComponents)
                                        ) {

                                            $failedComponents = [];

                                        }


                                        $activityName =
                                            $activity?->activity_name
                                            ?? 'Atividade';


                                        /*
                                         * Identifica se a atividade é uma
                                         * composição operacional.
                                         *
                                         * result e final_status possuem
                                         * significado operacional somente
                                         * neste tipo de atividade.
                                         */

                                        $isOperationalComposition =
                                            $activity?->activity_type ===
                                            \App\Enums\ActivityKind::OPERATIONAL_COMPOSITION->value;

                                    @endphp


                                    {{-- =================================================
                                        ATIVIDADE
                                    ================================================== --}}

                                    <div
                                        class="rounded-lg border border-slate-200 bg-slate-50/70 p-3.5"
                                    >

                                        {{-- CABEÇALHO --}}

                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                                            <div>

                                                <p class="text-sm font-semibold text-slate-800">
                                                    {{ $activityName }}
                                                </p>

                                            </div>


                                            {{-- RESULTADO DA ATIVIDADE --}}

                                            <div>

                                                @if ($activityStatus === 'conforme')

                                                    <span
                                                        class="inline-flex w-fit rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700"
                                                    >
                                                        Conforme
                                                    </span>

                                                @elseif ($activityStatus === 'nao_conforme')

                                                    <span
                                                        class="inline-flex w-fit rounded-full border border-red-200 bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700"
                                                    >
                                                        Não conforme
                                                    </span>

                                                @else

                                                    <span
                                                        class="inline-flex w-fit rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700"
                                                    >
                                                        Pendente
                                                    </span>

                                                @endif

                                            </div>

                                        </div>


                                        {{-- =================================================
                                            RESPOSTA
                                        ================================================== --}}

                                        @if ($response)

                                            <div class="mt-3 border-t border-slate-200 pt-3">


                                                {{-- =================================================
                                                    INFORMAÇÕES OPERACIONAIS
                                                ================================================== --}}

                                                @if ($isOperationalComposition)

                                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                                                        {{-- SITUAÇÃO INICIAL --}}

                                                        <div>

                                                            <p class="text-xs font-medium text-slate-500">
                                                                Situação inicial
                                                            </p>

                                                            <p class="mt-1 text-sm font-medium text-slate-800">

                                                                @if ($response->result === 'conforme')

                                                                    <span class="text-emerald-700">
                                                                        Conforme
                                                                    </span>

                                                                @elseif ($response->result === 'nao_conforme')

                                                                    <span class="text-red-700">
                                                                        Não conforme
                                                                    </span>

                                                                @else

                                                                    {{ $response->result ?? '—' }}

                                                                @endif

                                                            </p>

                                                        </div>


                                                        {{-- SITUAÇÃO FINAL --}}

                                                        @if ($response->final_status)

                                                            <div>

                                                                <p class="text-xs font-medium text-slate-500">
                                                                    Situação final
                                                                </p>

                                                                <p class="mt-1 text-sm font-medium text-slate-800">

                                                                    @if ($response->final_status instanceof \BackedEnum)

                                                                        @if ($response->final_status->value === 'Operacional')

                                                                            <span class="text-emerald-700">
                                                                                Operacional
                                                                            </span>

                                                                        @elseif ($response->final_status->value === 'resolvido')

                                                                            <span class="text-emerald-700">
                                                                                Resolvido
                                                                            </span>

                                                                        @elseif ($response->final_status->value === 'pendente')

                                                                            <span class="text-amber-700">
                                                                                Pendente
                                                                            </span>

                                                                        @else

                                                                            {{ $response->final_status->value }}

                                                                        @endif

                                                                    @else

                                                                        {{ $response->final_status }}

                                                                    @endif

                                                                </p>

                                                            </div>

                                                        @endif

                                                    </div>

                                                @endif


                                                {{-- =================================================
                                                    RESPONDIDO EM
                                                ================================================== --}}

                                                <div class="mt-3">

                                                    <p class="text-xs font-medium text-slate-500">
                                                        Respondido em
                                                    </p>

                                                    <p class="mt-1 text-sm text-slate-800">
                                                        {{ $response->answered_at?->format('d/m/Y H:i') ?? '—' }}
                                                    </p>

                                                </div>


                                                {{-- =================================================
                                                    COMPONENTES COM FALHA
                                                ================================================== --}}

                                                @if (
                                                    $isOperationalComposition &&
                                                    count($failedComponents) > 0
                                                )

                                                    <div class="mt-4 border-t border-slate-200 pt-3">

                                                        <p class="text-xs font-semibold text-red-600">
                                                            Componentes com falha
                                                        </p>

                                                        <div class="mt-2 space-y-1">

                                                            @foreach ($failedComponents as $component)

                                                                <div
                                                                    class="flex items-center gap-2 rounded-md border border-red-100 bg-red-50 px-3 py-2"
                                                                >

                                                                    <span
                                                                        class="h-1.5 w-1.5 shrink-0 rounded-full bg-red-500"
                                                                    ></span>

                                                                    <span class="text-sm text-red-800">
                                                                        {{ $component }}
                                                                    </span>

                                                                </div>

                                                            @endforeach

                                                        </div>

                                                    </div>

                                                @endif


                                                {{-- =================================================
                                                    OBSERVAÇÃO
                                                ================================================== --}}

                                                @if (
                                                    is_string($response->observation) &&
                                                    trim($response->observation) !== ''
                                                )

                                                    <div class="mt-4 border-t border-slate-200 pt-3">

                                                        <p class="text-xs font-semibold text-slate-500">
                                                            Observação
                                                        </p>

                                                        <div
                                                            class="mt-2 rounded-md bg-white px-3 py-2.5 text-sm text-slate-700"
                                                        >

                                                            <p class="whitespace-pre-line">
                                                                {{ $response->observation }}
                                                            </p>

                                                        </div>

                                                    </div>

                                                @endif


                                                {{-- =================================================
                                                    EVIDÊNCIA FOTOGRÁFICA
                                                ================================================== --}}

                                                @if ($response->photo)

                                                    <div class="mt-4 border-t border-slate-200 pt-3">

                                                        <p class="text-xs font-semibold text-slate-500">
                                                            Evidência fotográfica
                                                        </p>


                                                        <button
                                                            type="button"
                                                            data-photo-modal-open
                                                            data-photo-url="{{ route('preventivas.execucao.response-photo', [
                                                                'preventive' => $preventive->id,
                                                                'response' => $response->id,
                                                            ]) }}"
                                                            class="group mt-2 block w-full overflow-hidden rounded-lg border border-slate-200 bg-white transition hover:border-slate-300 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                                                        >

                                                            <img
                                                                src="{{ route('preventivas.execucao.response-photo', [
                                                                    'preventive' => $preventive->id,
                                                                    'response' => $response->id,
                                                                ]) }}"
                                                                alt="Evidência fotográfica da atividade {{ $activityName }}"
                                                                class="max-h-80 w-full object-contain transition duration-200 group-hover:scale-[1.01]"
                                                            >

                                                        </button>


                                                        <p class="mt-1.5 text-[10px] text-slate-400">
                                                            Clique na imagem para visualizar em tamanho maior.
                                                        </p>


                                                        <p class="text-[10px] text-slate-400">

                                                            Evidência registrada em

                                                            {{ $response->photo->captured_at?->format('d/m/Y H:i:s') ?? '—' }}

                                                        </p>

                                                    </div>

                                                @endif

                                            </div>

                                        @else

                                            <div class="mt-3 border-t border-slate-200 pt-3">

                                                <p class="text-sm text-slate-500">
                                                    Esta atividade ainda não foi respondida.
                                                </p>

                                            </div>

                                        @endif

                                    </div>

                                @empty

                                    <div
                                        class="rounded-lg border border-dashed border-slate-300 px-4 py-6 text-center"
                                    >

                                        <p class="text-sm text-slate-500">
                                            Nenhuma atividade encontrada para esta unidade.
                                        </p>

                                    </div>

                                @endforelse

                            </div>

                        </div>

                    @empty

                        <div class="px-5 py-10 text-center">

                            <p class="text-sm text-slate-500">
                                Nenhuma unidade encontrada neste ciclo.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        @endforeach

    </div>

@else

    <div
        class="rounded-xl border border-dashed border-slate-300 bg-white px-5 py-10 text-center"
    >

        <p class="text-sm font-medium text-slate-700">
            Nenhum ciclo encontrado.
        </p>

        <p class="mt-1 text-sm text-slate-500">
            Esta preventiva ainda não possui ciclos registrados.
        </p>

    </div>

@endif


{{-- ============================================================
    MODAL DE EVIDÊNCIA FOTOGRÁFICA
============================================================= --}}

@include('preventive-execution.partials._photo-modal')


{{-- ============================================================
    JAVASCRIPT DO MODAL
============================================================= --}}

@vite('resources/js/components/photo-modal.js')
