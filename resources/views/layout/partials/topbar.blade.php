<div
    id="topbar"
    class="
        relative
        flex
        h-20
        w-full
        items-center
        justify-between
        gap-4
        border-b
        border-slate-200
        bg-white
        px-8
        transition-all
        duration-300

        max-lg:h-16
        max-lg:px-4
        max-lg:gap-2
    "
>
    {{-- Botão Sanduíche (Apenas Mobile/Tablet < 1024px) --}}
    <button
        id="mobile-menu-button"
        type="button"
        class="
            hidden
            h-10
            w-10
            shrink-0
            items-center
            justify-center
            rounded-lg
            text-slate-700
            transition
            hover:bg-slate-100
            active:bg-slate-200

            max-lg:flex
        "
        aria-label="Abrir menu"
        aria-expanded="false"
    >
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    {{-- Identificação do sistema (Mostra APENAS no mobile/tablet) --}}
    <div
        id="topbar-brand"
        class="
            flex
            min-w-0
            items-center
            gap-3
            mr-auto

            lg:hidden
        "
    >
        <img src="{{ asset('images/preventivas.png') }}" alt="Preventivas Logo" class="h-8 w-auto">

        <div class="min-w-0">
            <p id="topbar-brand-name" class="truncate text-sm font-bold leading-tight text-slate-800">
                Preventivas
            </p>
            <p id="topbar-brand-description" class="truncate text-xs text-slate-500">
                Sistema de Gestão
            </p>
        </div>
    </div>

    {{-- Usuário / Ações da Direita --}}
    <div
        id="topbar-user"
        class="
            ml-auto
            flex
            shrink-0
            items-center
            gap-4

            max-lg:gap-2
        "
    >
        {{-- Avatar --}}
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-600 font-bold text-white max-lg:h-9 max-lg:w-9 max-lg:text-sm">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        {{-- Informações do usuário --}}
        <div class="text-right max-lg:hidden">
            <p class="font-semibold text-slate-800">
                {{ auth()->user()->name }}
            </p>
            <p class="text-sm text-slate-500">
                {{ auth()->user()->role->name ?? 'Usuário' }}
            </p>
        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700 active:bg-red-800 max-lg:px-3 max-lg:py-1.5 max-lg:text-xs"
            >
                Sair
            </button>
        </form>
    </div>
</div>
