@extends('layout.app')

@section('content')

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">

        {{-- ============================================================
             CABEÇALHO
             ============================================================ --}}

        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Aprovação de preventiva
                </p>

                <h1 class="mt-1 text-xl font-semibold text-slate-800">
                    {{ $preventive->preventiveType?->name ?? 'Preventiva' }}
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Análise da execução realizada pelo técnico.
                </p>

            </div>

            <div class="flex items-center gap-2">

                <span
                    class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-medium
                    {{ $preventive->status->colorClass() }}"
                >
                    {{ $preventive->status->label() }}
                </span>

                <span
                    class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700"
                >
                    Ciclo {{ $cycle->sequence }}
                </span>

            </div>

        </div>


        {{-- ============================================================
             INFORMAÇÕES DA PREVENTIVA
             ============================================================ --}}

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="text-sm font-semibold text-slate-800">
                    Informações da preventiva
                </h2>

            </div>

            <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 lg:grid-cols-4">

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Filial
                    </p>

                    <p class="mt-1 text-sm font-medium text-slate-800">
                        {{ $preventive->branch?->name ?? '-' }}
                    </p>

                </div>


                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Responsável
                    </p>

                    <p class="mt-1 text-sm font-medium text-slate-800">
                        {{ $preventive->assignedUser?->name ?? '-' }}
                    </p>

                </div>


                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Iniciada em
                    </p>

                    <p class="mt-1 text-sm font-medium text-slate-800">
                        {{ $preventive->start_at?->format('d/m/Y H:i') ?? '-' }}
                    </p>

                </div>


                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Finalizada em
                    </p>

                    <p class="mt-1 text-sm font-medium text-slate-800">
                        {{ $preventive->completed_at?->format('d/m/Y H:i') ?? '-' }}
                    </p>

                </div>

            </div>

        </div>


        {{-- ============================================================
             RESUMO DA EXECUÇÃO
             ============================================================ --}}

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="text-sm font-semibold text-slate-800">
                    Resumo da execução
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Resultado geral da execução do ciclo.
                </p>

            </div>


            <div class="grid grid-cols-2 gap-3 p-5 sm:grid-cols-3 lg:grid-cols-6">

                {{-- UNIDADES --}}

                <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">

                    <p class="text-xs text-slate-500">
                        Unidades
                    </p>

                    <p class="mt-1 text-xl font-semibold text-slate-800">
                        {{ $summary['total_units'] }}
                    </p>

                </div>


                {{-- CONFORMES --}}

                <div class="rounded-lg border border-slate-200 bg-white px-4 py-3">

                    <p class="text-xs text-slate-500">
                        Conformes
                    </p>

                    <p class="mt-1 text-xl font-semibold text-green-700">
                        {{ $summary['conforming_units'] }}
                    </p>

                </div>


                {{-- NÃO CONFORMES --}}

                <div class="rounded-lg border border-slate-200 bg-white px-4 py-3">

                    <p class="text-xs text-slate-500">
                        Não conformes
                    </p>

                    <p class="mt-1 text-xl font-semibold text-red-700">
                        {{ $summary['non_conforming_units'] }}
                    </p>

                </div>


                {{-- PENDENTES --}}

                <div class="rounded-lg border border-slate-200 bg-white px-4 py-3">

                    <p class="text-xs text-slate-500">
                        Pendentes
                    </p>

                    <p class="mt-1 text-xl font-semibold text-amber-700">
                        {{ $summary['pending_units'] }}
                    </p>

                </div>


                {{-- ATIVIDADES --}}

                <div class="rounded-lg border border-slate-200 bg-white px-4 py-3">

                    <p class="text-xs text-slate-500">
                        Atividades
                    </p>

                    <p class="mt-1 text-xl font-semibold text-slate-800">

                        {{ $summary['answered_activities'] }}

                        <span class="text-sm font-normal text-slate-400">
                            / {{ $summary['total_activities'] }}
                        </span>

                    </p>

                </div>


                {{-- COMPONENTES --}}

                <div class="rounded-lg border border-slate-200 bg-white px-4 py-3">

                    <p class="text-xs text-slate-500">
                        Componentes com falha
                    </p>

                    <p class="mt-1 text-xl font-semibold text-slate-800">
                        {{ count($summary['failed_components']) }}
                    </p>

                </div>

            </div>

        </div>


        {{-- ============================================================
             RESULTADO POR UNIDADE
             ============================================================ --}}

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="text-sm font-semibold text-slate-800">
                    Resultado por unidade
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Resumo das respostas registradas durante a execução.
                </p>

            </div>


            <div class="divide-y divide-slate-200">

                @foreach ($units as $unit)

                    @php

                        $status = $unit['status'];

                        $statusLabel = match ($status) {

                            'conforme' =>
                                'Conforme',

                            'nao_conforme' =>
                                'Não conforme',

                            'pending' =>
                                'Pendente',

                            default =>
                                'Indefinido',
                        };


                        $statusClass = match ($status) {

                            'conforme' =>
                                'bg-green-50 text-green-700',

                            'nao_conforme' =>
                                'bg-red-50 text-red-700',

                            'pending' =>
                                'bg-amber-50 text-amber-700',

                            default =>
                                'bg-slate-100 text-slate-600',
                        };

                    @endphp


                    <div class="px-5 py-5">

                        {{-- CABEÇALHO DA UNIDADE --}}

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

                            <div class="min-w-0">

                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $unit['unit_name'] }}
                                </p>


                                @if (!empty($unit['operational_unit_identifier']))

                                    <p class="mt-0.5 text-xs text-slate-500">
                                        {{ $unit['operational_unit_identifier'] }}
                                    </p>

                                @endif


                                @if (!empty($unit['unit_type_name']))

                                    <p class="mt-0.5 text-xs text-slate-500">
                                        {{ $unit['unit_type_name'] }}
                                    </p>

                                @endif

                            </div>


                            <span
                                class="inline-flex w-fit items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}"
                            >
                                {{ $statusLabel }}
                            </span>

                        </div>


                        {{-- PROGRESSO --}}

                        @php

                            $total =
                                $unit['progress']['total'];

                            $answered =
                                $unit['progress']['answered'];

                            $percentage =
                                $total > 0
                                    ? round(($answered / $total) * 100)
                                    : 0;

                        @endphp


                        <div class="mt-4">

                            <div class="flex items-center justify-between text-xs text-slate-500">

                                <span>
                                    {{ $answered }} de {{ $total }} atividades
                                </span>

                                <span>
                                    {{ $percentage }}%
                                </span>

                            </div>


                            <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-slate-100">

                                <div
                                    class="h-full rounded-full bg-slate-700"
                                    style="width: {{ $percentage }}%;"
                                ></div>

                            </div>

                        </div>


                        {{-- COMPONENTES COM DEFEITO --}}

                        @if (!empty($unit['failed_components']))

                            <div class="mt-5">

                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                    Componentes com defeito
                                </p>


                                <div class="mt-2 space-y-2">

                                    @foreach ($unit['failed_components'] as $component)

                                        <div class="rounded-lg border border-red-100 bg-red-50 px-4 py-3">

                                            <p class="text-sm font-medium text-red-800">

                                                {{ $component['component_name'] ?? ($component['name'] ?? 'Componente') }}

                                            </p>


                                            @if (!empty($component['category_name']))

                                                <p class="mt-0.5 text-xs text-red-600">
                                                    {{ $component['category_name'] }}
                                                </p>

                                            @endif

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        @endif


                        {{-- OBSERVAÇÕES --}}

                        @if (!empty($unit['observations']))

                            <div class="mt-5">

                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                    Observações
                                </p>


                                <div class="mt-2 space-y-2">

                                    @foreach ($unit['observations'] as $observation)

                                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">

                                            <p class="text-sm text-slate-700">
                                                {{ $observation }}
                                            </p>

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        @endif


                        {{-- ========================================================
                             ATIVIDADES
                             ======================================================== --}}

                        <div class="mt-5">

                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                Atividades executadas
                            </p>


                            <div class="mt-2 space-y-2">

                                @foreach ($unit['activities'] as $activity)

                                    @php

                                        $activityResult =
                                            $activity['response']?->result;


                                        $activityStatusClass = match ($activityResult) {

                                            'conforme' =>
                                                'bg-green-50 text-green-700',

                                            'nao_conforme' =>
                                                'bg-red-50 text-red-700',

                                            default =>
                                                'bg-slate-100 text-slate-600',

                                        };


                                        $activityStatusLabel = match ($activityResult) {

                                            'conforme' =>
                                                'Conforme',

                                            'nao_conforme' =>
                                                'Não conforme',

                                            default =>
                                                'Não respondida',

                                        };

                                    @endphp


                                    <div
                                        class="flex flex-col gap-2 rounded-lg border border-slate-200 bg-white px-4 py-3"
                                    >

                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                                            <div class="min-w-0">

                                                {{-- NOME DA ATIVIDADE --}}

                                                <p class="text-sm font-medium text-slate-800">
                                                    {{ $activity['activity_name'] ?? 'Atividade' }}
                                                </p>


                                                {{-- DESCRIÇÃO DA ATIVIDADE --}}

                                                @if (!empty($activity['activity_description']))

                                                    <p class="mt-1 text-xs text-slate-500">
                                                        {{ $activity['activity_description'] }}
                                                    </p>

                                                @endif


                                                {{-- OBSERVAÇÃO DA RESPOSTA --}}

                                                @if ($activity['response']?->observation)

                                                    <p class="mt-1 text-xs text-slate-500">
                                                        {{ $activity['response']->observation }}
                                                    </p>

                                                @endif


                                                {{-- ========================================================
                                                     EVIDÊNCIA FOTOGRÁFICA
                                                     ======================================================== --}}

                                                @if ($activity['response']?->photo)

                                                    <div class="mt-3 border-t border-slate-100 pt-3">

                                                        <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                                                            Evidência fotográfica
                                                        </p>


                                                        <button
                                                            type="button"
                                                            data-photo-modal-open
                                                            data-photo-url="{{ route('preventivas.execucao.response-photo', [
                                                                'preventive' => $preventive->id,
                                                                'response' => $activity['response']->id,
                                                            ]) }}"
                                                            class="group mt-2 block w-full overflow-hidden rounded-lg border border-slate-200 bg-slate-50 transition hover:border-slate-300 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                                                        >

                                                            <img
                                                                src="{{ route('preventivas.execucao.response-photo', [
                                                                    'preventive' => $preventive->id,
                                                                    'response' => $activity['response']->id,
                                                                ]) }}"
                                                                alt="Evidência fotográfica da atividade {{ $activity['activity_name'] ?? 'Atividade' }}"
                                                                class="max-h-48 w-full object-contain transition duration-200 group-hover:scale-[1.01]"
                                                            >

                                                        </button>


                                                        <p class="mt-1 text-[10px] text-slate-400">
                                                            Clique na imagem para visualizar em tamanho maior.
                                                        </p>


                                                        <p class="text-[10px] text-slate-400">

                                                            Registrada em

                                                            {{ $activity['response']->photo->captured_at?->format('d/m/Y H:i:s') ?? '—' }}

                                                        </p>

                                                    </div>

                                                @endif

                                            </div>


                                            <span
                                                class="inline-flex w-fit shrink-0 items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $activityStatusClass }}"
                                            >
                                                {{ $activityStatusLabel }}
                                            </span>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>


        {{-- ============================================================
             AÇÕES DO GESTOR
             ============================================================ --}}

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="text-sm font-semibold text-slate-800">
                    Decisão do gestor
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Após analisar os resultados, escolha uma ação para esta preventiva.
                </p>

            </div>


            <div class="space-y-5 p-5">

                {{-- ========================================================
                     OBSERVAÇÃO DA REVISÃO
                     ======================================================== --}}

                <div>

                    <label
                        for="review_observation"
                        class="block text-xs font-medium uppercase tracking-wide text-slate-500"
                    >
                        Observação da revisão
                    </label>


                    <textarea
                        id="review_observation"
                        name="review_observation"
                        form="reject-preventive-form"
                        rows="4"
                        maxlength="5000"
                        placeholder="Informe o motivo da reprovação ou uma observação sobre a análise..."
                        class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
                    >{{ old('review_observation') }}</textarea>


                    <p class="mt-1.5 text-xs text-slate-400">
                        Obrigatório somente para reprovação.
                    </p>


                    @error('review_observation')

                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ========================================================
                     BOTÕES
                     ======================================================== --}}

                <div class="flex flex-col gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">

                    {{-- REPROVAR --}}

                    <form
                        id="reject-preventive-form"
                        method="POST"
                        action="{{ route('preventivas.reject', $preventive) }}"
                        class="w-full sm:w-auto"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-lg border border-red-200 bg-white px-5 py-2.5 text-sm font-medium text-red-700 transition hover:bg-red-50 active:scale-[0.98] sm:w-auto"
                        >
                            Reprovar preventiva
                        </button>

                    </form>


                    {{-- APROVAR --}}

                    <form
                        method="POST"
                        action="{{ route('preventivas.approve', $preventive) }}"
                        class="w-full sm:w-auto"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-green-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-green-700 active:scale-[0.98] sm:w-auto"
                        >
                            Aprovar preventiva
                        </button>

                    </form>

                </div>

            </div>

        </div>


        {{-- ============================================================
             MODAL DE EVIDÊNCIA FOTOGRÁFICA
             ============================================================ --}}

        @include('preventive-execution.partials._photo-modal')

    </div>


    {{-- ============================================================
         JAVASCRIPT DO MODAL DE FOTO
         ============================================================ --}}

    @vite('resources/js/components/photo-modal.js')

@endsection
