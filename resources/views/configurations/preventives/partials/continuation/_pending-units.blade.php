{{-- ============================================================
     UNIDADES PENDENTES DO CYCLE ANTERIOR
============================================================= --}}

@if ($hasPendingUnits)

    <section class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4">

        <div class="mb-4">

            <h2 class="text-sm font-semibold text-amber-900">
                Unidades pendentes do Ciclo anterior
            </h2>

            <p class="mt-1 text-xs leading-5 text-amber-800">
                Estas unidades possuem atividades que não foram concluídas.
                Você pode adicioná-las rapidamente à continuidade.
            </p>

        </div>

        <div
            id="pending-units-list"
            class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3"
        >

            @foreach ($pendingUnits as $unit)

                <div
                    class="rounded-lg border border-amber-200 bg-white p-3"
                    data-pending-unit
                    data-operational-unit-id="{{ $unit['operational_unit_id'] }}"
                >

                    <div class="flex items-start justify-between gap-3">

                        <div class="min-w-0">

                            <p class="truncate text-sm font-semibold text-slate-800">
                                {{ $unit['name'] }}
                            </p>

                            <p class="mt-0.5 text-xs text-slate-500">
                                {{ $unit['identifier'] }}
                            </p>

                        </div>

                        <span
                            class="shrink-0 rounded-full bg-amber-100 px-2 py-1 text-[10px] font-semibold text-amber-800"
                        >
                            {{ $unit['answered_activities'] }}/{{ $unit['total_activities'] }}
                        </span>

                    </div>

                    <div class="mt-3">

                        <button
                            type="button"
                            class="js-add-pending-unit inline-flex w-full items-center justify-center rounded-lg border border-amber-300 bg-amber-100 px-3 py-2 text-xs font-semibold text-amber-900 transition hover:bg-amber-200"
                            data-operational-unit-id="{{ $unit['operational_unit_id'] }}"
                        >
                            Adicionar à continuidade
                        </button>

                    </div>

                </div>

            @endforeach

        </div>

        {{-- ====================================================
             ESTADO QUANDO TODAS AS PENDÊNCIAS FORAM ADICIONADAS
        ===================================================== --}}

        <div
            id="pending-units-empty"
            class="hidden rounded-lg border border-dashed border-amber-300 bg-white p-4 text-center"
        >

            <p class="text-sm font-medium text-amber-800">
                Todas as unidades pendentes foram adicionadas.
            </p>

            <p class="mt-1 text-xs text-amber-700">
                Você também pode adicionar outras unidades pelo
                seletor abaixo.
            </p>

        </div>

    </section>

@endif
