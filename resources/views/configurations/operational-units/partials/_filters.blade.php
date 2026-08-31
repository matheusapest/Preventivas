{{-- ========================================================= --}}
{{-- FILTROS                                                   --}}
{{-- ========================================================= --}}

<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Cabeçalho --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">
        <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
            Filtros
        </h2>

        <p class="mt-0.5 text-xs leading-relaxed text-slate-500 sm:text-sm">
            Utilize os filtros para localizar uma unidade operacional específica.
        </p>
    </div>

    {{-- Corpo --}}
    <form
        action="{{ route('configuracoes.unidades-operacionais.index') }}"
        method="GET"
    >
        <div class="space-y-4 p-4 sm:p-6">

            {{-- Linha principal --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

                {{-- Identificador --}}
                <div>
                    <x-forms.input
                        name="search"
                        label="Buscar"
                        :value="request('search')"
                        placeholder="Nome ou identificador da unidade"
                    />
                </div>

                {{-- Filial --}}
                <div>
                    <label
                        for="branch_id"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Filial
                    </label>

                    <select
                        id="branch_id"
                        name="branch_id"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-xs text-slate-900 shadow-2xs focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                        <option value="">
                            Todas as filiais
                        </option>

                        @foreach ($branches as $branch)
                            <option
                                value="{{ $branch->id }}"
                                @selected(request('branch_id') == $branch->id)
                            >
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tipo de Unidade --}}
                <div>
                    <label
                        for="unit_type_id"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Tipo de Unidade
                    </label>

                    <select
                        id="unit_type_id"
                        name="unit_type_id"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-xs text-slate-900 shadow-2xs focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                        <option value="">
                            Todos os tipos
                        </option>

                        @foreach ($unitTypes as $unitType)
                            <option
                                value="{{ $unitType->id }}"
                                @selected(request('unit_type_id') == $unitType->id)
                            >
                                {{ $unitType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Perfil Operacional --}}
                <div>
                    <label
                        for="operational_profile_id"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Perfil Operacional
                    </label>

                    <select
                        id="operational_profile_id"
                        name="operational_profile_id"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-xs text-slate-900 shadow-2xs focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                        <option value="">
                            Todos os perfis
                        </option>

                        @foreach ($operationalProfiles as $operationalProfile)
                            <option
                                value="{{ $operationalProfile->id }}"
                                @selected(request('operational_profile_id') == $operationalProfile->id)
                            >
                                {{ $operationalProfile->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            {{-- Separador --}}
            <div class="border-t border-slate-100 pt-4">

                {{-- Ações --}}
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                    <x-buttons.secondary
                        :href="route('configuracoes.unidades-operacionais.index')"
                        class="w-full justify-center sm:w-auto"
                    >
                        Limpar filtros
                    </x-buttons.secondary>

                    <x-buttons.primary
                        type="submit"
                        class="w-full justify-center sm:w-auto"
                    >
                        Filtrar
                    </x-buttons.primary>

                </div>

            </div>

        </div>
    </form>

</x-cards.card>
