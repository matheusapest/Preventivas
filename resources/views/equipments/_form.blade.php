<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Cabeçalho do Card --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">
        <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
            Dados do Equipamento
        </h2>
    </div>

    {{-- Corpo do Formulário --}}
    <div class="space-y-4 p-4 sm:space-y-6 sm:p-6">

        {{-- Filial e Modelo --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-6">

            <x-forms.select name="branch_id" label="Filial" :options="$branches" :value="$equipment->branch_id ?? null" required />

            <x-forms.select name="equipment_model_id" label="Modelo do equipamento" :options="$equipmentModels" :value="$equipment->equipment_model_id ?? null"
                required />

        </div>


        {{-- Nome do Equipamento --}}
        <x-forms.input name="name" label="Nome do Equipamento" :value="$equipment->name ?? old('name')" required />


        {{-- Patrimônio e Número de Série --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-6">

            <x-forms.input name="asset_number" label="Número de patrimônio" :value="$equipment->asset_number ?? old('asset_number')" />

            <x-forms.input name="serial_number" label="Número de série" :value="$equipment->serial_number ?? old('serial_number')" />

        </div>


        {{-- Descrição --}}
        <x-forms.textarea name="description" label="Descrição" :value="$equipment->description ?? old('description')" />


        {{-- Status --}}
        <div class="pt-1">

            @if ($mode === 'create')
                {{-- Equipamento novo começa ativo --}}
                <div
                    class="
                        rounded-lg
                        border
                        border-slate-100
                        bg-slate-50/50
                        p-3
                        sm:border-0
                        sm:bg-transparent
                        sm:p-0
                    ">
                    <x-forms.checkbox name="active" label="Equipamento ativo" :checked="true" />
                </div>
            @elseif ($mode === 'edit')

                {{-- Status Operacional --}}
                <x-forms.select name="operational_status" label="Status Operacional" :options="$operationalStatuses"
                    optionValue="value" optionLabel="name" :value="$equipment->operational_status?->value" required />
            {{-- Ativo/Inativo --}}
                @can('toggleActive', $equipment)
                    <div
                        class="
                            rounded-lg
                            border
                            border-slate-100
                            bg-slate-50/50
                            p-3
                            sm:border-0
                            sm:bg-transparent
                            sm:p-0
                        ">
                        <x-forms.checkbox name="active" label="Equipamento ativo" :checked="$equipment->active" />
                    </div>
                @endcan


            @endif

        </div>

    </div>

</x-cards.card>
