<p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">
    Programação
</p>

<div class="space-y-1">

    {{-- Painel de Preventivas --}}
    <a
        href="{{ route('preventivas.index') }}"
        @class([
            $linkClass,
            'flex items-center gap-2.5',
            $activeClass => request()->routeIs('preventivas.index'),
            $inactiveClass => !request()->routeIs('preventivas.index'),
        ])
    >
        <svg
            class="h-4 w-4 shrink-0 text-slate-400"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
            />
        </svg>

        <span>Painel de Preventivas</span>
    </a>

</div>
