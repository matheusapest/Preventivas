{{-- ========================================================= --}}
{{-- REGRAS ESPECÍFICAS                                        --}}
{{-- ========================================================= --}}

<div class="rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- CABEÇALHO --}}
    <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-start sm:justify-between">

        <div>
            <h2 class="text-base font-semibold text-slate-900">
                Regras específicas
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Crie regras diferentes da regra Todos para
                unidades específicas desta filial.
            </p>
        </div>

        {{-- BOTÃO DO MODAL --}}
        <button type="button" data-specific-rule-open
            class="inline-flex shrink-0 items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Adicionar regra específica
        </button>

    </div>

    {{-- CONTEÚDO --}}
    <div class="px-6 py-5">

        @if (($specificRules ?? collect())->isEmpty())

            <div class="rounded-lg border border-dashed border-slate-300 px-6 py-6 text-center">

                <p class="text-sm font-medium text-slate-600">
                    Nenhuma regra específica foi configurada para esta filial.
                </p>

                <p class="mt-1 text-sm text-slate-400">
                    As unidades utilizarão a regra Todos enquanto não houver
                    uma regra específica.
                </p>

            </div>
        @else
            <div class="space-y-3">

                @foreach ($specificRules as $specificRule)
                    <div class="rounded-lg border border-slate-200 px-4 py-4">

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                            {{-- INFORMAÇÕES DA REGRA --}}
                            <div>

                                <p class="text-sm font-semibold text-slate-900">
                                    Regra específica
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $specificRule->units->count() }}
                                    unidade(s)
                                    —
                                    {{ $specificRule->activities->count() }}
                                    atividade(s)
                                </p>

                                {{-- UNIDADES --}}
                                @if ($specificRule->units->isNotEmpty())
                                    <div class="mt-2 flex flex-wrap gap-2">

                                        @foreach ($specificRule->units as $unit)
                                            <span
                                                class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">
                                                {{ $unit->operationalUnit?->identifier ?? ($unit->operationalUnit?->name ?? 'Unidade') }}
                                            </span>
                                        @endforeach

                                    </div>
                                @endif

                            </div>

                            {{-- AÇÕES --}}
                            <div class="flex shrink-0 items-center gap-2">

                                {{-- EDITAR --}}
                                <button type="button" data-specific-rule-edit
                                    data-specific-rule-id="{{ $specificRule->id }}"
                                    data-update-url="{{ route('configuracoes.perfis-preventivas.regras.specific.update', [
                                        'preventiveProfile' => $preventiveProfile,
                                        'rule' => $rule,
                                        'specificRule' => $specificRule,
                                    ]) }}"
                                    data-operational-unit-id="{{ $specificRule->units->first()?->operational_unit_id }}"
                                    data-operational-unit-label="{{ $specificRule->units->first()?->operationalUnit?->identifier }}"
                                    data-activity-ids='@json($specificRule->activities->pluck('activity_id')->values())'
                                    class="inline-flex shrink-0 items-center justify-center rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Editar
                                </button>

                                <form method="POST"
                                    action="{{ route('configuracoes.perfis-preventivas.regras.specific.destroy', [
                                        'preventiveProfile' => $preventiveProfile,
                                        'rule' => $rule,
                                        'specificRule' => $specificRule,
                                    ]) }}"
                                    class="inline-flex" data-specific-rule-delete>
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="inline-flex shrink-0 items-center justify-center rounded-lg border border-red-200 px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        Excluir
                                    </button>
                                </form>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        @endif

        {{-- INFORMAÇÃO DAS UNIDADES --}}
        <div class="mt-4 rounded-lg border border-blue-100 bg-blue-50 px-4 py-4">

            <p class="text-sm font-medium text-blue-900">
                Unidades operacionais da filial
            </p>

            <p class="mt-1 text-sm text-blue-700">
                Esta filial possui
                <strong>{{ $operationalUnits->count() }}</strong>
                unidade(s) operacional(is) ativa(s) elegível(is)
                para regras específicas.
            </p>

        </div>

    </div>

</div>
