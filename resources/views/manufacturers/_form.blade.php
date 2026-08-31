<x-cards.card>

    <div class="border-b border-slate-200 px-6 py-4">

        <h2 class="text-lg font-semibold text-slate-800">

            Dados do Fabricante

        </h2>

    </div>

    <div class="space-y-6 p-6">

        <x-forms.input
            name="name"
            label="Nome"
            :value="$manufacturer->name ?? null"
            required
        />

        @if($mode === 'create')

            <x-forms.checkbox
                name="active"
                label="Fabricante ativo"
                :checked="true"
            />

        @elseif($mode === 'edit')

            @can('toggleActive', $manufacturer)

                <x-forms.checkbox
                    name="active"
                    label="Fabricante ativo"
                    :checked="$manufacturer->active"
                />

            @endcan

        @endif

    </div>

</x-cards.card>
