{{-- ========================================================= --}}
{{-- DADOS DO TIPO DE PREVENTIVA                               --}}
{{-- ========================================================= --}}

<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Container principal --}}
    <div>

        {{-- Cabeçalho --}}
        <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

            <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
                Dados do Tipo de Preventiva
            </h2>

            <p class="mt-0.5 text-xs leading-relaxed text-slate-500 sm:text-sm">
                Informe os dados do tipo de preventiva.
            </p>

        </div>

        {{-- Corpo --}}
        <div class="space-y-6 p-4 sm:p-6">

            {{-- ================================================= --}}
            {{-- TIPO DE UNIDADE E NOME                            --}}
            {{-- ================================================= --}}

            <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2">

                {{-- Tipo de Unidade --}}
                <div>

                    <label
                        for="unit_type_id"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Tipo de Unidade <span class="text-red-500">*</span>
                    </label>

                    @if (($mode ?? 'create') === 'create')

                        <select
                            id="unit_type_id"
                            name="unit_type_id"
                            required
                            class="w-full rounded-lg border @error('unit_type_id') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-slate-300 focus:border-blue-500 focus:ring-blue-500 @enderror bg-white px-3.5 py-2.5 text-xs text-slate-900 shadow-sm focus:outline-none focus:ring-1 sm:text-sm"
                        >
                            <option value="">
                                Selecione o tipo de unidade
                            </option>

                            @foreach ($unitTypes as $unitType)

                                <option
                                    value="{{ $unitType->id }}"
                                    @selected(
                                        old('unit_type_id') == $unitType->id
                                    )
                                >
                                    {{ $unitType->name }}
                                </option>

                            @endforeach

                        </select>

                    @else

                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs text-slate-700 sm:text-sm">
                            {{ $preventiveType->unitType?->name ?? '—' }}
                        </div>

                        <p class="mt-1 text-xs text-slate-500">
                            O tipo de unidade não pode ser alterado após o cadastro.
                        </p>

                    @endif

                    @error('unit_type_id')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Nome --}}
                <div>

                    <x-forms.input
                        name="name"
                        id="name"
                        label="Nome"
                        :value="old(
                            'name',
                            $preventiveType->name ?? null
                        )"
                        placeholder="Ex.: Preventiva de PDV"
                        required
                    />

                    <p class="mt-1 text-xs text-slate-500">
                        Informe o nome que identifica este tipo de preventiva.
                    </p>

                    @error('name')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>

            {{-- ================================================= --}}
            {{-- STATUS                                             --}}
            {{-- ================================================= --}}

            @if (($mode ?? 'create') === 'create')

                <div class="pt-2">

                    <x-forms.checkbox
                        name="active"
                        id="active"
                        label="Tipo de preventiva ativo"
                        :checked="old('active', true)"
                    />

                </div>

            @elseif (($mode ?? null) === 'edit')

                @can('toggleActive', $preventiveType)

                    <div class="pt-2">

                        <x-forms.checkbox
                            name="active"
                            id="active"
                            label="Tipo de preventiva ativo"
                            :checked="old(
                                'active',
                                $preventiveType->active
                            )"
                        />

                    </div>

                @endcan

            @endif

        </div>

    </div>

</x-cards.card>
