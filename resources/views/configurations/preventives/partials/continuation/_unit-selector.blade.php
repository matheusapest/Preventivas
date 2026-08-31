{{-- ============================================================
     SELETOR DE UNIDADES
============================================================= --}}

<section class="mb-6 rounded-xl border border-slate-200 bg-white">

    <div class="border-b border-slate-200 p-4">

        <h2 class="text-sm font-semibold text-slate-900">
            Adicionar unidade
        </h2>

        <p class="mt-1 text-xs text-slate-500">
            Selecione uma unidade e escolha as atividades que deverão ser refeitas.
        </p>

    </div>

    <div class="p-4">

        <div>

            <label for="continuation-unit-select" class="mb-1.5 block text-xs font-medium text-slate-700">
                Unidade operacional
            </label>

            <select id="continuation-unit-select"
                data-activities-url="{{ route('preventivas.continuation.activities', [$preventive, '__UNIT_ID__']) }}"
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                <option value="">
                    Selecione uma unidade...
                </option>

                @foreach ($availableUnits as $unit)
                    <option value="{{ $unit['operational_unit_id'] }}" data-name="{{ $unit['name'] }}"
                        data-identifier="{{ $unit['identifier'] }}">
                        {{ $unit['identifier'] }}
                    </option>
                @endforeach

            </select>

        </div>

        <div id="continuation-activities-container" class="mt-5 hidden border-t border-slate-200 pt-5">

            <div class="mb-3">

                <h3 id="continuation-selected-unit-name" class="text-sm font-semibold text-slate-900"></h3>

                <p id="continuation-selected-unit-identifier" class="mt-0.5 text-xs text-slate-500"></p>

            </div>

            <div id="continuation-activities-loading"
                class="hidden rounded-lg border border-slate-200 bg-slate-50 p-3 text-center text-xs text-slate-500">
                Carregando atividades...
            </div>

            <div id="continuation-activities-error"
                class="hidden rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700"></div>

            <div id="continuation-activities-results" class="space-y-2"></div>

            <div class="mt-4 flex justify-end">

                <button type="button" id="add-continuation-unit"
                    class="hidden inline-flex items-center justify-center rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">
                    Adicionar unidade
                </button>

            </div>

        </div>

    </div>

</section>
