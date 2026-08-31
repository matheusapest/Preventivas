<x-cards.card>

    <div class="border-b border-slate-200 px-6 py-4">
        <h2 class="text-lg font-semibold text-slate-800">
            Dados da Filial
        </h2>
    </div>

    <div class="space-y-6 p-6">

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            <x-forms.select
                name="company_id"
                label="Empresa"
                :options="$companies"
                optionValue="id"
                optionLabel="name"
                :value="$branch->company_id ?? null"
                required
            />

            <x-forms.select
                name="branch_code_id"
                label="Código da Filial"
                :options="$branchCodes"
                optionValue="id"
                optionLabel="code"
                :value="$branch->branch_code_id ?? null"
                required
            />

        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            <x-forms.input
                name="name"
                label="Nome da Filial"
                :value="$branch->name ?? null"
                required
            />

            <x-forms.input
                name="city"
                label="Cidade"
                :value="$branch->city ?? null"
                required
            />

        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            <x-forms.select
                name="state"
                label="Estado"
                :options="$states"
                optionValue="value"
                optionLabel="label"
                :value="$branch->state->value ?? null"
                required
            />

            <x-forms.select
                name="type"
                label="Tipo"
                :options="$types"
                optionValue="value"
                optionLabel="label"
                :value="$branch->type->value ?? null"
                required
            />

        </div>

        @if ($mode === 'create')

            <x-forms.checkbox
                name="active"
                label="Filial ativa"
                :checked="true"
            />

        @elseif ($mode === 'edit')

            @can('toggleActive', $branch)

                <x-forms.checkbox
                    name="active"
                    label="Filial ativa"
                    :checked="$branch->active"
                />

            @endcan

        @endif

    </div>

</x-cards.card>
