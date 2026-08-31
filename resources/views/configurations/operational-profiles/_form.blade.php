{{-- ========================================================= --}}
{{-- DADOS DO PERFIL OPERACIONAL                              --}}
{{-- ========================================================= --}}

<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Cabeçalho --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">
        <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
            Dados do Perfil Operacional
        </h2>
    </div>

    {{-- Corpo --}}
    <div class="p-4 sm:p-6 space-y-4">

        {{-- Grid de Nome e Tipo de Unidade --}}
        <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2 items-start">

            {{-- Nome --}}
            <div>
                <x-forms.input
                    name="name"
                    label="Nome do Perfil"
                    :value="old('name', $operationalProfile->name ?? null)"
                    placeholder="Ex.: PDV com Scanner Bióptico"
                    required
                />

                <p class="mt-1 text-xs text-slate-500">
                    Informe um nome explicativo para a configuração real da unidade.
                </p>
            </div>

            {{-- Tipo de Unidade --}}
            <div>
                <label
                    for="unit_type_id"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Tipo de Unidade <span class="text-red-500">*</span>
                </label>

                @if ($mode === 'edit')

                    {{-- Tipo fixo na edição --}}
                    <div class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs font-medium text-slate-700 sm:text-sm h-[42px] flex items-center">
                        {{ $operationalProfile->unitType->name }}
                    </div>

                    {{-- Mantém o valor no POST --}}
                    <input
                        type="hidden"
                        name="unit_type_id"
                        value="{{ $operationalProfile->unit_type_id }}"
                    >

                    <p class="mt-1 text-xs text-slate-500">
                        O tipo de unidade define as categorias disponíveis para este perfil.
                    </p>

                @else

                    <select
                        id="unit_type_id"
                        name="unit_type_id"
                        required
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 focus:border-blue-500 focus:ring-blue-500 shadow-2xs"
                    >
                        <option value="">
                            Selecione o tipo de unidade
                        </option>

                        @foreach ($unitTypes as $unitType)
                            <option
                                value="{{ $unitType->id }}"
                                @selected(
                                    old(
                                        'unit_type_id',
                                        $operationalProfile->unit_type_id ?? null
                                    ) == $unitType->id
                                )
                            >
                                {{ $unitType->name }}
                            </option>
                        @endforeach
                    </select>

                    <p class="mt-1 text-xs text-slate-500">
                        Define o tipo de unidade a qual este perfil pertence.
                    </p>

                    @error('unit_type_id')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                @endif
            </div>

        </div>

        {{-- Status posicionado abaixo do Nome do Perfil --}}
        <div class="pt-2">

            @if ($mode === 'create')

                <x-forms.checkbox
                    name="active"
                    label="Perfil ativo"
                    :checked="old('active', true)"
                />

            @elseif ($mode === 'edit')

                @can('toggleActive', $operationalProfile)

                    <x-forms.checkbox
                        name="active"
                        label="Perfil ativo"
                        :checked="old('active', $operationalProfile->active)"
                    />

                @endcan

            @endif

        </div>

    </div>

</x-cards.card>


{{-- ========================================================= --}}
{{-- COMPOSIÇÃO OPERACIONAL                                   --}}
{{-- ========================================================= --}}

<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Container principal utilizado pelo JavaScript --}}
    <div
        id="operational-profile-form"
        data-mode="{{ $mode }}"
        data-categories='@json($categoriesData)'
        data-existing-categories='@json($existingCategories ?? [])'
    >

        {{-- Cabeçalho --}}
        <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">
            <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
                Composição Operacional
            </h2>

            <p class="mt-0.5 text-xs leading-relaxed text-slate-500 sm:text-sm">
                Adicione as categorias de equipamentos que fazem parte deste perfil operacional e informe suas quantidades.
            </p>
        </div>


        {{-- Corpo --}}
        <div class="space-y-6 p-4 sm:p-6">

            {{-- ================================================= --}}
            {{-- CATEGORIAS DISPONÍVEIS                            --}}
            {{-- ================================================= --}}

            <div>

                <div class="flex items-center justify-between gap-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 sm:text-sm">
                        Categorias Disponíveis
                    </h3>
                </div>

                <p class="mt-0.5 text-xs text-slate-500">
                    Clique em uma categoria para adicioná-la à composição.
                </p>

                {{-- Categorias serão inseridas pelo JavaScript --}}
                <div
                    id="available-categories"
                    class="mt-3 grid grid-cols-1 gap-2.5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                >

                    <div
                        id="available-categories-empty"
                        class="col-span-full rounded-xl border border-dashed border-slate-300 bg-slate-50/60 p-6 text-center"
                    >
                        <p class="text-xs font-semibold text-slate-700 sm:text-sm">
                            Selecione um tipo de unidade.
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            As categorias disponíveis para o tipo escolhido serão exibidas aqui.
                        </p>
                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- COMPOSIÇÃO DO PERFIL                              --}}
            {{-- ================================================= --}}

            <div class="border-t border-slate-100 pt-6">

                <div class="flex items-center justify-between gap-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 sm:text-sm">
                        Composição do Perfil
                    </h3>
                </div>

                <p class="mt-0.5 text-xs text-slate-500">
                    Defina a quantidade de cada categoria adicionada.
                </p>

                {{-- Categorias selecionadas serão inseridas pelo JS --}}
                <div
                    id="selected-categories"
                    class="mt-3 space-y-2.5"
                >

                    <div
                        id="selected-categories-empty"
                        class="rounded-xl border border-dashed border-slate-300 bg-slate-50/60 p-6 text-center"
                    >
                        <p class="text-xs font-semibold text-slate-700 sm:text-sm">
                            Nenhuma categoria adicionada.
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Clique nas categorias acima para montar a composição deste perfil.
                        </p>
                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- INFORMAÇÃO                                       --}}
            {{-- ================================================= --}}

            <div class="rounded-lg bg-slate-50 p-3 text-xs text-slate-500 border border-slate-200/60">
                <span class="font-semibold text-slate-700">Atenção:</span> Adicione apenas as categorias pertencentes a este perfil. Categorias não selecionadas não serão registradas.
            </div>

        </div>

    </div>

</x-cards.card>


{{-- ========================================================= --}}
{{-- JAVASCRIPT DO MÓDULO                                     --}}
{{-- ========================================================= --}}

@vite('resources/js/operational-profile/operationalProfile.js')
