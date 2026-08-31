<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Cabeçalho do Card --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">
        <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
            Dados da Empresa
        </h2>
    </div>

    {{-- Corpo do Formulário --}}
    <div class="space-y-5 p-4 sm:space-y-6 sm:p-6">

        {{-- Grid de Campos --}}
        <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2">

            <x-forms.input
                name="name"
                label="Nome"
                :value="$company->name ?? null"
                required
            />

            <x-forms.select
                name="type"
                label="Tipo"
                :options="$types"
                optionValue="value"
                optionLabel="label"
                :value="$company->type->value ?? null"
                required
            />

        </div>

        {{-- Área do Checkbox com destaque de clique --}}
        <div class="pt-2">

            @if ($mode === 'create')

                <div class="rounded-lg border border-slate-100 bg-slate-50/50 p-3 sm:border-0 sm:bg-transparent sm:p-0">

                    <x-forms.checkbox
                        name="active"
                        label="Empresa ativa"
                        :checked="true"
                    />

                </div>

            @elseif ($mode === 'edit')

                @can('toggleActive', $company)

                    <div class="rounded-lg border border-slate-100 bg-slate-50/50 p-3 sm:border-0 sm:bg-transparent sm:p-0">

                        <x-forms.checkbox
                            name="active"
                            label="Empresa ativa"
                            :checked="$company->active"
                        />

                    </div>

                @endcan

            @endif

        </div>

    </div>

</x-cards.card>
