{{-- ============================================================
    RESUMO DAS PREVENTIVAS
============================================================= --}}

<div class="grid grid-cols-2 gap-2.5 sm:gap-4 md:grid-cols-3 lg:grid-cols-5">

    {{-- TODAS --}}

    <a
        href="{{ route('preventivas.index', request()->except(['status', 'page'])) }}"
        @class([
            'block rounded-xl transition active:scale-[0.98]',
            'ring-2 ring-slate-500 ring-offset-2' => !request('status'),
        ])
    >
        <x-cards.card
            class="h-full border border-slate-200 p-3 transition hover:shadow-md sm:p-5"
        >
            <div class="flex items-center justify-between gap-1.5 sm:gap-2">

                <div class="min-w-0">
                    <p class="truncate text-[10px] font-semibold uppercase tracking-wider text-slate-500 sm:text-xs">
                        Todas
                    </p>

                    <p class="mt-0.5 text-xl font-bold text-slate-700 sm:mt-1 sm:text-3xl">
                        {{ $newCount + $inProgressCount + $pendingApprovalCount + $approvedCount }}
                    </p>

                    <p class="mt-1 hidden text-xs text-slate-500 sm:block">
                        Todas as preventivas
                    </p>
                </div>

                <div class="shrink-0 rounded-lg border border-slate-200 bg-slate-50 p-2 text-slate-600 sm:p-3">
                    <svg
                        class="h-4 w-4 sm:h-6 sm:w-6"
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
        </x-cards.card>
    </a>


    @foreach ($statusFilters as $filter)

        @php
            $status = $filter['status'];

            $params = request()->except('page');

            if (request('status') === $status->value) {
                unset($params['status']);
            } else {
                $params['status'] = $status->value;
            }

            $url = route('preventivas.index', $params);

            $colorClasses = match ($status) {
                \App\Enums\StatusPreventiveEnum::NEW => [
                    'text' => 'text-blue-600',
                    'ring' => 'ring-blue-500',
                    'bg' => 'bg-blue-50',
                    'border' => 'border-blue-100',
                ],

                \App\Enums\StatusPreventiveEnum::IN_PROGRESS => [
                    'text' => 'text-amber-600',
                    'ring' => 'ring-amber-500',
                    'bg' => 'bg-amber-50',
                    'border' => 'border-amber-100',
                ],

                \App\Enums\StatusPreventiveEnum::PENDING_APPROVAL => [
                    'text' => 'text-indigo-600',
                    'ring' => 'ring-indigo-500',
                    'bg' => 'bg-indigo-50',
                    'border' => 'border-indigo-100',
                ],

                \App\Enums\StatusPreventiveEnum::APPROVED => [
                    'text' => 'text-emerald-600',
                    'ring' => 'ring-emerald-500',
                    'bg' => 'bg-emerald-50',
                    'border' => 'border-emerald-100',
                ],
            };

            $isActive = request('status') === $status->value;
        @endphp

        <a
            href="{{ $url }}"
            @class([
                'block rounded-xl transition active:scale-[0.98]',
                $colorClasses['ring'] . ' ring-2 ring-offset-2' => $isActive,
            ])
        >
            <x-cards.card
                class="h-full border border-slate-200 p-3 transition hover:shadow-md sm:p-5"
            >
                <div class="flex items-center justify-between gap-1.5 sm:gap-2">

                    <div class="min-w-0">
                        <p class="truncate text-[10px] font-semibold uppercase tracking-wider text-slate-500 sm:text-xs">
                            {{ $filter['label'] }}
                        </p>

                        <p class="mt-0.5 text-xl font-bold {{ $colorClasses['text'] }} sm:mt-1 sm:text-3xl">
                            {{ $filter['count'] }}
                        </p>

                        <p class="mt-1 hidden text-xs text-slate-500 sm:block">
                            {{ $filter['description'] }}
                        </p>
                    </div>

                    <div
                        @class([
                            'shrink-0 rounded-lg border p-2 sm:p-3',
                            $colorClasses['bg'],
                            $colorClasses['border'],
                            $colorClasses['text'],
                        ])
                    >
                        <svg
                            class="h-4 w-4 sm:h-6 sm:w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            @if ($status === \App\Enums\StatusPreventiveEnum::NEW)

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />

                            @elseif ($status === \App\Enums\StatusPreventiveEnum::IN_PROGRESS)

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3M12 3a9 9 0 100 18 9 9 0 000-18z"
                                />

                            @elseif ($status === \App\Enums\StatusPreventiveEnum::PENDING_APPROVAL)

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"
                                />

                            @elseif ($status === \App\Enums\StatusPreventiveEnum::APPROVED)

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />

                            @endif
                        </svg>
                    </div>

                </div>
            </x-cards.card>
        </a>

    @endforeach

</div>
