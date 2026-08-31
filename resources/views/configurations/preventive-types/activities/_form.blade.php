{{-- ========================================================= --}}
{{-- DADOS DA ATIVIDADE                                        --}}
{{-- ========================================================= --}}

<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Container principal --}}
    <div>

        {{-- Cabeçalho --}}
        <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

            <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
                Dados da Atividade
            </h2>

            <p class="mt-0.5 text-xs leading-relaxed text-slate-500 sm:text-sm">
                Informe os dados da atividade que será executada nesta preventiva.
            </p>

        </div>


        {{-- Corpo --}}
        <div class="space-y-6 p-4 sm:p-6">

            {{-- ================================================= --}}
            {{-- TIPO DE PREVENTIVA                                 --}}
            {{-- ================================================= --}}

            <div>

                <label for="preventive_type" class="mb-2 block text-sm font-medium text-slate-700">
                    Tipo de Preventiva
                </label>

                <div
                    class="rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs text-slate-700 sm:text-sm">
                    {{ $preventiveType->name }}
                </div>

                <p class="mt-1 text-xs text-slate-500">
                    A atividade será vinculada a este tipo de preventiva.
                </p>

            </div>


            {{-- ================================================= --}}
            {{-- NOME, TIPO E CATEGORIA                             --}}
            {{-- ================================================= --}}

            <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-3">

                {{-- Nome --}}
                <div>

                    <x-forms.input name="name" id="name" label="Nome" :value="old('name', $activity->name ?? null)"
                        placeholder="Ex.: Teste de SSD" required />

                    <p class="mt-1 text-xs text-slate-500">
                        Informe um nome claro e objetivo para a atividade.
                    </p>

                    @error('name')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Tipo --}}
                <div>

                    <label for="type" class="mb-2 block text-sm font-medium text-slate-700">
                        Tipo <span class="text-red-500">*</span>
                    </label>

                    <select id="type" name="type" required
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-xs text-slate-900 shadow-2xs focus:border-blue-500 focus:ring-blue-500 sm:text-sm">

                        <option value="">
                            Selecione o tipo da atividade
                        </option>

                        @foreach ($activityTypes as $activityType)
                            <option value="{{ $activityType->value }}" @selected(old('type', $activity->type?->value ?? ($activity->type ?? null)) === $activityType->value)>

                                {{ $activityType->label() }}

                            </option>
                        @endforeach

                    </select>

                    <p class="mt-1 text-xs text-slate-500">
                        Define como esta atividade será executada pelo técnico.
                    </p>

                    @error('type')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Categoria --}}
                <div>
                    <label for="activity_category_id" class="mb-2 block text-sm font-medium text-slate-700">
                        Categoria <span class="text-red-500">*</span>
                    </label>

                    <select id="activity_category_id" name="activity_category_id" required
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-xs text-slate-900 shadow-2xs focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">
                            Selecione a categoria
                        </option>

                        @foreach ($activityCategories as $activityCategory)
                            <option value="{{ $activityCategory->id }}" @selected((string) old('activity_category_id', $activity->activity_category_id ?? '') === (string) $activityCategory->id)>
                                {{ $activityCategory->name }}
                            </option>
                        @endforeach
                    </select>

                    <p class="mt-1 text-xs text-slate-500">
                        Agrupa atividades relacionadas ao mesmo objetivo.
                    </p>

                    @error('activity_category_id')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>


            {{-- ================================================= --}}
            {{-- DESCRIÇÃO                                          --}}
            {{-- ================================================= --}}

            <div>

                <label for="description" class="mb-2 block text-sm font-medium text-slate-700">
                    Descrição
                </label>

                <textarea id="description" name="description" rows="4"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-xs text-slate-900 shadow-2xs focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    placeholder="Descreva o objetivo e o procedimento da atividade.">{{ old('description', $activity->description ?? null) }}</textarea>

                <p class="mt-1 text-xs text-slate-500">
                    Explique de forma objetiva o que o técnico deverá realizar.
                </p>

                @error('description')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- ================================================= --}}
            {{-- STATUS                                             --}}
            {{-- ================================================= --}}

            @if (($mode ?? 'create') === 'create')
                <div class="pt-2">

                    <x-forms.checkbox name="active" label="Atividade ativa" :checked="old('active', true)" />

                </div>
            @elseif (($mode ?? null) === 'edit')
                @can('toggleActive', $activity)
                    <div class="pt-2">

                        <x-forms.checkbox name="active" label="Atividade ativa" :checked="old('active', $activity->active)" />

                    </div>
                @endcan
            @endif

        </div>

    </div>

</x-cards.card>
