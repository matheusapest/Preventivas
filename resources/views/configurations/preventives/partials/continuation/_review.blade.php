{{-- ============================================================
     INFORMAÇÕES DA REPROVAÇÃO
============================================================= --}}

<div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

    <div class="mb-3 flex items-center justify-between gap-3">

        <div>
            <h2 class="text-sm font-semibold text-red-900">
                Continuidade após reprovação
            </h2>

            <p class="mt-1 text-xs text-red-700">
                O Ciclo abaixo foi finalizado e reprovado pelo gestor.
            </p>
        </div>

        <span class="shrink-0 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
            Ciclo {{ $cycle->sequence }}
        </span>

    </div>

    <div class="grid gap-3 sm:grid-cols-2">

        <div class="rounded-lg border border-red-100 bg-white p-3">

            <p class="text-xs font-medium text-slate-500">
                Status do Ciclo
            </p>

            <p class="mt-1 text-sm font-semibold text-slate-800">
                {{ $cycle->status->label() }}
            </p>

        </div>

        <div class="rounded-lg border border-red-100 bg-white p-3">

            <p class="text-xs font-medium text-slate-500">
                Status de revisão do ciclo
            </p>

            <p class="mt-1 text-sm font-semibold text-red-700">
                {{ $cycle->review_status->label() }}
            </p>

        </div>

    </div>

    @if ($reviewObservation)

        <div class="mt-4 rounded-lg border border-red-100 bg-white p-3">

            <p class="text-xs font-medium text-slate-500">
                Motivo da reprovação
            </p>

            <p class="mt-1 whitespace-pre-line text-sm text-slate-700">
                {{ $reviewObservation }}
            </p>

        </div>

    @endif

</div>
