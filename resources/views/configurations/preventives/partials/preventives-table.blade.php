{{-- resources/views/configurations/preventives/partials/preventives-table.blade.php --}}

<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- HEADER --}}
    <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

        <div>
            <h2 class="text-xs font-semibold text-slate-800 sm:text-base">
                Preventivas
            </h2>

            <p class="mt-0.5 text-[11px] text-slate-500 sm:text-sm">
                Lista de preventivas cadastradas no sistema.
            </p>
        </div>

        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 sm:text-sm">
            {{ $preventives->total() }}

            <span class="hidden sm:inline">
                preventiva(s)
            </span>
        </span>

    </div>


    {{-- ============================================================
         VISÃO MOBILE / TABLET
         ============================================================ --}}

    <div class="block divide-y divide-slate-100 lg:hidden">

        @forelse ($preventives as $preventive)

            @php

                $status = $preventive->status;

                $statusClasses = match ($status) {

                    \App\Enums\StatusPreventiveEnum::NEW
                        => 'bg-blue-50 text-blue-700 border-blue-200',

                    \App\Enums\StatusPreventiveEnum::IN_PROGRESS
                        => 'bg-amber-50 text-amber-700 border-amber-200',

                    \App\Enums\StatusPreventiveEnum::PENDING_APPROVAL
                        => 'bg-indigo-50 text-indigo-700 border-indigo-200',

                    \App\Enums\StatusPreventiveEnum::APPROVED
                        => 'bg-emerald-50 text-emerald-700 border-emerald-200',

                    default
                        => 'bg-slate-50 text-slate-700 border-slate-200',
                };

                $currentCycle =
                    $preventive->current_cycle_model ?? null;

                $isRejected =
                    $currentCycle
                    && $currentCycle->review_status ===
                        \App\Enums\CycleReviewStatusEnum::REJECTED;

                $isApproved =
                    $currentCycle
                    && $currentCycle->review_status ===
                        \App\Enums\CycleReviewStatusEnum::APPROVED;

                /*
                 * Evita exibir "Reprovado" novamente caso a
                 * observação seja exatamente esse texto.
                 */
                $reviewObservation = trim(
                    (string) (
                        $currentCycle?->review_observation ?? ''
                    )
                );

                $showReviewObservation =
                    $reviewObservation !== ''
                    && mb_strtolower(
                        $reviewObservation
                    ) !== 'reprovado';

            @endphp


            <div class="space-y-3 p-3.5 sm:p-4">

                {{-- ====================================================
                     LINHA SUPERIOR
                     ==================================================== --}}

                <div class="flex items-start justify-between gap-3">

                    <div class="min-w-0">

                        <span class="text-sm font-bold text-slate-900 sm:text-base">
                            #{{ $preventive->id }}
                        </span>

                        @if ($preventive->preventiveType)

                            <p class="truncate text-xs font-medium text-slate-500">
                                {{ $preventive->preventiveType->name }}
                            </p>

                        @endif

                    </div>


                    {{-- STATUS DA PREVENTIVA --}}

                    <span
                        class="inline-flex shrink-0 items-center rounded-full border px-2.5 py-0.5 text-[11px] font-semibold sm:text-xs {{ $statusClasses }}"
                    >
                        {{ $status->label() }}
                    </span>

                </div>


                {{-- ====================================================
                     DADOS PRINCIPAIS
                     ==================================================== --}}

                <div class="grid grid-cols-2 gap-x-3 gap-y-2.5 rounded-lg border border-slate-100 bg-slate-50/75 p-3 text-xs">

                    {{-- FILIAL --}}

                    <div>

                        <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                            Filial
                        </span>

                        <span class="block truncate text-xs font-medium text-slate-800">
                            {{ $preventive->branch->name ?? '-' }}
                        </span>

                    </div>


                    {{-- RESPONSÁVEL --}}

                    <div>

                        <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                            Responsável
                        </span>

                        <span class="block truncate text-xs font-medium text-slate-800">
                            {{ $preventive->assignedUser->name ?? '-' }}
                        </span>

                    </div>


                    {{-- CICLO --}}

                    <div>

                        <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                            Ciclo atual
                        </span>

                        <span class="block text-xs font-semibold text-slate-700">
                            {{ $preventive->current_cycle ?? '-' }}
                        </span>

                    </div>


                    {{-- CRIADA EM --}}

                    <div>

                        <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                            Criada em
                        </span>

                        <span class="block text-xs font-medium text-slate-600">
                            {{ $preventive->created_at?->format('d/m/Y H:i') ?? '-' }}
                        </span>

                    </div>


                    {{-- INÍCIO --}}

                    <div>

                        <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                            Início
                        </span>

                        <span class="block text-xs font-medium text-slate-600">
                            {{ $preventive->start_at?->format('d/m/Y H:i') ?? '—' }}
                        </span>

                    </div>


                    {{-- REVISÃO --}}

                    <div>

                        <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                            Revisão
                        </span>

                        @if ($currentCycle?->review_status)

                            <span
                                @class([
                                    'text-xs font-semibold',

                                    'text-red-600' =>
                                        $currentCycle->review_status ===
                                        \App\Enums\CycleReviewStatusEnum::REJECTED,

                                    'text-emerald-600' =>
                                        $currentCycle->review_status ===
                                        \App\Enums\CycleReviewStatusEnum::APPROVED,

                                    'text-slate-600' =>
                                        ! in_array(
                                            $currentCycle->review_status,
                                            [
                                                \App\Enums\CycleReviewStatusEnum::REJECTED,
                                                \App\Enums\CycleReviewStatusEnum::APPROVED,
                                            ],
                                            true
                                        ),
                                ])
                            >
                                {{ $currentCycle->review_status->label() }}
                            </span>

                        @else

                            <span class="text-xs font-medium text-slate-500">
                                —
                            </span>

                        @endif

                    </div>

                </div>


                {{-- ====================================================
                     OBSERVAÇÃO DA REVISÃO
                     ==================================================== --}}

                @if ($isRejected && $showReviewObservation)

                    <div class="rounded-lg border border-red-100 bg-red-50 p-2.5">

                        <span class="block text-[10px] font-semibold uppercase tracking-wider text-red-500">
                            Motivo da reprovação
                        </span>

                        <p class="mt-0.5 line-clamp-3 text-xs leading-relaxed text-red-800">
                            {{ $reviewObservation }}
                        </p>

                    </div>

                @endif


                {{-- ====================================================
                     AÇÕES MOBILE
                     ==================================================== --}}

                <div class="grid grid-cols-2 gap-2 pt-1 sm:flex sm:flex-wrap sm:justify-end">

                    {{-- VISUALIZAR --}}

                    <a
                        href="{{ route('preventivas.show', ['preventive' => $preventive->id]) }}"
                        class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-center text-xs font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 active:scale-[0.98] sm:flex-none"
                    >
                        Visualizar
                    </a>


                    {{-- EDITAR --}}

                    @can('update', $preventive)

                        <a
                            href="#"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-center text-xs font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 active:scale-[0.98] sm:flex-none"
                        >
                            Editar
                        </a>

                    @endcan


                    {{-- VALIDAR --}}

                    @if (
                        $preventive->status ===
                        \App\Enums\StatusPreventiveEnum::PENDING_APPROVAL
                    )

                        @can('validate', $preventive)

                            <a
                                href="{{ route('preventivas.validation', ['preventive' => $preventive->id]) }}"
                                class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-3 py-2 text-center text-xs font-medium text-white shadow-sm transition hover:bg-gray-700 active:scale-[0.98] sm:flex-none"
                            >
                                Validar
                            </a>

                        @endcan

                    @endif


                    {{-- NOVO CICLO --}}

                    @if ($preventive->can_continue)

                        <a
                            href="{{ route('preventivas.continuation', ['preventive' => $preventive->id]) }}"
                            class="inline-flex items-center justify-center rounded-lg bg-amber-600 px-3 py-2 text-center text-xs font-medium text-white shadow-sm transition hover:bg-amber-700 active:scale-[0.98] sm:flex-none"
                        >
                            Novo Ciclo
                        </a>

                    @endif

                </div>

            </div>

        @empty

            <div class="p-8 text-center">

                <p class="text-sm font-medium text-slate-800">
                    Nenhuma preventiva encontrada
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Ainda não existem preventivas cadastradas.
                </p>

            </div>

        @endforelse

    </div>


    {{-- ============================================================
         VISÃO DESKTOP
         ============================================================ --}}

    <div class="hidden overflow-x-auto lg:block">

        <table class="min-w-full divide-y divide-slate-200">

            <thead class="bg-slate-50">

                <tr>

                    <th
                        scope="col"
                        class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 sm:px-6"
                    >
                        Preventiva
                    </th>

                    <th
                        scope="col"
                        class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                    >
                        Filial
                    </th>

                    <th
                        scope="col"
                        class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                    >
                        Responsável pela execução
                    </th>

                    <th
                        scope="col"
                        class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                    >
                        Status
                    </th>

                    <th
                        scope="col"
                        class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                    >
                        Ciclo
                    </th>

                    <th
                        scope="col"
                        class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                    >
                        Revisão
                    </th>

                    <th
                        scope="col"
                        class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                    >
                        Criada em
                    </th>

                    <th
                        scope="col"
                        class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                    >
                        Início
                    </th>

                    <th
                        scope="col"
                        class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                    >
                        Aprovação
                    </th>

                    <th
                        scope="col"
                        class="px-4 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 sm:px-6"
                    >
                        Ações
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-200 bg-white">

                @forelse ($preventives as $preventive)

                    @php

                        $status = $preventive->status;

                        $statusClasses = match ($status) {

                            \App\Enums\StatusPreventiveEnum::NEW
                                => 'bg-blue-50 text-blue-700 border-blue-200',

                            \App\Enums\StatusPreventiveEnum::IN_PROGRESS
                                => 'bg-amber-50 text-amber-700 border-amber-200',

                            \App\Enums\StatusPreventiveEnum::PENDING_APPROVAL
                                => 'bg-indigo-50 text-indigo-700 border-indigo-200',

                            \App\Enums\StatusPreventiveEnum::APPROVED
                                => 'bg-emerald-50 text-emerald-700 border-emerald-200',

                            default
                                => 'bg-slate-50 text-slate-700 border-slate-200',
                        };

                        $currentCycle =
                            $preventive->current_cycle_model ?? null;

                        $isRejected =
                            $currentCycle
                            && $currentCycle->review_status ===
                                \App\Enums\CycleReviewStatusEnum::REJECTED;

                        $isApproved =
                            $currentCycle
                            && $currentCycle->review_status ===
                                \App\Enums\CycleReviewStatusEnum::APPROVED;

                        $reviewObservation = trim(
                            (string) (
                                $currentCycle?->review_observation ?? ''
                            )
                        );

                        $showReviewObservation =
                            $reviewObservation !== ''
                            && mb_strtolower(
                                $reviewObservation
                            ) !== 'reprovado';

                    @endphp


                    <tr class="transition hover:bg-slate-50">

                        {{-- PREVENTIVA --}}

                        <td class="whitespace-nowrap px-4 py-4 sm:px-6">

                            <div class="flex flex-col">

                                <span class="text-sm font-semibold text-slate-900">
                                    #{{ $preventive->id }}
                                </span>

                                @if ($preventive->preventiveType)

                                    <span class="mt-0.5 text-xs text-slate-500">
                                        {{ $preventive->preventiveType->name }}
                                    </span>

                                @endif

                            </div>

                        </td>


                        {{-- FILIAL --}}

                        <td class="whitespace-nowrap px-4 py-4">

                            <span class="text-sm font-medium text-slate-700">
                                {{ $preventive->branch->name ?? '-' }}
                            </span>

                        </td>


                        {{-- RESPONSÁVEL --}}

                        <td class="whitespace-nowrap px-4 py-4">

                            <span class="text-sm text-slate-700">
                                {{ $preventive->assignedUser->name ?? '-' }}
                            </span>

                        </td>


                        {{-- STATUS --}}

                        <td class="whitespace-nowrap px-4 py-4">

                            <span
                                class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $statusClasses }}"
                            >
                                {{ $status->label() }}
                            </span>

                        </td>


                        {{-- CICLO --}}

                        <td class="whitespace-nowrap px-4 py-4">

                            <span class="text-sm font-semibold text-slate-800">
                                {{ $preventive->current_cycle ?? '—' }}
                            </span>

                        </td>


                        {{-- REVISÃO --}}

                        <td class="px-4 py-4">

                            @if ($currentCycle?->review_status)

                                <div class="flex max-w-xs flex-col">

                                    <span
                                        @class([
                                            'inline-flex w-fit rounded-full px-2.5 py-0.5 text-xs font-semibold',

                                            'bg-red-50 text-red-700' =>
                                                $currentCycle->review_status ===
                                                \App\Enums\CycleReviewStatusEnum::REJECTED,

                                            'bg-emerald-50 text-emerald-700' =>
                                                $currentCycle->review_status ===
                                                \App\Enums\CycleReviewStatusEnum::APPROVED,

                                            'bg-slate-50 text-slate-700' =>
                                                ! in_array(
                                                    $currentCycle->review_status,
                                                    [
                                                        \App\Enums\CycleReviewStatusEnum::REJECTED,
                                                        \App\Enums\CycleReviewStatusEnum::APPROVED,
                                                    ],
                                                    true
                                                ),
                                        ])
                                    >
                                        {{ $currentCycle->review_status->label() }}
                                    </span>


                                    @if ($isRejected && $showReviewObservation)

                                        <span
                                            class="mt-1 max-w-xs truncate text-xs text-slate-500"
                                            title="{{ $reviewObservation }}"
                                        >
                                            {{ $reviewObservation }}
                                        </span>

                                    @endif

                                </div>

                            @else

                                <span class="text-xs text-slate-500">
                                    —
                                </span>

                            @endif

                        </td>


                        {{-- CRIADA EM --}}

                        <td class="whitespace-nowrap px-4 py-4">

                            <span class="text-sm text-slate-600">
                                {{ $preventive->created_at?->format('d/m/Y H:i') ?? '-' }}
                            </span>

                        </td>


                        {{-- INÍCIO --}}

                        <td class="whitespace-nowrap px-4 py-4">

                            <span class="text-sm text-slate-600">
                                {{ $preventive->start_at?->format('d/m/Y H:i') ?? '-' }}
                            </span>

                        </td>


                        {{-- APROVAÇÃO --}}

                        <td class="whitespace-nowrap px-4 py-4">

                            <span class="text-sm text-slate-600">
                                {{ $preventive->approved_at?->format('d/m/Y H:i') ?? '-' }}
                            </span>

                        </td>


                        {{-- AÇÕES --}}

                        <td class="whitespace-nowrap px-4 py-4 text-right sm:px-6">

                            <div class="flex justify-end gap-2">

                                {{-- VISUALIZAR --}}

                                <a
                                    href="{{ route('preventivas.show', ['preventive' => $preventive->id]) }}"
                                    class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50 active:scale-[0.98]"
                                >
                                    Visualizar
                                </a>


                                {{-- EDITAR --}}

                                @can('update', $preventive)

                                    <a
                                        href="#"
                                        class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50 active:scale-[0.98]"
                                    >
                                        Editar
                                    </a>

                                @endcan


                                {{-- VALIDAR --}}

                                @if (
                                    $preventive->status ===
                                    \App\Enums\StatusPreventiveEnum::PENDING_APPROVAL
                                )

                                    @can('validate', $preventive)

                                        <a
                                            href="{{ route('preventivas.validation', ['preventive' => $preventive->id]) }}"
                                            class="inline-flex items-center rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-gray-700 active:scale-[0.98]"
                                        >
                                            Validar
                                        </a>

                                    @endcan

                                @endif


                                {{-- NOVO CICLO --}}

                                @if ($preventive->can_continue)

                                    <a
                                        href="{{ route('preventivas.continuation', ['preventive' => $preventive->id]) }}"
                                        class="inline-flex items-center rounded-lg bg-amber-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-amber-700 active:scale-[0.98]"
                                    >
                                        Novo Ciclo
                                    </a>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="10" class="px-6 py-12 text-center">

                            <div class="flex flex-col items-center justify-center">

                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">

                                    <svg
                                        class="h-6 w-6 text-slate-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4"
                                        />

                                    </svg>

                                </div>

                                <p class="mt-3 text-sm font-medium text-slate-900">
                                    Nenhuma preventiva encontrada
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    Ainda não existem preventivas cadastradas.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAGINATION --}}

    @if ($preventives->hasPages())

        <div class="border-t border-slate-200 px-4 py-3.5 sm:px-6">

            {{ $preventives->links() }}

        </div>

    @endif

</x-cards.card>
