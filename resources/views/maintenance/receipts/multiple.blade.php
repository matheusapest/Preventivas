@extends('layout.app')

@section('title', 'Receber Equipamentos')

@section('content')

    <div class="space-y-5">

        {{-- Cabeçalho --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

            <div>

                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 text-xs text-slate-500 sm:text-sm">

                    <a href="{{ route('reparos_externos.recebimentos.index') }}" class="transition hover:text-blue-600">
                        Recebimentos
                    </a>

                    <span>/</span>

                    <span class="text-slate-700">
                        Receber Equipamentos
                    </span>

                </div>


                <h1 class="mt-2 text-xl font-semibold text-slate-800 sm:text-2xl">
                    Receber Equipamentos
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Pesquise os equipamentos pelo patrimônio ou número de série
                    e adicione ao lote de recebimento.
                </p>

            </div>


            {{-- Voltar --}}
            <a href="{{ route('reparos_externos.recebimentos.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                Voltar
            </a>

        </div>


        {{-- Consulta do equipamento --}}
        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

                <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                    Adicionar equipamento
                </h2>

                <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
                    Informe o patrimônio ou número de série do equipamento.
                    O sistema verificará se ele está disponível para recebimento.
                </p>

            </div>


            <div class="p-4 sm:p-6">

                <div class="flex flex-col gap-3 sm:flex-row sm:items-end">

                    <div class="flex-1">

                        <label for="equipment_identifier" class="block text-xs font-medium text-slate-700 sm:text-sm">
                            Patrimônio ou número de série
                        </label>

                        <input type="text" id="equipment_identifier" autocomplete="off"
                            placeholder="Digite o patrimônio ou número de série"
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">

                    </div>


                    <button type="button" id="btn-search-equipment"
                        class="inline-flex shrink-0 items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60">
                        Consultar
                    </button>

                </div>


                <div id="equipment-search-message" class="mt-3 hidden rounded-lg border px-3 py-2.5 text-xs sm:text-sm">
                </div>

            </div>

        </x-cards.card>


        {{-- Equipamentos selecionados --}}
        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                            Equipamentos selecionados
                        </h2>

                        <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
                            Equipamentos que serão recebidos nesta operação.
                        </p>

                    </div>


                    <span id="selected-equipment-count"
                        class="inline-flex w-fit items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                        0 selecionados
                    </span>

                </div>

            </div>


            <div id="selected-equipment-list" class="p-4 sm:p-6">

                {{-- Estado inicial --}}
                <div id="empty-selection"
                    class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-6 text-center">

                    <p class="text-sm font-medium text-slate-600">
                        Nenhum equipamento selecionado.
                    </p>

                    <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                        Pesquise um patrimônio ou número de série para adicionar
                        um equipamento ao lote.
                    </p>

                </div>

            </div>

        </x-cards.card>


        {{-- Dados do recebimento --}}
        <form id="multiple-receipt-form" method="POST"
            action="{{ route('reparos_externos.recebimentos.multiplos.store') }}">

            @csrf


            {{-- IDs dos shipments adicionados pelo JavaScript --}}
            <div id="shipment-inputs"></div>


            <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

                    <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                        Dados do Recebimento
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
                        Informe os dados referentes ao recebimento físico dos equipamentos.
                    </p>

                </div>


                <div class="space-y-5 p-4 sm:p-6">

                    {{-- Filial de recebimento --}}
                    <div>

                        <label for="receiving_branch_id" class="block text-xs font-medium text-slate-700 sm:text-sm">
                            Filial de Recebimento
                        </label>

                        <select id="receiving_branch_id" name="receiving_branch_id" required
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">

                            <option value="">
                                Selecione a filial de recebimento
                            </option>

                            @foreach ($branches as $state => $stateBranches)
                                <optgroup label="{{ $state }}">

                                    @foreach ($stateBranches as $branch)
                                        <option value="{{ $branch->id }}" @selected(old('receiving_branch_id') == $branch->id)>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach

                                </optgroup>
                            @endforeach

                        </select>


                        @error('receiving_branch_id')
                            <p class="mt-1 text-xs text-red-600 sm:text-sm">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Nota fiscal --}}
                    <div>

                        <label for="invoice_number" class="block text-xs font-medium text-slate-700 sm:text-sm">
                            Nota Fiscal de Retorno
                        </label>

                        <input type="text" id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}"
                            maxlength="50" placeholder="Opcional"
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">


                        @error('invoice_number')
                            <p class="mt-1 text-xs text-red-600 sm:text-sm">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Aviso --}}
                    <div class="rounded-lg border border-blue-200 bg-blue-50 p-3.5 sm:p-4">

                        <div class="flex gap-3">

                            <div class="shrink-0 text-blue-600">

                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>

                            </div>

                            <div>

                                <p class="text-xs font-semibold text-blue-800 sm:text-sm">
                                    Confirmação do recebimento
                                </p>

                                <p class="mt-0.5 text-xs leading-relaxed text-blue-700 sm:text-sm">
                                    Ao confirmar, todos os equipamentos selecionados serão registrados
                                    como recebidos e seus respectivos envios serão marcados como retornados.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Ações --}}
                <div
                    class="flex flex-col-reverse gap-2 border-t border-slate-200 px-4 py-4 sm:flex-row sm:items-center sm:justify-end sm:px-6">

                    <a href="{{ route('reparos_externos.recebimentos.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        Cancelar
                    </a>


                    <button type="submit" id="btn-submit-receipt" disabled
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                        Receber Equipamentos
                    </button>

                </div>

            </x-cards.card>

        </form>

    </div>

@endsection

@vite('resources/js/maintenance/receipts/multiple.js')
