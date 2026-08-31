{{-- ========================================================= --}}
{{-- DADOS DO TIPO DE UNIDADE                                  --}}
{{-- ========================================================= --}}

<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Cabeçalho --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">
        <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
            Dados do Tipo de Unidade
        </h2>

        <p class="mt-1 text-xs leading-relaxed text-slate-500 sm:text-sm">
            Defina o tipo de unidade e as filiais onde ele poderá ser utilizado.
        </p>
    </div>

    {{-- Corpo --}}
    <div class="space-y-6 p-4 sm:p-6">

        {{-- Nome --}}
        <div>
            <x-forms.input
                name="name"
                label="Nome"
                :value="old('name', $unitType->name ?? null)"
                placeholder="Ex.: PDV, Balança Etiquetadora, Cancela de Estacionamento"
                required
            />

            <p class="mt-1.5 text-xs leading-relaxed text-slate-500">
                Informe o nome do tipo de unidade operacional que será utilizado
                na operação.
            </p>

            @error('name')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Status --}}
        <div class="pt-1">

            @if ($mode === 'create')

                <div class="rounded-lg border border-slate-100 bg-slate-50/50 p-3 sm:border-0 sm:bg-transparent sm:p-0">

                    <x-forms.checkbox
                        name="active"
                        label="Tipo de unidade ativo"
                        :checked="old('active', true)"
                    />

                </div>

            @elseif ($mode === 'edit')

                @can('update', $unitType)

                    <div class="rounded-lg border border-slate-100 bg-slate-50/50 p-3 sm:border-0 sm:bg-transparent sm:p-0">

                        <x-forms.checkbox
                            name="active"
                            label="Tipo de unidade ativo"
                            :checked="old('active', $unitType->active)"
                        />

                    </div>

                @endcan

            @endif

        </div>

    </div>

</x-cards.card>


{{-- ========================================================= --}}
{{-- FILIAIS DISPONÍVEIS                                      --}}
{{-- ========================================================= --}}

<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Cabeçalho --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

        <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
            Filiais Disponíveis
        </h2>

        <p class="mt-1 text-xs leading-relaxed text-slate-500 sm:text-sm">
            Selecione as filiais onde este tipo de unidade poderá ser cadastrado.
        </p>

    </div>

    {{-- Corpo --}}
    <div class="space-y-4 p-4 sm:p-6">

        @php
            $selectedBranches = old(
                'branches',
                $mode === 'edit'
                    ? $unitType->branches->pluck('id')->toArray()
                    : []
            );
        @endphp

        @if ($branches->isNotEmpty())

            <div class="overflow-hidden rounded-lg border border-slate-200">

                {{-- Cabeçalho --}}
                <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Filiais
                    </p>

                </div>

                {{-- Lista --}}
                <div class="divide-y divide-slate-200">

                    @foreach ($branches as $branch)

                        <label
                            for="branch-{{ $branch->id }}"
                            class="flex cursor-pointer items-center gap-3 px-4 py-3 transition hover:bg-slate-50"
                        >

                            <input
                                type="checkbox"
                                id="branch-{{ $branch->id }}"
                                name="branches[]"
                                value="{{ $branch->id }}"
                                @checked(in_array($branch->id, $selectedBranches))
                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            >

                            <div class="min-w-0">

                                <p class="text-sm font-medium text-slate-900">
                                    {{ $branch->name }}
                                </p>

                            </div>

                        </label>

                    @endforeach

                </div>

            </div>

        @else

            <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-6 text-center">

                <p class="text-sm font-medium text-slate-700">
                    Nenhuma filial disponível.
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Cadastre uma filial ativa antes de definir a disponibilidade
                    deste tipo de unidade.
                </p>

            </div>

        @endif

        @error('branches')
            <p class="text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

        @error('branches.*')
            <p class="text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

        @if ($branches->isNotEmpty())

            <p class="text-xs leading-relaxed text-slate-500">
                É obrigatório selecionar pelo menos uma filial.
                Somente as filiais selecionadas poderão utilizar este tipo
                de unidade operacional.
            </p>

        @endif

    </div>

</x-cards.card>
