@php
    // Verifica se qualquer rota referente à Operação está ativa para abrir a gaveta principal automaticamente
    $isOpen = request()->routeIs('equipamentos.*')
        || request()->routeIs('transferencias.*')
        || request()->routeIs('reparo-externo.*');
@endphp

<div class="sidebar-group">
    {{-- Botão Principal do Módulo Operação --}}
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
    {{-- Ícone do Módulo Ativos (Desktop / Monitor) --}}
    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
    </svg>
    <span class="font-semibold">Ativos</span>
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
            'sidebar-group-content pl-4 pr-2 py-2 space-y-3',
            'hidden' => !$isOpen,
        ])
    >
        {{-- Item Direto: Equipamentos --}}
        <div>
            <a
                href="{{ route('equipamentos.index') }}"
                @class([
                    $linkClass,
                    $activeClass => request()->routeIs('equipamentos.*'),
                    $inactiveClass => !request()->routeIs('equipamentos.*'),
                ])
            >
                💻 Equipamentos
            </a>
        </div>

        {{-- Bloco: Transferências --}}
        <div class="border-t border-slate-800/60 pt-2">
            @include('layout.partials.sidebar.transferencias')
        </div>

        {{-- Bloco: Reparo Externo (Submódulo Oculto) --}}
        <div class="border-t border-slate-800/60 pt-2">
            @include('layout.partials.sidebar.reparo-externo')
        </div>
    </div>
</div>
