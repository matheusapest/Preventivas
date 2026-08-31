<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 sm:gap-6">

    {{-- Transferências Pendentes --}}
    <x-cards.card class="p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">
                    Transferências Pendentes
                </p>
                <p class="mt-2 text-2xl font-bold text-amber-600 sm:text-3xl">
                    {{ $pendingTransfers ?? 0 }}
                </p>
            </div>

            <div class="rounded-full bg-amber-100 p-3 sm:p-4 shrink-0">
                <svg class="h-6 w-6 text-amber-600 sm:h-8 sm:w-8"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4l3 3M12 3a9 9 0 100 18 9 9 0 000-18z" />
                </svg>
            </div>
        </div>
    </x-cards.card>

    {{-- Enviadas Hoje --}}
    <x-cards.card class="p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">
                    Enviadas Hoje
                </p>
                <p class="mt-2 text-2xl font-bold text-blue-600 sm:text-3xl">
                    {{ $sentToday ?? 0 }}
                </p>
            </div>

            <div class="rounded-full bg-blue-100 p-3 sm:p-4 shrink-0">
                <svg class="h-6 w-6 text-blue-600 sm:h-8 sm:w-8"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </div>
        </div>
    </x-cards.card>

    {{-- Recebidas Hoje --}}
    <x-cards.card class="p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">
                    Recebidas Hoje
                </p>
                <p class="mt-2 text-2xl font-bold text-emerald-600 sm:text-3xl">
                    {{ $receivedToday ?? 0 }}
                </p>
            </div>

            <div class="rounded-full bg-emerald-100 p-3 sm:p-4 shrink-0">
                <svg class="h-6 w-6 text-emerald-600 sm:h-8 sm:w-8"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>
    </x-cards.card>

</div>
