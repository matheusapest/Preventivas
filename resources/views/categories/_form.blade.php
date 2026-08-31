<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Cabeçalho do Card --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">
        <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
            Dados da Categoria
        </h2>
    </div>

    {{-- Corpo do Formulário --}}
    <div class="space-y-4 p-4 sm:space-y-6 sm:p-6">

        {{-- Nome --}}
        <x-forms.input
            name="name"
            label="Nome"
            :value="$category->name ?? null"
            required
        />

        {{-- Tipos de Unidade --}}
        <div>
            <label class="mb-2 block text-sm font-medium text-slate-700">
                Tipos de Unidade
                <span class="text-red-500">*</span>
            </label>

            @php
                $selectedUnitTypeIds = old(
                    'unit_type_ids',
                    isset($category)
                        ? $category->unitTypes->pluck('id')->all()
                        : []
                );

                $selectedUnitTypeIds = array_map(
                    'strval',
                    $selectedUnitTypeIds ?? []
                );
            @endphp

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">

                @foreach ($unitTypes as $unitType)

                    <label
                        class="flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 bg-white p-3 transition hover:border-slate-300 hover:bg-slate-50"
                    >

                        <input
                            type="checkbox"
                            name="unit_type_ids[]"
                            value="{{ $unitType->id }}"
                            @checked(
                                in_array(
                                    (string) $unitType->id,
                                    $selectedUnitTypeIds,
                                    true
                                )
                            )
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                        >

                        <span class="text-sm text-slate-700">
                            {{ $unitType->name }}

                            @if (!$unitType->active)
                                <span class="text-xs text-slate-400">
                                    (Inativo)
                                </span>
                            @endif
                        </span>

                    </label>

                @endforeach

            </div>

            @error('unit_type_ids')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

            @error('unit_type_ids.*')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

            <p class="mt-1.5 text-xs text-slate-500">
                Selecione um ou mais tipos de unidade onde esta categoria
                de equipamento pode ser utilizada.
            </p>
        </div>

        {{-- Status --}}
        <div class="pt-1">

            @if ($mode === 'create')

                <div class="rounded-lg border border-slate-100 bg-slate-50/50 p-3 sm:border-0 sm:bg-transparent sm:p-0">

                    <x-forms.checkbox
                        name="active"
                        label="Categoria ativa"
                        :checked="true"
                    />

                </div>

            @elseif ($mode === 'edit')

                @can('toggleActive', $category)

                    <div class="rounded-lg border border-slate-100 bg-slate-50/50 p-3 sm:border-0 sm:bg-transparent sm:p-0">

                        <x-forms.checkbox
                            name="active"
                            label="Categoria ativa"
                            :checked="$category->active"
                        />

                    </div>

                @endcan

            @endif

        </div>

    </div>

</x-cards.card>
