@php
    // Verifica se alguma rota do módulo de segurança/usuários está ativa para abrir a gaveta automaticamente
    $isOpen = request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*');
@endphp

<div class="sidebar-group">
    {{-- Botão Principal do Módulo Segurança --}}
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
            {{-- Ícone do Módulo Segurança (Escudo/Cadeado) --}}
            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <span class="font-semibold">Segurança</span>
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
        {{-- Grupo: Administração --}}
        <div>
            <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                Administração
            </p>

            <div class="space-y-1">
                <a
                    href="{{ route('users.index') }}"
                    @class([
                        $linkClass,
                        $activeClass => request()->routeIs('users.*'),
                        $inactiveClass => !request()->routeIs('users.*'),
                    ])
                >
                    👥 Usuários
                </a>
            </div>
        </div>
    </div>
</div>
