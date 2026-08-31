{{-- ============================================================
    RESUMO DAS PREVENTIVAS
============================================================= --}}

<div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:grid-cols-3 xl:grid-cols-5">

    {{-- ========================================================
        TODAS
    ========================================================= --}}

    <a
        href="{{ route('preventivas.execucao.index') }}"
        class="overflow-hidden rounded-xl border bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md
            {{ !request('status')
                ? 'border-slate-500 ring-2 ring-slate-500/20'
                : 'border-gray-200' }}"
    >
        <div class="flex items-center justify-between p-4 sm:p-5">

            <div>

                <p class="text-xs font-medium uppercase tracking-wider text-gray-500 sm:text-sm sm:normal-case">
                    Todas
                </p>

                <p class="mt-1 text-2xl font-semibold text-slate-700 sm:text-3xl">
                    {{ $totalCount }}
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Minhas preventivas
                </p>

            </div>

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-100 sm:h-12 sm:w-12">

                <svg
                    class="h-5 w-5 text-slate-600 sm:h-6 sm:w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>

            </div>

        </div>
    </a>


    {{-- ========================================================
        NOVAS
    ========================================================= --}}

    <a
        href="{{ route('preventivas.execucao.index', ['status' => 'new']) }}"
        class="overflow-hidden rounded-xl border bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md
            {{ request('status') === 'new'
                ? 'border-blue-500 ring-2 ring-blue-500/20'
                : 'border-gray-200' }}"
    >
        <div class="flex items-center justify-between p-4 sm:p-5">

            <div>

                <p class="text-xs font-medium uppercase tracking-wider text-gray-500 sm:text-sm sm:normal-case">
                    Novas
                </p>

                <p class="mt-1 text-2xl font-semibold text-blue-600 sm:text-3xl">
                    {{ $newCount }}
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Aguardando início
                </p>

            </div>

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 sm:h-12 sm:w-12">

                <svg
                    class="h-5 w-5 text-blue-600 sm:h-6 sm:w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>

            </div>

        </div>
    </a>


    {{-- ========================================================
        EM ANDAMENTO
    ========================================================= --}}

    <a
        href="{{ route('preventivas.execucao.index', ['status' => 'in_progress']) }}"
        class="overflow-hidden rounded-xl border bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md
            {{ request('status') === 'in_progress'
                ? 'border-orange-500 ring-2 ring-orange-500/20'
                : 'border-gray-200' }}"
    >
        <div class="flex items-center justify-between p-4 sm:p-5">

            <div>

                <p class="text-xs font-medium uppercase tracking-wider text-gray-500 sm:text-sm sm:normal-case">
                    Em andamento
                </p>

                <p class="mt-1 text-2xl font-semibold text-orange-600 sm:text-3xl">
                    {{ $inProgressCount }}
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Execuções em andamento
                </p>

            </div>

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-orange-100 sm:h-12 sm:w-12">

                <svg
                    class="h-5 w-5 text-orange-600 sm:h-6 sm:w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>

            </div>

        </div>
    </a>


    {{-- ========================================================
        AGUARDANDO VALIDAÇÃO
    ========================================================= --}}

    <a
        href="{{ route('preventivas.execucao.index', ['status' => 'pending_approval']) }}"
        class="overflow-hidden rounded-xl border bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md
            {{ request('status') === 'pending_approval'
                ? 'border-amber-500 ring-2 ring-amber-500/20'
                : 'border-gray-200' }}"
    >
        <div class="flex items-center justify-between p-4 sm:p-5">

            <div>

                <p class="text-xs font-medium uppercase tracking-wider text-gray-500 sm:text-sm sm:normal-case">
                    Aguardando validação
                </p>

                <p class="mt-1 text-2xl font-semibold text-amber-600 sm:text-3xl">
                    {{ $pendingApprovalCount }}
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Aguardando o gestor
                </p>

            </div>

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 sm:h-12 sm:w-12">

                <svg
                    class="h-5 w-5 text-amber-600 sm:h-6 sm:w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-.64-.05-1.27-.146-1.888z"
                    />
                </svg>

            </div>

        </div>
    </a>


    {{-- ========================================================
        FINALIZADAS
    ========================================================= --}}

    <a
        href="{{ route('preventivas.execucao.index', ['status' => 'approved']) }}"
        class="overflow-hidden rounded-xl border bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md
            {{ request('status') === 'approved'
                ? 'border-emerald-500 ring-2 ring-emerald-500/20'
                : 'border-gray-200' }}"
    >
        <div class="flex items-center justify-between p-4 sm:p-5">

            <div>

                <p class="text-xs font-medium uppercase tracking-wider text-gray-500 sm:text-sm sm:normal-case">
                    Finalizadas
                </p>

                <p class="mt-1 text-2xl font-semibold text-emerald-600 sm:text-3xl">
                    {{ $approvedCount }}
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Validadas pelo gestor
                </p>

            </div>

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 sm:h-12 sm:w-12">

                <svg
                    class="h-5 w-5 text-emerald-600 sm:h-6 sm:w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                    />
                </svg>

            </div>

        </div>
    </a>

</div>
