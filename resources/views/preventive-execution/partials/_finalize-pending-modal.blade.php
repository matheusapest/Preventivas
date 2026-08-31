{{-- ============================================================
     MODAL — FINALIZAR PREVENTIVA COM PENDÊNCIAS
============================================================= --}}
@php
    $totalActivities = $progress['total_activities'] ?? 0;
    $answeredActivities = $progress['answered_activities'] ?? 0;

    $completionPercentage = $totalActivities > 0
        ? round(($answeredActivities / $totalActivities) * 100)
        : 0;
@endphp

@if ($pendingUnits->isNotEmpty())

    <div
        id="finalize-pending-modal"
        class="fixed inset-0 z-50 hidden"
        aria-hidden="true"
    >

        {{-- BACKDROP --}}
        <div
            class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
            data-finalize-modal-backdrop
        ></div>


        {{-- CONTAINER (CENTRALIZADO EM QUALQUER TELA) --}}
        <div
            class="relative flex min-h-full items-center justify-center p-4"
        >

            <div
                class="relative flex max-h-[90vh] w-full max-w-lg flex-col overflow-hidden rounded-2xl bg-white shadow-xl"
                role="dialog"
                aria-modal="true"
                aria-labelledby="finalize-pending-title"
            >

                {{-- ========================================================
                     ETAPA 1 — CONFIRMAÇÃO
                ========================================================= --}}

                <div
                    id="finalize-pending-step-confirm"
                    class="js-finalize-step flex flex-col overflow-hidden"
                >

                    {{-- HEADER --}}
                    <div class="shrink-0 border-b border-slate-200 px-4 py-3.5 sm:px-5 sm:py-4">
                        <div class="flex items-start gap-3">
                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-700 sm:h-10 sm:w-10"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 9v3.75m0 3.75h.007M10.29 3.86 2.82 17.14A2 2 0 0 0 4.56 20h14.88a2 2 0 0 0 1.74-2.86L13.71 3.86a2 2 0 0 0-3.42 0Z"
                                    />
                                </svg>
                            </div>

                            <div class="min-w-0">
                                <h2
                                    id="finalize-pending-title"
                                    class="text-xs font-semibold text-slate-800 sm:text-base"
                                >
                                    Finalizar preventiva com pendências
                                </h2>

                                <p class="mt-0.5 text-[11px] leading-relaxed text-slate-500 sm:mt-1 sm:text-sm">
                                    A preventiva ainda possui unidades operacionais que não foram executadas.
                                </p>
                            </div>
                        </div>
                    </div>


                    {{-- CONTEÚDO (ROLÁVEL) --}}
                    <div class="space-y-3 overflow-y-auto px-4 py-4 sm:space-y-4 sm:px-5 sm:py-5">
                        <div class="rounded-xl border border-amber-200 bg-amber-50 p-3 sm:p-4">
                            <div class="space-y-1.5 text-xs text-amber-900 sm:space-y-2 sm:text-sm">
                                <p>
                                    Você concluiu
                                    <strong>
                                       {{ $completionPercentage }}%
                                    </strong>
                                    da preventiva.
                                </p>

                                <p>
                                    Ainda faltam
                                    <strong>
                                        {{ $pendingUnits->count() }}
                                    </strong>
                                    unidade(s) operacional(is) para executar.
                                </p>
                            </div>
                        </div>

                        <p class="text-xs leading-relaxed text-slate-600 sm:text-sm">
                            Se você continuar, a preventiva será finalizada e enviada para aprovação do gestor mesmo com essas pendências.
                        </p>
                    </div>


                    {{-- AÇÕES (FIXAS NO RODAPÉ) --}}
                    <div
                        class="shrink-0 flex flex-col-reverse gap-2 border-t border-slate-200 bg-slate-50 px-4 py-3 sm:flex-row sm:justify-end sm:px-5"
                    >
                        <button
                            type="button"
                            class="js-close-finalize-modal inline-flex w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50 active:scale-[0.98] sm:w-auto sm:text-sm"
                        >
                            Não, continuar execução
                        </button>

                        <button
                            type="button"
                            id="confirm-finalize-pending"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-amber-600 px-4 py-2.5 text-xs font-medium text-white transition hover:bg-amber-700 active:scale-[0.98] sm:w-auto sm:text-sm"
                        >
                            Sim, finalizar
                        </button>
                    </div>

                </div>


                {{-- ========================================================
                     ETAPA 2 — JUSTIFICATIVA
                ========================================================= --}}

                <div
                    id="finalize-pending-step-observation"
                    class="js-finalize-step hidden flex flex-col overflow-hidden"
                >

                    {{-- HEADER --}}
                    <div class="shrink-0 border-b border-slate-200 px-4 py-3.5 sm:px-5 sm:py-4">
                        <div class="flex items-start gap-3">
                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-700 sm:h-10 sm:w-10"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M8 10h8M8 14h5M6 19l-3 2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6Z"
                                    />
                                </svg>
                            </div>

                            <div>
                                <h2 class="text-xs font-semibold text-slate-800 sm:text-base">
                                    Justificativa da finalização
                                </h2>

                                <p class="mt-0.5 text-[11px] leading-relaxed text-slate-500 sm:mt-1 sm:text-sm">
                                    Explique por que a preventiva está sendo finalizada com unidades pendentes.
                                </p>
                            </div>
                        </div>
                    </div>


                    {{-- FORMULÁRIO --}}
                    <form
                        id="finalize-pending-form"
                        method="POST"
                        action="{{ route('preventivas.execucao.finalize-with-pending', $preventive) }}"
                        class="flex flex-col overflow-hidden"
                    >
                        @csrf

                        <div class="space-y-3 overflow-y-auto px-4 py-4 sm:space-y-4 sm:px-5 sm:py-5">
                            <div>
                                <label
                                    for="finalize-pending-observation"
                                    class="block text-[10px] font-semibold uppercase tracking-wider text-slate-500 sm:text-xs"
                                >
                                    Motivo
                                </label>

                                <textarea
                                    id="finalize-pending-observation"
                                    name="observation"
                                    rows="3"
                                    minlength="5"
                                    maxlength="5000"
                                    required
                                    placeholder="Informe o motivo da finalização com unidades pendentes..."
                                    class="mt-1.5 block w-full resize-none rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs leading-relaxed text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500 sm:mt-2 sm:text-sm"
                                >{{ old('observation') }}</textarea>

                                <p class="mt-1 text-[11px] text-slate-400 sm:text-xs">
                                    Mínimo de 5 caracteres.
                                </p>

                                <p
                                    id="finalize-pending-observation-error"
                                    class="mt-1 hidden text-[11px] font-medium text-red-600 sm:text-xs"
                                ></p>

                                @error('observation')
                                    <p class="mt-1 text-[11px] font-medium text-red-600 sm:text-xs">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>


                        {{-- AÇÕES (FIXAS NO RODAPÉ) --}}
                        <div
                            class="shrink-0 flex flex-col-reverse gap-2 border-t border-slate-200 bg-slate-50 px-4 py-3 sm:flex-row sm:justify-end sm:px-5"
                        >
                            <button
                                type="button"
                                id="back-to-finalize-confirm"
                                class="inline-flex w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50 active:scale-[0.98] sm:w-auto sm:text-sm"
                            >
                                Voltar
                            </button>

                            <button
                                type="submit"
                                id="submit-finalize-pending"
                                class="inline-flex w-full items-center justify-center rounded-lg bg-gray-900 px-4 py-2.5 text-xs font-medium text-white transition hover:bg-gray-700 active:scale-[0.98] sm:w-auto sm:text-sm"
                            >
                                Finalizar preventiva
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endif
