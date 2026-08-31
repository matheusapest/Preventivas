<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

    {{-- FILIAIS VINCULADAS --}}
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">

        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 sm:text-xs">
            Filiais Vinculadas
        </span>

        <div class="mt-2 flex items-baseline gap-2">

            <span class="text-2xl font-bold text-slate-900 sm:text-3xl">
                {{ $totalBranches }}
            </span>

        </div>

        <p class="mt-1 text-xs text-slate-500 sm:text-sm">
            Total de filiais associadas a este perfil.
        </p>

    </div>


    {{-- CONFIGURADAS --}}
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">

        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 sm:text-xs">
            Configuradas
        </span>

        <div class="mt-2 flex items-baseline gap-2">

            <span class="text-2xl font-bold text-emerald-600 sm:text-3xl">
                {{ $configuredBranches }}
            </span>

        </div>

        <p class="mt-1 text-xs text-slate-500 sm:text-sm">
            Filiais com regra ALL ativa.
        </p>

    </div>


    {{-- PENDENTES --}}
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5 sm:col-span-2 lg:col-span-1">

        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 sm:text-xs">
            Pendentes
        </span>

        <div class="mt-2 flex items-baseline gap-2">

            <span class="text-2xl font-bold text-amber-600 sm:text-3xl">
                {{ $pendingBranches }}
            </span>

        </div>

        <p class="mt-1 text-xs text-slate-500 sm:text-sm">
            Filiais sem regra configurada.
        </p>

    </div>

</div>
