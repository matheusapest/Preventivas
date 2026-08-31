@extends('layout.app')

@section('content')

    <div class="w-full space-y-4 sm:space-y-6 px-4 sm:px-6 lg:px-8 py-4">

        {{-- ============================================================
             CABEÇALHO
             ============================================================ --}}

        <div>
            <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
                <span>Execução da preventiva</span>

                <span>/</span>

                <span class="truncate max-w-[200px] sm:max-w-none">
                    {{ $preventive->name ?? 'Preventiva' }}
                </span>
            </div>

            <div class="mt-2 sm:mt-3">
                <h1 class="text-lg sm:text-xl font-semibold text-slate-800 break-words">
                    {{ $snapshotRuleActivity->activity_name }}
                </h1>

                <p class="mt-1 text-xs sm:text-sm text-slate-500 leading-relaxed">
                    {{ $snapshotRuleActivity->activity_description }}
                </p>
            </div>
        </div>


        {{-- ============================================================
             INFORMAÇÕES DA EXECUÇÃO
             ============================================================ --}}

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-4 py-3.5 sm:px-5 sm:py-4">

                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Execução
                </p>

                <h2 class="mt-0.5 text-sm sm:text-base font-semibold text-slate-800">
                    Informações da atividade
                </h2>

            </div>

            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-3 sm:p-5">

                {{-- UNIDADE OPERACIONAL --}}
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Unidade operacional
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-800 truncate">
                        {{ $snapshotUnit->operational_unit_name }}
                    </p>

                    <p class="mt-0.5 text-xs text-slate-500 truncate">
                        {{ $snapshotUnit->operational_unit_identifier }}
                    </p>
                </div>


                {{-- TIPO DA UNIDADE --}}
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Tipo de unidade
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-800 truncate">
                        {{ $unitTypeName }}
                    </p>
                </div>


                {{-- ATIVIDADE --}}
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Atividade
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-800 truncate">
                        {{ $snapshotRuleActivity->activity_name }}
                    </p>

                    <p class="mt-0.5 text-xs text-slate-500 truncate">
                        {{ $activityTypeLabel }}
                    </p>
                </div>

            </div>

        </div>


        {{-- ============================================================
             COMPOSIÇÃO DO PERFIL
             ============================================================ --}}

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-4 py-3.5 sm:px-5 sm:py-4">

                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Composição do perfil
                </p>

                <h2 class="mt-0.5 text-sm sm:text-base font-semibold text-slate-800">
                    {{ $operationalProfileName }}
                </h2>

                <p class="mt-0.5 text-xs text-slate-500">
                    Componentes previstos para esta unidade operacional.
                </p>

            </div>


            <div class="p-4 sm:p-5">

                @if ($operationalComposition)
                    <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2 lg:grid-cols-3">

                        @foreach ($operationalComposition as $component)
                            <div
                                class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 p-3">

                                <div class="flex items-center gap-3 min-w-0">

                                    <div
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-xs font-bold text-slate-700 ring-1 ring-slate-200">
                                        {{ $component['quantity'] ?? 1 }}
                                    </div>

                                    <div class="min-w-0">

                                        <p class="text-sm font-medium text-slate-800 truncate">
                                            {{ $component['name'] ?? 'Componente' }}
                                        </p>

                                    </div>

                                </div>

                                @if (($component['quantity'] ?? 1) > 1)
                                    <span class="text-xs text-slate-500 shrink-0 ml-2">
                                        {{ $component['quantity'] }} un.
                                    </span>
                                @endif

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-center">
                        <p class="text-xs sm:text-sm text-slate-500">
                            Nenhum componente foi registrado na composição desta unidade.
                        </p>
                    </div>
                @endif

            </div>

        </div>


        {{-- ============================================================
             FORMULÁRIO DE EXECUÇÃO
             ============================================================ --}}

        <form id="activity-execution-form" method="POST"
            action="{{ route('preventivas.execucao.activity.store', [
                'preventive' => $preventive->id,
                'cycleUnit' => $cycleUnit->id,
                'activity' => $cycleUnitActivity->snapshot_rule_activity_id,
            ]) }}">

            @csrf

            {{-- PERGUNTA PRINCIPAL --}}
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-4 py-3.5 sm:px-5 sm:py-4">

                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Avaliação
                    </p>

                    <h2 class="mt-0.5 text-sm sm:text-base font-semibold text-slate-800 leading-snug">
                        O conjunto do {{ $unitTypeName }} está operacional?
                    </h2>

                </div>


                <div class="space-y-4 sm:space-y-5 p-4 sm:p-5">

                    {{-- STATUS OPERACIONAL --}}
                    <div>

                        <label for="operational_status"
                            class="block text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Situação
                        </label>

                        <select id="operational_status" name="operational_status"
                            class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
                            required>
                            <option value="" disabled selected>
                                Selecione uma opção
                            </option>

                            <option value="yes">
                                Sim — está operacional
                            </option>

                            <option value="no">
                                Não — apresenta problema
                            </option>

                        </select>

                    </div>


                    {{-- COMPONENTES COM FALHA --}}
                    <div id="failed-components-section" class="hidden">

                        <div class="rounded-lg border border-amber-200 bg-amber-50 p-3.5 sm:p-4">

                            <p class="text-xs font-semibold text-amber-800">
                                Atenção
                            </p>

                            <p class="mt-0.5 text-xs text-amber-700">
                                Marque os itens que não estão operacionais ou apresentam defeito.
                            </p>

                        </div>


                        <div class="mt-4">

                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Componentes com problema
                            </p>

                            <div class="mt-2.5 space-y-2">

                                @foreach ($operationalComposition as $component)
                                    @php
                                        $categoryId = $component['category_id'] ?? null;
                                        $categoryName = $component['category_name'] ?? '';
                                        $quantity = (int) ($component['quantity'] ?? 1);
                                        $componentName = $component['name'] ?? 'Componente';
                                    @endphp

                                    @for ($index = 1; $index <= $quantity; $index++)
                                        <label
                                            class="flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 bg-white p-3.5 transition hover:bg-slate-50 active:bg-slate-100">

                                            <input type="checkbox"
                                                class="js-failed-component h-4 w-4 shrink-0 rounded border-slate-300 text-gray-900 focus:ring-gray-500"
                                                data-category-id="{{ $categoryId }}"
                                                data-category-name="{{ $categoryName }}"
                                                data-component-name="{{ $componentName }}"
                                                data-quantity-index="{{ $index }}"
                                                data-quantity="{{ $quantity }}">

                                            <div class="min-w-0 flex-1">

                                                <p class="text-sm font-medium text-slate-800 truncate">
                                                    {{ $componentName }}
                                                </p>

                                                @if ($quantity > 1)
                                                    <p class="mt-0.5 text-xs text-slate-500">
                                                        Unidade {{ $index }}
                                                    </p>
                                                @endif

                                            </div>

                                        </label>
                                    @endfor
                                @endforeach

                            </div>

                        </div>

                    </div>


                    {{-- SITUAÇÃO FINAL DA NÃO CONFORMIDADE --}}
                    <div id="final-status-section" class="hidden">

                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-3.5 sm:p-4">

                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Situação final
                            </p>

                            <p class="mt-0.5 text-xs text-slate-600">
                                Informe o que aconteceu com o problema identificado.
                            </p>


                            <div class="mt-3 space-y-2.5">

                                {{-- RESOLVIDO --}}
                                <label
                                    class="flex cursor-pointer items-start gap-3 rounded-lg border border-slate-200 bg-white p-3.5 transition hover:bg-slate-50 active:bg-slate-100">

                                    <input type="radio" name="final_status" value="resolvido"
                                        class="js-final-status mt-0.5 h-4 w-4 shrink-0 border-slate-300 text-gray-900 focus:ring-gray-500">

                                    <div class="min-w-0 flex-1">

                                        <p class="text-sm font-medium text-slate-800">
                                            Resolvido
                                        </p>

                                        <p class="mt-0.5 text-xs text-slate-500">
                                            O problema foi corrigido durante a execução.
                                        </p>

                                    </div>

                                </label>


                                {{-- PENDENTE --}}
                                <label
                                    class="flex cursor-pointer items-start gap-3 rounded-lg border border-slate-200 bg-white p-3.5 transition hover:bg-slate-50 active:bg-slate-100">

                                    <input type="radio" name="final_status" value="pendente"
                                        class="js-final-status mt-0.5 h-4 w-4 shrink-0 border-slate-300 text-gray-900 focus:ring-gray-500">

                                    <div class="min-w-0 flex-1">

                                        <p class="text-sm font-medium text-slate-800">
                                            Pendente
                                        </p>

                                        <p class="mt-0.5 text-xs text-slate-500">
                                            O problema permanece pendente e necessita de providência.
                                        </p>

                                    </div>

                                </label>

                            </div>

                        </div>

                    </div>


                    {{-- OBSERVAÇÃO --}}
                    <div>

                        <label for="observation" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Observação
                        </label>

                        <textarea id="observation" name="observation" rows="3"
                            class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
                            placeholder="Informe alguma observação sobre a execução..."></textarea>

                        <p id="observation-help" class="mt-1.5 hidden text-xs text-amber-700">
                            Descreva como a não conformidade foi resolvida ou por que permanece pendente.
                        </p>

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 border-t border-slate-200 bg-slate-50 px-4 py-3 sm:px-5 sm:py-4">

                    <a href="{{ route('preventivas.execucao.show', $preventive->id) }}"
                        class="inline-flex w-full sm:w-auto items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 active:scale-[0.98]">
                        Cancelar
                    </a>

                    <button type="submit"
                        class="inline-flex w-full sm:w-auto items-center justify-center rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-gray-700 active:scale-[0.98]">
                        Finalizar atividade
                    </button>

                </div>

            </div>

        </form>

    </div>

@endsection


@push('scripts')
@endpush

@vite('resources/js/preventive-execution/activity.js')
