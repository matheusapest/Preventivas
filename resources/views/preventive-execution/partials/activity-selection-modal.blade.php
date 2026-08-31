{{-- ============================================================
     MODAL DE SELEÇÃO DA ATIVIDADE
============================================================= --}}

<div
    id="activity-selection-modal"
    class="fixed inset-0 z-50 hidden overflow-y-auto"
    aria-labelledby="activity-selection-title"
    aria-modal="true"
    role="dialog"
>
    {{-- BACKDROP --}}
    <div
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm js-close-activity-modal"
    ></div>


    {{-- CONTAINER --}}
    <div class="relative flex min-h-full items-center justify-center p-3 sm:p-4">

        <div
            class="my-auto w-full max-w-lg overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl"
        >

            {{-- ====================================================
                 CABEÇALHO
            ===================================================== --}}

            <div class="border-b border-slate-200 px-4 py-3.5 sm:px-5 sm:py-4">

                <div class="flex items-start justify-between gap-4">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Execução
                        </p>

                        <h2
                            id="activity-selection-title"
                            class="mt-0.5 text-base font-semibold text-slate-800"
                        >
                            Iniciar execução
                        </h2>

                        <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
                            Selecione a atividade que deseja executar.
                        </p>

                    </div>


                    <button
                        type="button"
                        class="js-close-activity-modal inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 active:scale-95"
                        aria-label="Fechar"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18 18 6M6 6l12 12"
                            />
                        </svg>

                    </button>

                </div>

            </div>


            {{-- ====================================================
                 CONTEÚDO
            ===================================================== --}}

            <div class="space-y-4 p-4 sm:p-5">

                {{-- UNIDADE --}}
                <div class="rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-3">

                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Unidade operacional
                    </p>

                    <p
                        class="js-unit-identifier mt-0.5 truncate text-sm font-semibold text-slate-800"
                    >
                        —
                    </p>

                </div>


                {{-- ATIVIDADE --}}
                <div>

                    <label
                        for="modal-activity-select"
                        class="block text-xs font-semibold uppercase tracking-wider text-slate-500"
                    >
                        Atividade pendente
                    </label>


                    <select
                        id="modal-activity-select"
                        class="js-activity-select mt-2 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
                    >

                        <option value="">
                            Selecione uma atividade
                        </option>

                    </select>


                    {{-- SEM ATIVIDADES --}}
                    <div
                        class="js-no-activities mt-3 hidden rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-center"
                    >

                        <p class="text-xs text-slate-500 sm:text-sm">
                            Não existem atividades pendentes para esta unidade.
                        </p>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                 FOOTER
            ===================================================== --}}

            <div
                class="flex flex-col-reverse items-center justify-end gap-2 border-t border-slate-200 bg-slate-50 px-4 py-3 sm:flex-row sm:px-5 sm:py-4"
            >

                <button
                    type="button"
                    class="js-close-activity-modal inline-flex w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 active:scale-[0.98] sm:w-auto"
                >
                    Cancelar
                </button>


                <button
                    type="button"
                    class="js-confirm-activity inline-flex w-full items-center justify-center rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-gray-700 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto"
                    disabled
                >
                    Continuar
                </button>

            </div>

        </div>

    </div>

</div>
