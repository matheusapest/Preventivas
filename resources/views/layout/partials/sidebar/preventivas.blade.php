@php
    $isOpen =
        request()->routeIs('configuracoes.tipos-unidade.*') ||
        request()->routeIs('configuracoes.perfis-operacionais.*') ||
        request()->routeIs('configuracoes.unidades-operacionais.*') ||
        request()->routeIs('configuracoes.tipos-preventivas.*') ||
        request()->routeIs('configuracoes.perfis-preventivas.*') ||
        request()->routeIs('preventivas.programacao.*') ||
        request()->routeIs('preventivas.execucao.*');
@endphp

<div class="sidebar-group">

    {{-- Botão Principal do Módulo Preventivas --}}
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
            {{-- Ícone Escudo (Preventivas) --}}
            <svg
                class="h-5 w-5 text-slate-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                />
            </svg>

            <span class="font-semibold">
                Preventivas
            </span>
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
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
            />
        </svg>
    </button>

    {{-- Conteúdo Expansível / Submenu --}}
    <div
        @class([
            'sidebar-group-content pl-4 pr-2 py-2 space-y-3',
            'hidden' => !$isOpen,
        ])
    >
        {{-- Configurações --}}
        <div>
            @include('layout.partials.sidebar.preventivas-configuracoes')
        </div>

        {{-- Programação --}}
        <div class="border-t border-slate-800/60 pt-3">
            @include('layout.partials.sidebar.preventivas-programacao')
        </div>

        {{-- Execução --}}
        <div class="border-t border-slate-800/60 pt-3">
            @include('layout.partials.sidebar.preventivas-execucao')
        </div>
    </div>

</div>
