<x-cards.card>

    <div class="border-b border-slate-200 px-6 py-4">

        <h2 class="text-lg font-semibold text-slate-800">
            Dados do Modelo
        </h2>

    </div>

    <div class="space-y-6 p-6">

        <x-forms.select
            name="category_id"
            label="Categoria"
            :options="$categories"
            :selected="$equipmentModel->category_id ?? null"
            required
        />

        <x-forms.select
            name="manufacturer_id"
            label="Fabricante"
            :options="$manufacturers"
            :selected="$equipmentModel->manufacturer_id ?? null"
            required
        />

        <x-forms.input
            name="name"
            label="Modelo"
            :value="$equipmentModel->name ?? null"
            required
        />

        @if($mode === 'create')

            <x-forms.checkbox
                name="active"
                label="Modelo ativo"
                :checked="true"
            />

        @elseif($mode === 'edit')

            @can('toggleActive', $equipmentModel)

                <x-forms.checkbox
                    name="active"
                    label="Modelo ativo"
                    :checked="$equipmentModel->active"
                />

            @endcan

        @endif

    </div>

</x-cards.card>
