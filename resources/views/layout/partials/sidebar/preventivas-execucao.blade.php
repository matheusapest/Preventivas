<p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">
    Execução
</p>

<div class="space-y-1">

    {{-- Execução de Preventivas --}}
    <a
        href="{{ route('preventivas.execucao.index') }}"
        @class([
            $linkClass,
            'flex items-center gap-2.5',
            $activeClass => request()->routeIs('preventivas.execucao.*'),
            $inactiveClass => !request()->routeIs('preventivas.execucao.*'),
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
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
            />
        </svg>

        <span>Preventivas</span>
    </a>

</div>
