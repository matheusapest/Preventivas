<p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">
    Configurações
</p>

<div class="space-y-1">

    {{-- Tipos de Unidade --}}
    <a
        href="{{ route('configuracoes.tipos-unidade.index') }}"
        @class([
            $linkClass,
            'flex items-center gap-2.5',
            $activeClass => request()->routeIs('configuracoes.tipos-unidade.*'),
            $inactiveClass => !request()->routeIs('configuracoes.tipos-unidade.*'),
        ])
    >
        <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        <span>Tipos de Unidade</span>
    </a>

    {{-- Perfis Operacionais --}}
    <a
        href="{{ route('configuracoes.perfis-operacionais.index') }}"
        @class([
            $linkClass,
            'flex items-center gap-2.5',
            $activeClass => request()->routeIs('configuracoes.perfis-operacionais.*'),
            $inactiveClass => !request()->routeIs('configuracoes.perfis-operacionais.*'),
        ])
    >
        <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span>Perfis Operacionais</span>
    </a>

    {{-- Unidades Operacionais --}}
    <a
        href="{{ route('configuracoes.unidades-operacionais.index') }}"
        @class([
            $linkClass,
            'flex items-center gap-2.5',
            $activeClass => request()->routeIs('configuracoes.unidades-operacionais.*'),
            $inactiveClass => !request()->routeIs('configuracoes.unidades-operacionais.*'),
        ])
    >
        <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span>Unidades Operacionais</span>
    </a>

    {{-- Tipos de Preventiva --}}
    <a
        href="{{ route('configuracoes.tipos-preventivas.index') }}"
        @class([
            $linkClass,
            'flex items-center gap-2.5',
            $activeClass => request()->routeIs('configuracoes.tipos-preventivas.*'),
            $inactiveClass => !request()->routeIs('configuracoes.tipos-preventivas.*'),
        ])
    >
        <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10M7 17h10" />
        </svg>
        <span>Tipos de Preventiva</span>
    </a>

    {{-- Perfis de Preventiva --}}
    <a
        href="{{ route('configuracoes.perfis-preventivas.index') }}"
        @class([
            $linkClass,
            'flex items-center gap-2.5',
            $activeClass => request()->routeIs('configuracoes.perfis-preventivas.*'),
            $inactiveClass => !request()->routeIs('configuracoes.perfis-preventivas.*'),
        ])
    >
        <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <span>Perfis de Preventiva</span>
    </a>

</div>
