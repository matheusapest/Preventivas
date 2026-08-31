<div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">

    {{-- Título e Descrição --}}
    <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
        Buscar Equipamento
    </h2>

    <p class="mb-4 mt-0.5 text-xs text-slate-500 sm:mb-6 sm:text-sm">
        Informe o patrimônio do equipamento para consultar seus dados.
    </p>

    <form id="equipment-search-form" class="space-y-4 sm:space-y-6">
        <div class="grid gap-3 md:grid-cols-[1fr_auto] md:items-end md:gap-4">

            {{-- Campo de Input --}}
            <div class="w-full">
                <label for="consult-equipment-identifier"
                    class="mb-1.5 block text-xs font-medium text-slate-700 sm:text-sm">
                    Patrimônio ou Numero Serial<span class="text-rose-500">*</span>
                </label>

                <div class="relative">
                    <input id="consult-equipment-identifier" name="identifier" type="text" required
                        autocomplete="off"
                        class="
                        w-full
                        rounded-lg
                        border
                        border-slate-300
                        px-4
                        py-3
                        focus:border-blue-500
                        focus:outline-none
                        focus:ring-2
                        focus:ring-blue-500
                    "
                        placeholder="Informe o patrimônio ou número de série">
                </div>
            </div>

            {{-- Botão de Submissão --}}
            <div class="w-full md:w-auto">
                <button id="btn-search-equipment" type="submit"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 active:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-60 md:w-auto sm:py-3">
                    <svg class="mr-2 h-4 w-4 shrink-0 text-white" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Buscar</span>
                </button>
            </div>

        </div>
    </form>

</div>
