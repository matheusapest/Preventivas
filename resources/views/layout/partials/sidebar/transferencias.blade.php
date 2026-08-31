@php
    // Verifica se alguma rota de transferência está ativa para manter o menu aberto automaticamente
    $isOpen = request()->routeIs('transferencias.*');
@endphp

<div class="sidebar-group">
    {{-- Botão Principal do Módulo --}}
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
            {{-- Ícone do Módulo Transferências --}}
            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
            <span class="font-semibold">Transferencias</span>
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
            'sidebar-group-content pl-4 pr-2 py-2 space-y-1',
            'hidden' => !$isOpen,
        ])
    >
        <a
            href="{{ route('transferencias.index') }}"
            @class([
                $linkClass,
                $activeClass => request()->routeIs('transferencias.index'),
                $inactiveClass => !request()->routeIs('transferencias.index'),
            ])
        >
            📊 Painel
        </a>

        <a
            href="{{ route('transferencias.search') }}"
            @class([
                $linkClass,
                $activeClass => request()->routeIs('transferencias.search'),
                $inactiveClass => !request()->routeIs('transferencias.search'),
            ])
        >
            📋 Consultar Equipamento
        </a>

        <a
            href="{{ route('transferencias.create') }}"
            @class([
                $linkClass,
                $activeClass => request()->routeIs('transferencias.create'),
                $inactiveClass => !request()->routeIs('transferencias.create'),
            ])
        >
            📤 Enviar Equipamento
        </a>

        <a
            href="{{ route('transferencias.receive.index') }}"
            @class([
                $linkClass,
                $activeClass => request()->routeIs('transferencias.receive.*'),
                $inactiveClass => !request()->routeIs('transferencias.receive.*'),
            ])
        >
            📥 Receber Equipamentos
        </a>
    </div>
</div>
