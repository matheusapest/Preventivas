<div
    class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
>

    {{-- Header --}}
    <button
        type="button"
        id="toggle-preventive-quick-actions"
        class="flex w-full items-center justify-between gap-3 px-4 py-3.5 text-left transition hover:bg-slate-50 active:bg-slate-100 sm:gap-4 sm:px-6 sm:py-4"
        aria-expanded="false"
        aria-controls="preventive-quick-actions-content"
    >

        <div class="min-w-0">

            <h2 class="text-xs font-semibold text-slate-800 sm:text-base">
                Ações Rápidas
            </h2>

            <p class="mt-0.5 truncate text-[11px] text-slate-500 sm:text-xs">
                Operações frequentes das preventivas.
            </p>

        </div>

        <div class="flex shrink-0 items-center gap-1.5 sm:gap-2">

            <span
                id="preventive-quick-actions-label"
                class="hidden text-xs font-medium text-slate-400 sm:inline"
            >
                Exibir ações
            </span>

            <svg
                id="preventive-quick-actions-icon"
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
        id="preventive-quick-actions-content"
        class="hidden border-t border-slate-200"
    >

        <div class="grid grid-cols-1 gap-3 p-3.5 sm:p-5">

            @can('create', \App\Models\Preventive\Preventive::class)

                <div
                    class="flex flex-col gap-3 rounded-lg border border-slate-200 p-3.5 transition hover:border-slate-300 hover:bg-slate-50 sm:flex-row sm:items-center sm:justify-between sm:gap-4 sm:p-4"
                >

                    <div class="min-w-0">

                        <h3 class="text-xs font-semibold text-slate-800 sm:text-sm">
                            Criar Preventiva
                        </h3>

                        <p class="mt-0.5 text-[11px] leading-relaxed text-slate-500 sm:text-xs">
                            Crie uma nova preventiva a partir de um perfil configurado.
                        </p>

                    </div>

                    <x-buttons.primary
                        :href="route('preventivas.create')"
                        class="w-full shrink-0 justify-center text-xs sm:w-auto sm:text-sm"
                    >
                        Criar
                    </x-buttons.primary>

                </div>

            @endcan

        </div>

    </div>

</div>
