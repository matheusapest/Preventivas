@php
    // Verifica se alguma rota de reparo externo está ativa para abrir o subgrupo
    $isReparoOpen = request()->routeIs('reparos_externos.*');

    // Verifica se alguma tela de recebimento está ativa
    $isRecebimentoOpen = request()->routeIs(
        'reparos_externos.recebimentos.*',
        'reparos_externos.receber.*'
    );
@endphp

<div class="sidebar-group">

    {{-- Botão Principal do Submódulo Reparo Externo --}}
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
            py-2.5
            text-sm
            font-medium
            text-slate-300
            transition
            hover:bg-slate-800
            hover:text-white
        "
        aria-expanded="{{ $isReparoOpen ? 'true' : 'false' }}"
    >
        <div class="flex items-center gap-3">

            <svg
                class="h-4 w-4 text-slate-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M11 4a2 2 0 114 0v1a2 2 0 01-2 2 2 2 0 01-2-2V4zM4 8a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V8z"
                />
            </svg>

            <span class="font-medium">
                Reparo Externo
            </span>

        </div>

        {{-- Seta Indicadora --}}
        <svg
            @class([
                'sidebar-chevron h-3.5 w-3.5 transition-transform duration-200 text-slate-400',
                'rotate-180' => $isReparoOpen,
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


    {{-- Submenu do Reparo Externo --}}
    <div
        @class([
            'sidebar-group-content pl-4 pr-2 py-1 space-y-1',
            'hidden' => !$isReparoOpen,
        ])
    >

        {{-- Painel --}}
        <a
            href="{{ route('reparos_externos.index') }}"
            @class([
                $linkClass,
                $activeClass => request()->routeIs('reparos_externos.index'),
                $inactiveClass => !request()->routeIs('reparos_externos.index'),
            ])
        >
            📊 Painel de OS
        </a>


        {{-- Enviar Equipamento --}}
        <a
            href="{{ route('reparos_externos.create') }}"
            @class([
                $linkClass,
                $activeClass => request()->routeIs('reparos_externos.create'),
                $inactiveClass => !request()->routeIs('reparos_externos.create'),
            ])
        >
            📤 Enviar Equipamento
        </a>


        {{-- Grupo Recebimentos --}}
        <div class="sidebar-group">

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
                    py-2.5
                    text-sm
                    font-medium
                    text-slate-300
                    transition
                    hover:bg-slate-800
                    hover:text-white
                "
                aria-expanded="{{ $isRecebimentoOpen ? 'true' : 'false' }}"
            >

                <span class="flex items-center gap-3">
                    📥
                    <span>
                        Recebimentos
                    </span>
                </span>

                <svg
                    @class([
                        'sidebar-chevron h-3.5 w-3.5 transition-transform duration-200 text-slate-400',
                        'rotate-180' => $isRecebimentoOpen,
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


            {{-- Submenu de Recebimentos --}}
            <div
                @class([
                    'sidebar-group-content ml-4 pl-3 pr-1 py-1 space-y-1 border-l border-slate-700',
                    'hidden' => !$isRecebimentoOpen,
                ])
            >

                {{-- Recebimentos Pendentes --}}
                <a
                    href="{{ route('reparos_externos.recebimentos.index') }}"
                    @class([
                        $linkClass,
                        $activeClass => request()->routeIs('reparos_externos.recebimentos.index'),
                        $inactiveClass => !request()->routeIs('reparos_externos.recebimentos.index'),
                    ])
                >
                    Pendentes
                </a>


                {{-- Receber Equipamentos --}}
                <a
                    href="{{ route('reparos_externos.recebimentos.multiplos') }}"
                    @class([
                        $linkClass,
                        $activeClass => request()->routeIs('reparos_externos.recebimentos.multiplos'),
                        $inactiveClass => !request()->routeIs('reparos_externos.recebimentos.multiplos'),
                    ])
                >
                    Receber Equipamentos
                </a>

            </div>

        </div>

    </div>

</div>
