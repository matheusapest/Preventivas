<x-cards.card>

    <div class="border-b border-slate-200 px-6 py-4">

        <h2 class="text-lg font-semibold text-slate-800">

            Dados do Número da Filial

        </h2>

    </div>

    <div class="space-y-6 p-6">

        <x-forms.input
            name="code"
            label="Número"
            :value="$branchCode->code ?? null"
            required
        />

        @if($mode === 'create')

            <x-forms.checkbox
                name="active"
                label="Número ativo"
                :checked="true"
            />

        @elseif($mode === 'edit')

            @can('toggleActive', $branchCode)

                <x-forms.checkbox
                    name="active"
                    label="Número ativo"
                    :checked="$branchCode->active"
                />

            @endcan

        @endif

    </div>

</x-cards.card>
