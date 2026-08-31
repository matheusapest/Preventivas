<div
    id="shipment-form"
    @class([
        'hidden' => !$errors->any(),
    ])
>
    <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        {{-- Cabeçalho --}}
        <div class="border-b border-slate-200 px-3.5 py-3 sm:px-6 sm:py-4">

            <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                Dados do Envio
            </h2>

            <p class="mt-0.5 text-xs text-slate-500 sm:mt-1 sm:text-sm">
                Informe os dados necessários para enviar o equipamento para reparo externo.
            </p>

        </div>


        {{-- Formulário --}}
        <form
            action="{{ route('reparos_externos.store') }}"
            method="POST"
        >

            @csrf


            {{-- Equipamento selecionado --}}
            <input
                type="hidden"
                name="equipment_id"
                id="shipment-equipment-id"
                value="{{ old('equipment_id') }}"
            >


            <div class="space-y-4 p-3.5 sm:space-y-6 sm:p-6">

                {{-- Dados principais do envio --}}
                <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2">

                    {{-- Empresa Terceirizada --}}
                    <x-forms.select
                        name="company_id"
                        label="Empresa Terceirizada"
                        :options="$companies"
                        optionValue="id"
                        optionLabel="name"
                        :value="old('company_id')"
                        required
                    />


                    {{-- Filial de Envio--}}
                    <div>

                        <label
                            for="origin_branch_id"
                            class="block text-xs font-medium text-slate-700 sm:text-sm"
                        >
                            Filial de Envio
                        </label>

                        <select
                            id="origin_branch_id"
                            name="origin_branch_id"
                            required
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-xs text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm"
                        >

                            <option value="">
                                Selecione a filial de Envio
                            </option>

                            @foreach ($branches as $state => $stateBranches)

                                <optgroup label="{{ $state }}">

                                    @foreach ($stateBranches as $branch)

                                        <option
                                            value="{{ $branch->id }}"
                                            @selected(old('origin_branch_id') == $branch->id)
                                        >
                                            {{ $branch->name }}
                                        </option>

                                    @endforeach

                                </optgroup>

                            @endforeach

                        </select>

                        @error('origin_branch_id')

                            <p class="mt-1 text-xs text-red-600 sm:mt-1.5 sm:text-sm">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>


                {{-- Data e Nota Fiscal --}}
                <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2">

                    {{-- Data de Envio --}}
                    <x-forms.input
                        type="date"
                        name="sent_at"
                        label="Data de Envio"
                        :value="old('sent_at', now()->toDateString())"
                        min="{{ now()->subDays(7)->toDateString() }}"
                        max="{{ now()->toDateString() }}"
                        required
                    />


                    {{-- Nota Fiscal --}}
                    <x-forms.input
                        name="invoice_number"
                        label="Número da Nota Fiscal"
                        placeholder="Opcional"
                        maxlength="50"
                        :value="old('invoice_number')"
                    />

                </div>


                {{-- Defeito --}}
                <x-forms.textarea
                    name="defect_description"
                    label="Defeito Apresentado"
                    placeholder="Descreva o defeito apresentado pelo equipamento."
                    rows="4"
                    :value="old('defect_description')"
                    required
                />


                {{-- Observação --}}
                <x-forms.textarea
                    name="observation"
                    label="Observação"
                    placeholder="Informe alguma observação adicional, se necessário."
                    rows="3"
                    :value="old('observation')"
                />

            </div>


            {{-- Ações --}}
            <div class="flex flex-col-reverse gap-2.5 border-t border-slate-200 px-3.5 py-3.5 sm:flex-row sm:items-center sm:justify-end sm:gap-3 sm:px-6 sm:py-4">

                <x-buttons.secondary
                    type="button"
                    onclick="document.getElementById('shipment-form').classList.add('hidden')"
                    class="w-full justify-center sm:w-auto"
                >
                    Cancelar
                </x-buttons.secondary>

                <x-buttons.primary
                    type="submit"
                    class="w-full justify-center sm:w-auto"
                >
                    Enviar para Reparo
                </x-buttons.primary>

            </div>

        </form>

    </x-cards.card>
</div>
