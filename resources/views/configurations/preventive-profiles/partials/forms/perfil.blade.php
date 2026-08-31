@php
    $isEdit = ($mode ?? 'create') === 'edit';

    $profile = $preventiveProfile ?? null;

    $selectedPreventiveType = old(
        'preventive_type_id',
        $profile?->preventive_type_id
    );

    $selectedBranches = old(
        'branch_ids',
        $isEdit
            ? ($profile?->branches?->pluck('branch_id')->all() ?? [])
            : []
    );

    $selectedBranches = array_map('intval', $selectedBranches);
@endphp

{{-- ================================================================ --}}
{{-- DADOS DO PERFIL                                                  --}}
{{-- ================================================================ --}}

<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="text-lg font-semibold text-slate-900">
            Dados do perfil
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Informe os dados básicos do perfil de preventiva.
        </p>
    </div>

    <div class="p-6">

        <div class="grid gap-6 md:grid-cols-2">

            {{-- ==================================================== --}}
            {{-- NOME                                                   --}}
            {{-- ==================================================== --}}

            <div>
                <label
                    for="name"
                    class="block text-sm font-medium text-slate-700"
                >
                    Nome <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $profile?->name) }}"
                    required
                    maxlength="255"
                    placeholder="Ex.: Preventiva PDV - Lojas"
                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                >

                @error('name')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- ==================================================== --}}
            {{-- TIPO DE PREVENTIVA                                    --}}
            {{-- ==================================================== --}}

            <div>
                <label
                    for="preventive_type_id"
                    class="block text-sm font-medium text-slate-700"
                >
                    Tipo de Preventiva <span class="text-red-500">*</span>
                </label>

                <select
                    id="preventive_type_id"
                    name="preventive_type_id"
                    required
                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                >
                    <option value="">
                        Selecione o tipo de preventiva
                    </option>

                    @foreach ($preventiveTypes as $preventiveType)
                        <option
                            value="{{ $preventiveType->id }}"
                            @selected(
                                (int) $selectedPreventiveType === (int) $preventiveType->id
                            )
                        >
                            {{ $preventiveType->name }}
                        </option>
                    @endforeach
                </select>

                @error('preventive_type_id')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        {{-- ======================================================== --}}
        {{-- DESCRIÇÃO                                                 --}}
        {{-- ======================================================== --}}

        <div class="mt-6">
            <label
                for="description"
                class="block text-sm font-medium text-slate-700"
            >
                Descrição
            </label>

            <textarea
                id="description"
                name="description"
                rows="4"
                maxlength="1000"
                placeholder="Descreva a finalidade deste perfil de preventiva."
                class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
            >{{ old('description', $profile?->description) }}</textarea>

            @error('description')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- ======================================================== --}}
        {{-- ATIVO                                                     --}}
        {{-- ======================================================== --}}

        <div class="mt-6 rounded-lg border border-slate-200 bg-slate-50 p-4">

            <label class="flex cursor-pointer items-start gap-3">

                <input
                    type="checkbox"
                    name="active"
                    value="1"
                    class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                    @checked(
                        old(
                            'active',
                            $profile?->active ?? true
                        )
                    )
                >

                <span>
                    <span class="block text-sm font-medium text-slate-800">
                        Perfil ativo
                    </span>

                    <span class="mt-1 block text-xs text-slate-500">
                        Perfis inativos não poderão ser utilizados em novas configurações.
                    </span>
                </span>

            </label>

            @error('active')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>

    </div>
</div>


{{-- ================================================================ --}}
{{-- FILIAIS PARTICIPANTES                                            --}}
{{-- ================================================================ --}}

<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="text-lg font-semibold text-slate-900">
            Filiais participantes
        </h2>

        (Exibe apenas filiais que possuem unidade desse tipo cadastradas)

        <p class="mt-1 text-sm text-slate-500">
            Selecione as filiais que participarão deste perfil.
        </p>
    </div>

    <div class="p-6">

        {{-- ======================================================== --}}
        {{-- LOADING                                                    --}}
        {{-- ======================================================== --}}

        <div
            id="branches-loading"
            class="hidden rounded-lg border border-slate-200 bg-slate-50 p-6 text-center"
        >
            <div class="flex items-center justify-center gap-3">

                <svg
                    class="h-5 w-5 animate-spin text-blue-600"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>

                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                    ></path>
                </svg>

                <span class="text-sm text-slate-600">
                    Carregando filiais elegíveis...
                </span>

            </div>
        </div>


        {{-- ======================================================== --}}
        {{-- ESTADO VAZIO                                               --}}
        {{-- ======================================================== --}}

        <div
            id="branches-empty"
            class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-6 text-center"
        >
            <p class="font-medium text-slate-700">
                @if ($selectedPreventiveType)
                    Nenhuma filial disponível.
                @else
                    Selecione um tipo de preventiva.
                @endif
            </p>

            <p class="mt-1 text-sm text-slate-500">
                @if ($selectedPreventiveType)
                    Não existem filiais disponíveis para este cadastro.
                @else
                    As filiais elegíveis serão carregadas após selecionar o tipo de preventiva.
                @endif
            </p>
        </div>


        {{-- ======================================================== --}}
        {{-- FILIAIS                                                    --}}
        {{-- ======================================================== --}}

        <div
            id="eligible-branches"
            class="hidden grid gap-3 sm:grid-cols-2"
        >

            {{--

                No CREATE, o conteúdo será preenchido pelo
                resources/js/preventive-profiles/create.js.

                No EDIT, as filiais existentes podem ser
                disponibilizadas pelo backend/JS.

            --}}

            @if ($isEdit && isset($branches))

                @foreach ($branches as $branch)

                    <label
                        class="flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 bg-white p-4 transition hover:border-blue-400 hover:bg-blue-50"
                    >

                        <input
                            type="checkbox"
                            name="branch_ids[]"
                            value="{{ $branch->id }}"
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            @checked(
                                in_array(
                                    (int) $branch->id,
                                    $selectedBranches,
                                    true
                                )
                            )
                        >

                        <div class="min-w-0">
                            <span class="block font-medium text-slate-800">
                                {{ $branch->name }}
                            </span>
                        </div>

                    </label>

                @endforeach

            @endif

        </div>


        {{-- ======================================================== --}}
        {{-- ERRO DE FILIAIS                                            --}}
        {{-- ======================================================== --}}

        @error('branch_ids')
            <p class="mt-3 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>
</div>
