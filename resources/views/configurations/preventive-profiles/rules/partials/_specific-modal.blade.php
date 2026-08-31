{{-- ========================================================= --}}
{{-- MODAL - REGRA ESPECÍFICA                                  --}}
{{-- ========================================================= --}}

<div
    id="specific-rule-modal"
    class="fixed inset-0 z-[9999] hidden"
    aria-labelledby="specific-rule-modal-title"
    aria-modal="true"
    role="dialog"
>
    {{-- ===================================================== --}}
    {{-- BACKDROP                                              --}}
    {{-- ===================================================== --}}

    <div
        data-specific-rule-backdrop
        class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
    ></div>

    {{-- ===================================================== --}}
    {{-- CENTRALIZAÇÃO                                         --}}
    {{-- ===================================================== --}}

    <div class="relative flex min-h-screen items-center justify-center p-4 sm:p-6">

        {{-- ================================================= --}}
        {{-- MODAL                                              --}}
        {{-- ================================================= --}}

        <div class="relative flex max-h-[90vh] w-full max-w-2xl flex-col overflow-hidden rounded-xl bg-white shadow-2xl">

            {{-- ================================================= --}}
            {{-- CABEÇALHO                                          --}}
            {{-- ================================================= --}}

            <div class="flex shrink-0 items-start justify-between border-b border-slate-200 px-6 py-5">

                <div>
                    <h2
                        id="specific-rule-modal-title"
                        class="text-lg font-semibold text-slate-900"
                    >
                        Nova regra específica
                    </h2>

                    <p
                        id="specific-rule-modal-description"
                        class="mt-1 text-sm text-slate-500"
                    >
                        Configure atividades diferentes da regra Todos
                        para uma unidade específica.
                    </p>
                </div>

                {{-- BOTÃO FECHAR --}}
                <button
                    type="button"
                    data-specific-rule-close
                    class="ml-4 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    aria-label="Fechar"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        class="h-5 w-5"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18 18 6M6 6l12 12"
                        />
                    </svg>
                </button>

            </div>

            {{-- ================================================= --}}
            {{-- FORMULÁRIO                                         --}}
            {{-- ================================================= --}}

            <form
                id="specific-rule-form"
                method="POST"
                action="{{ route('configuracoes.perfis-preventivas.regras.specific.store', [$preventiveProfile, $rule]) }}"
                data-create-url="{{ route('configuracoes.perfis-preventivas.regras.specific.store', [$preventiveProfile, $rule]) }}"
                data-update-url-template="{{ route('configuracoes.perfis-preventivas.regras.specific.update', [
                    'preventiveProfile' => $preventiveProfile,
                    'rule' => $rule,
                    'specificRule' => '__SPECIFIC_RULE_ID__',
                ]) }}"
                class="flex min-h-0 flex-1 flex-col"
            >

                @csrf

                {{-- Método HTTP --}}
                <input
                    type="hidden"
                    name="_method"
                    value="{{ old('_method', 'POST') }}"
                    data-specific-rule-method
                >

                {{-- ID da regra específica --}}
                <input
                    type="hidden"
                    name="specific_rule_id"
                    value="{{ old('specific_rule_id') }}"
                    data-specific-rule-id-input
                >

                {{-- ID da unidade operacional --}}
                <input
                    type="hidden"
                    name="operational_unit_id"
                    value="{{ old('operational_unit_id') }}"
                    data-specific-rule-operational-unit-input
                >

                {{-- ================================================= --}}
                {{-- CONTEÚDO                                           --}}
                {{-- ================================================= --}}

                <div class="min-h-0 flex-1 overflow-y-auto px-6 py-5">

                    {{-- ================================================= --}}
                    {{-- ERRO DO SERVICE                                   --}}
                    {{-- ================================================= --}}

                    @if (session('error') && session('specific_modal_error'))

                        <div
                            data-specific-modal-error="true"
                            class="mb-5 flex items-start gap-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2.5"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                class="mt-0.5 h-4 w-4 shrink-0 text-red-500"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 9v3.75m0 3.75h.007v.008H12v-.008zM10.34 3.94 2.86 17a2 2 0 0 0 1.73 3h14.82a2 2 0 0 0 1.73-3L13.66 3.94a1.91 1.91 0 0 0-3.32 0Z"
                                />
                            </svg>

                            <p class="text-sm font-medium text-red-700">
                                {{ session('error') }}
                            </p>
                        </div>

                    @endif

                    {{-- ================================================= --}}
                    {{-- UNIDADE OPERACIONAL                               --}}
                    {{-- ================================================= --}}

                    <div>

                        <label
                            for="specific-operational-unit"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Unidade operacional
                        </label>

                        {{-- ================================================= --}}
                        {{-- MODO CRIAÇÃO                                       --}}
                        {{-- ================================================= --}}

                        <div data-specific-create-operational-unit>

                            <select
                                id="specific-operational-unit"
                                name="operational_unit_id"
                                class="
                                    block w-full rounded-lg
                                    border
                                    bg-white
                                    px-3 py-2.5
                                    text-sm text-slate-900
                                    shadow-sm
                                    outline-none
                                    transition
                                    focus:ring-2
                                    @error('operational_unit_id')
                                        border-red-400
                                        focus:border-red-500
                                        focus:ring-red-500/20
                                    @else
                                        border-slate-300
                                        focus:border-blue-500
                                        focus:ring-blue-500/20
                                    @enderror
                                "
                                data-specific-operational-unit-select
                            >

                                <option value="">
                                    Selecione uma unidade
                                </option>

                                @foreach ($availableOperationalUnits as $unit)

                                    <option
                                        value="{{ $unit->id }}"
                                        @selected(old('operational_unit_id') == $unit->id)
                                    >
                                        {{ $unit->identifier }}

                                        @if ($unit->name)
                                            — {{ $unit->name }}
                                        @endif
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- ================================================= --}}
                        {{-- MODO EDIÇÃO                                        --}}
                        {{-- ================================================= --}}

                        <div
                            id="specific-edit-operational-unit"
                            class="hidden"
                            data-specific-edit-operational-unit
                        >
                            <div
                                class="
                                    flex items-center justify-between
                                    rounded-lg
                                    border border-slate-200
                                    bg-slate-50
                                    px-3 py-2.5
                                "
                            >

                                <div>

                                    <p class="text-xs font-medium text-slate-500">
                                        Unidade vinculada à regra
                                    </p>

                                    <p
                                        id="specific-edit-operational-unit-label"
                                        data-specific-edit-operational-unit-label
                                        class="mt-0.5 text-sm font-semibold text-slate-800"
                                    ></p>

                                </div>

                                <span
                                    class="
                                        rounded-full
                                        bg-slate-200
                                        px-2 py-1
                                        text-xs
                                        font-medium
                                        text-slate-600
                                    "
                                >
                                    Não alterável
                                </span>

                            </div>
                        </div>

                        @error('operational_unit_id')

                            <div
                                class="
                                    mt-2 flex items-start gap-2
                                    rounded-lg
                                    border border-red-200
                                    bg-red-50
                                    px-3 py-2.5
                                "
                            >
                                <p class="text-sm font-medium text-red-700">
                                    {{ $message }}
                                </p>
                            </div>

                        @enderror

                    </div>

                    {{-- ================================================= --}}
                    {{-- ATIVIDADES                                        --}}
                    {{-- ================================================= --}}

                    <div class="mt-6">

                        <label class="mb-3 block text-sm font-medium text-slate-700">
                            Atividades da regra
                        </label>

                        <div class="overflow-hidden rounded-lg border border-slate-200">

                            @forelse ($activities as $activity)

                                <label
                                    class="
                                        flex cursor-pointer items-center gap-3
                                        border-b border-slate-200
                                        px-4 py-3
                                        last:border-b-0
                                        hover:bg-slate-50
                                    "
                                >

                                    <input
                                        type="checkbox"
                                        name="activity_ids[]"
                                        value="{{ $activity->id }}"
                                        @checked(
                                            in_array(
                                                (string) $activity->id,
                                                array_map(
                                                    'strval',
                                                    old('activity_ids', [])
                                                ),
                                                true
                                            )
                                        )
                                        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                    >

                                    <span class="text-sm font-medium text-slate-700">
                                        {{ $activity->name }}
                                    </span>

                                </label>

                            @empty

                                <div class="px-4 py-6 text-center">

                                    <p class="text-sm text-slate-500">
                                        Nenhuma atividade ativa disponível.
                                    </p>

                                </div>

                            @endforelse

                        </div>

                        {{-- ERRO GERAL DAS ATIVIDADES --}}
                        @error('activity_ids')

                            <div class="mt-2 flex items-start gap-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2.5">

                                <p class="text-sm font-medium text-red-700">
                                    {{ $message }}
                                </p>

                            </div>

                        @enderror

                        {{-- ERRO DOS ITENS --}}
                        @error('activity_ids.*')

                            <div class="mt-2 flex items-start gap-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2.5">

                                <p class="text-sm font-medium text-red-700">
                                    {{ $message }}
                                </p>

                            </div>

                        @enderror

                    </div>

                </div>

                {{-- ================================================= --}}
                {{-- RODAPÉ                                            --}}
                {{-- ================================================= --}}

                <div
                    class="
                        flex shrink-0 items-center justify-end gap-3
                        border-t border-slate-200
                        bg-slate-50
                        px-6 py-4
                    "
                >

                    <button
                        type="button"
                        data-specific-rule-close
                        class="
                            inline-flex items-center justify-center
                            rounded-lg border border-slate-300
                            bg-white px-4 py-2
                            text-sm font-medium text-slate-700
                            transition
                            hover:bg-slate-50
                            focus:outline-none
                            focus:ring-2
                            focus:ring-blue-500
                            focus:ring-offset-2
                        "
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        data-specific-rule-submit
                        class="
                            inline-flex items-center justify-center
                            rounded-lg bg-blue-600 px-4 py-2
                            text-sm font-medium text-white
                            transition
                            hover:bg-blue-700
                            focus:outline-none
                            focus:ring-2
                            focus:ring-blue-500
                            focus:ring-offset-2
                        "
                    >
                        <span data-specific-rule-submit-text>
                            Criar regra
                        </span>
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
