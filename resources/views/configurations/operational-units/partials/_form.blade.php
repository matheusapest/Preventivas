{{-- ========================================================= --}}
{{-- DADOS DA UNIDADE OPERACIONAL                              --}}
{{-- ========================================================= --}}

<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Container principal --}}
    <div>

        {{-- Cabeçalho --}}
        <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

            <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
                Dados da Unidade Operacional
            </h2>

            <p class="mt-0.5 text-xs leading-relaxed text-slate-500 sm:text-sm">
                Informe os dados da unidade operacional.
            </p>

        </div>


        {{-- Corpo --}}
        <div class="space-y-6 p-4 sm:p-6">

            {{-- ================================================= --}}
            {{-- FILIAL, TIPO E PERFIL                            --}}
            {{-- ================================================= --}}

            <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-3">

                {{-- Filial --}}
                <div>

                    <label
                        for="branch_id"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Filial <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="branch_id"
                        name="branch_id"
                        required
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-xs text-slate-900 shadow-2xs focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >

                        <option value="">
                            Selecione a filial
                        </option>

                        @foreach ($branches as $branch)

                            <option
                                value="{{ $branch->id }}"
                                @selected(
                                    old(
                                        'branch_id',
                                        $operationalUnit->branch_id ?? null
                                    ) == $branch->id
                                )
                            >
                                {{ $branch->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('branch_id')

                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Tipo de Unidade --}}
                <div>

                    <label
                        for="unit_type_id"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Tipo de Unidade <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="unit_type_id"
                        name="unit_type_id"
                        required
                        disabled
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-xs text-slate-900 shadow-2xs focus:border-blue-500 focus:ring-blue-500 disabled:cursor-not-allowed disabled:bg-slate-50 sm:text-sm"
                    >

                        <option value="">
                            Selecione o tipo de unidade
                        </option>

                    </select>

                    @error('unit_type_id')

                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Perfil Operacional --}}
                <div>

                    <label
                        for="operational_profile_id"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Perfil Operacional <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="operational_profile_id"
                        name="operational_profile_id"
                        required
                        disabled
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-xs text-slate-900 shadow-2xs focus:border-blue-500 focus:ring-blue-500 disabled:cursor-not-allowed disabled:bg-slate-50 sm:text-sm"
                    >

                        <option value="">
                            Selecione o perfil operacional
                        </option>

                    </select>

                    @error('operational_profile_id')

                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- IDENTIFICADOR                                    --}}
            {{-- ================================================= --}}

            <div>

                <x-forms.input
                    name="identifier"
                    id="identifier"
                    label="Identificador"
                    :value="old(
                        'identifier',
                        $operationalUnit->identifier ?? null
                    )"
                    placeholder="Ex.: PDV 03"
                    required
                />

                <p class="mt-1 text-xs text-slate-500">
                    Informe o identificador utilizado para esta unidade.
                </p>

                @error('identifier')

                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- ================================================= --}}
            {{-- STATUS                                             --}}
            {{-- ================================================= --}}

            @if (($mode ?? 'create') === 'create')

                <div class="pt-2">

                    <x-forms.checkbox
                        name="active"
                        label="Unidade ativa"
                        :checked="old('active', true)"
                    />

                </div>

            @elseif (($mode ?? null) === 'edit')

                @can('toggleActive', $operationalUnit)

                    <div class="pt-2">

                        <x-forms.checkbox
                            name="active"
                            label="Unidade ativa"
                            :checked="old(
                                'active',
                                $operationalUnit->active
                            )"
                        />

                    </div>

                @endcan

            @endif

        </div>

    </div>

</x-cards.card>


