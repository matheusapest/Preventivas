<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Header --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

        <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
            Localizar Equipamento
        </h2>

        <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
            Informe o patrimônio ou número de série do equipamento.
        </p>

    </div>

    {{-- Corpo do Formulário --}}
    <div class="p-4 sm:p-6">

        <form id="equipment-search-form" class="space-y-4">

            <div class="grid gap-3 md:grid-cols-[1fr_auto] md:items-start md:gap-4">

                {{-- Campo de Input --}}
                <div class="w-full">
                    <x-forms.input id="consult-equipment-identifier" name="equipment_identifier"
                        label="Patrimônio ou Número de Série" placeholder="Digite o patrimônio ou número de série"
                        autocomplete="off" required />
                </div>

                {{-- Botão de Submissão Alinhado --}}
                <div class="w-full md:w-auto">

                    {{-- Label "fantasma" para reservar a mesma altura do label do input (só em md+) --}}
                    <label class="mb-1.5 hidden text-sm font-medium text-transparent select-none md:block">
                        &nbsp;
                    </label>

                    <x-buttons.primary id="btn-search-equipment" type="submit"
                        class="inline-flex h-[42px] w-full items-center justify-center gap-2 px-6 text-sm font-medium transition-all md:w-auto">
                        {{-- Spinner de carregamento --}}
                        <svg id="search-spinner" class="hidden h-4 w-4 animate-spin text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        {{-- Ícone de Lupa --}}
                        <svg id="search-icon" class="h-4 w-4 shrink-0 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>

                        <span>Consultar</span>
                    </x-buttons.primary>
                </div>

            </div>

        </form>

        {{-- Feedback visual --}}
        <div id="equipment-search-message"
            class="mt-4 hidden rounded-lg border border-slate-200 bg-slate-50 p-3 text-xs text-slate-700 sm:text-sm">
        </div>

    </div>

</x-cards.card>
