<div
    class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
>

    {{-- Header --}}
    <button
        type="button"
        id="toggle-quick-actions"
        class="flex w-full items-center justify-between gap-4 px-4 py-3.5 text-left transition hover:bg-slate-50 sm:px-6 sm:py-4"
        aria-expanded="false"
        aria-controls="quick-actions-content"
    >

        <div>

            <h2 class="text-sm font-semibold text-slate-800 sm:text-base">
                Ações Rápidas
            </h2>

            <p class="mt-0.5 text-xs text-slate-500">
                Operações frequentes do reparo externo.
            </p>

        </div>


        {{-- Indicador --}}
        <div class="flex shrink-0 items-center gap-2">

            <span
                id="quick-actions-label"
                class="hidden text-xs font-medium text-slate-400 sm:inline"
            >
                Exibir ações
            </span>

            <svg
                id="quick-actions-icon"
                class="h-4 w-4 text-slate-400 transition-transform duration-200"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="m19 9-7 7-7-7"
                />

            </svg>

        </div>

    </button>


    {{-- Conteúdo --}}
    <div
        id="quick-actions-content"
        class="hidden border-t border-slate-200"
    >

        <div class="grid grid-cols-1 gap-3 p-4 sm:grid-cols-2 sm:p-5">


            {{-- Enviar Equipamento --}}
            <div
                class="flex items-center justify-between gap-4 rounded-lg border border-slate-200 p-3.5 transition hover:border-slate-300 hover:bg-slate-50"
            >

                <div class="min-w-0">

                    <h3 class="text-sm font-semibold text-slate-800">
                        Enviar Equipamento
                    </h3>

                    <p class="mt-0.5 text-xs leading-relaxed text-slate-500">
                        Envie um equipamento para reparo em uma empresa terceirizada.
                    </p>

                </div>


                <x-buttons.primary
                    href="{{ route('reparos_externos.create') }}"
                    class="shrink-0"
                >
                    Enviar
                </x-buttons.primary>

            </div>


            {{-- Receber Equipamentos --}}
            <div
                class="flex items-center justify-between gap-4 rounded-lg border border-slate-200 p-3.5 transition hover:border-slate-300 hover:bg-slate-50"
            >

                <div class="min-w-0">

                    <h3 class="text-sm font-semibold text-slate-800">
                        Receber Equipamentos
                    </h3>

                    <p class="mt-0.5 text-xs leading-relaxed text-slate-500">
                        Registre o recebimento de um ou vários equipamentos retornados do reparo.
                    </p>

                </div>


                <x-buttons.primary
                    href="{{ route('reparos_externos.recebimentos.index') }}"
                    class="shrink-0"
                >
                    Receber
                </x-buttons.primary>

            </div>

        </div>

    </div>

</div>
