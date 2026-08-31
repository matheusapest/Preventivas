@php
    // Verifica se alguma rota deste bloco está ativa para manter o menu aberto automaticamente
    $isOpen = request()->routeIs('empresas.*')
        || request()->routeIs('filiais.*')
        || request()->routeIs('categorias.*')
        || request()->routeIs('fabricantes.*')
        || request()->routeIs('modelos-equipamentos.*');
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
            {{-- Ícone do Módulo Cadastros --}}
            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <span class="font-semibold">Cadastros</span>
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
        {{-- Grupo: Organização --}}
        <div>
            <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                Organização
            </p>

            <div class="space-y-1">
                <a
                    href="{{ route('empresas.index') }}"
                    @class([
                        $linkClass,
                        $activeClass => request()->routeIs('empresas.*'),
                        $inactiveClass => !request()->routeIs('empresas.*'),
                    ])
                >
                    🏢 Empresas
                </a>

                <a
                    href="{{ route('filiais.index') }}"
                    @class([
                        $linkClass,
                        $activeClass => request()->routeIs('filiais.*'),
                        $inactiveClass => !request()->routeIs('filiais.*'),
                    ])
                >
                    🏬 Filiais
                </a>
            </div>
        </div>

        {{-- Grupo: Equipamentos --}}
        <div>
            <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                Equipamentos
            </p>

            <div class="space-y-1">
                <a
                    href="{{ route('categorias.index') }}"
                    @class([
                        $linkClass,
                        $activeClass => request()->routeIs('categorias.*'),
                        $inactiveClass => !request()->routeIs('categorias.*'),
                    ])
                >
                    🏷 Categorias
                </a>

                <a
                    href="{{ route('fabricantes.index') }}"
                    @class([
                        $linkClass,
                        $activeClass => request()->routeIs('fabricantes.*'),
                        $inactiveClass => !request()->routeIs('fabricantes.*'),
                    ])
                >
                    🏭 Fabricantes
                </a>

                <a
                    href="{{ route('modelos-equipamentos.index') }}"
                    @class([
                        $linkClass,
                        $activeClass => request()->routeIs('modelos-equipamentos.*'),
                        $inactiveClass => !request()->routeIs('modelos-equipamentos.*'),
                    ])
                >
                    📦 Modelos
                </a>
            </div>
        </div>
    </div>
</div>
