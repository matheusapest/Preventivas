@php
    // Verifica se alguma rota de relatórios está ativa para abrir o módulo automaticamente
    $isOpen = request()->routeIs('relatorios.*');
@endphp

<div class="sidebar-group">
    {{-- Botão Principal do Módulo Relatórios --}}
    <button
        type="button"
        class="
            sidebar-group-toggle
            flex
            w-full
            items-center
            justify-between
            rounded-xl
            px-4
            py-3
            text-sm
            font-medium
            text-slate-300
            transition
            hover:bg-slate-800
            hover:text-white
        "
        aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
    >
        <div class="flex items-center gap-3">
            {{-- Ícone do Módulo Relatórios --}}
            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="font-semibold">Relatórios</span>
        </div>

        {{-- Seta Indicadora --}}
        <svg
            @class([
                'sidebar-chevron h-4 w-4 transition-transform duration-200 text-slate-400',
                'rotate-180' => $isOpen,
            ])
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    {{-- Conteúdo Expansível / Submenu --}}
    <div
        @class([
            'sidebar-group-content pl-4 pr-2 py-2 space-y-4',
            'hidden' => !$isOpen,
        ])
    >
        {{-- Grupo: Operacionais --}}
        <div>
            <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                Operacionais
            </p>

            <div class="space-y-1">
                <a
                    href="#"
                    @class([
                        $linkClass,
                        $inactiveClass,
                    ])
                >
                    📊 Relatórios
                </a>
            </div>
        </div>
    </div>
</div>
