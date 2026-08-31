{{-- ========================================================= --}}
{{-- DADOS DA CATEGORIA                                        --}}
{{-- ========================================================= --}}

<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div>

        {{-- Cabeçalho --}}

        <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

            <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
                Dados da Categoria
            </h2>

            <p class="mt-0.5 text-xs leading-relaxed text-slate-500 sm:text-sm">
                Informe os dados da categoria que será utilizada para organizar as atividades.
            </p>

        </div>


        {{-- Corpo --}}

        <div class="space-y-6 p-4 sm:p-6">

            {{-- ================================================= --}}
            {{-- NOME                                                --}}
            {{-- ================================================= --}}

            <div>

                <x-forms.input
                    name="name"
                    id="name"
                    label="Nome"
                    :value="old(
                        'name',
                        $activityCategory->name ?? null
                    )"
                    placeholder="Ex.: Hardware"
                    required
                />

                <p class="mt-1 text-xs text-slate-500">
                    Informe um nome claro e objetivo para identificar a categoria.
                </p>

                @error('name')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- ================================================= --}}
            {{-- STATUS                                              --}}
            {{-- ================================================= --}}

            @if (($mode ?? 'create') === 'create')

                <div class="pt-2">

                    <x-forms.checkbox
                        name="active"
                        id="active"
                        label="Categoria ativa"
                        :checked="old('active', true)"
                    />

                </div>

            @elseif (($mode ?? null) === 'edit')

                @can('toggleActive', $activityCategory)

                    <div class="pt-2">

                        <x-forms.checkbox
                            name="active"
                            id="active"
                            label="Categoria ativa"
                            :checked="old(
                                'active',
                                $activityCategory->active
                            )"
                        />

                    </div>

                @endcan

            @endif

        </div>

    </div>

</x-cards.card>
