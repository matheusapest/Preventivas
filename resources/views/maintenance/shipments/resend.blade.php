@extends('layout.app')

@section('title', 'Reenviar Equipamento')

@section('content')

    @php
        /*
         * Os shipments foram carregados pelo controller em ordem
         * decrescente de sequence.
         *
         * Portanto:
         * - first() = último ciclo
         * - last()  = primeiro ciclo da OS
         */
        $initialShipment = $maintenanceOrder->shipments->sortBy('sequence')->first();
    @endphp

    <div class="w-full min-w-0 flex-1 space-y-4 p-3.5 sm:space-y-6 sm:p-6">

        <x-layout.page-header title="Reenviar Equipamento"
            description="Crie um novo ciclo de envio para a mesma ordem de serviço.">

            <x-slot:breadcrumb>
                <nav class="flex flex-wrap items-center gap-1.5 text-xs text-slate-500">
                    <span class="hidden sm:inline">Dashboard</span>
                    <span class="hidden sm:inline">/</span>
                    <span class="hidden sm:inline">Operação</span>
                    <span class="hidden sm:inline">/</span>

                    <a href="{{ route('reparos_externos.index') }}" class="transition hover:text-slate-800">
                        Reparos Externos
                    </a>

                    <span>/</span>

                    <span class="font-medium text-slate-800">
                        Reenvio
                    </span>
                </nav>
            </x-slot:breadcrumb>

        </x-layout.page-header>


        {{-- ============================================================
             INFORMAÇÕES DA OS
        ============================================================ --}}

        <div
            class="
                rounded-xl
                border
                border-slate-200
                bg-white
                p-3.5
                shadow-sm
                sm:p-6
            ">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between sm:gap-4">

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Ordem de Serviço
                    </p>

                    <h2 class="mt-0.5 text-base font-semibold text-slate-900 sm:mt-1 sm:text-lg">
                        OS #{{ $maintenanceOrder->id }}
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-500 sm:mt-1 sm:text-sm">
                        Novo ciclo de reparo externo
                    </p>

                </div>


                <div class="self-start rounded-lg bg-blue-50 px-3 py-1.5 sm:py-2">

                    <p class="text-[10px] font-medium text-blue-600 sm:text-xs">
                        Novo ciclo
                    </p>

                    <p class="text-base font-semibold text-blue-800 sm:text-lg">
                        #{{ $latestShipment->sequence + 1 }}
                    </p>

                </div>

            </div>


            <div class="mt-4 grid grid-cols-1 gap-3.5 sm:mt-5 sm:grid-cols-2 lg:grid-cols-4 sm:gap-4">

                {{-- Equipamento --}}
                <div>

                    <span class="text-xs font-medium text-slate-500">
                        Equipamento
                    </span>

                    <p class="mt-0.5 text-xs font-medium text-slate-800 sm:text-sm">
                        {{ $maintenanceOrder->equipment?->name ?? 'Não informado' }}
                    </p>

                </div>


                {{-- Patrimônio --}}
                <div>

                    <span class="text-xs font-medium text-slate-500">
                        Patrimônio
                    </span>

                    <p class="mt-0.5 text-xs font-medium text-slate-800 sm:text-sm">
                        {{ $maintenanceOrder->equipment?->asset_number ?? 'Não informado' }}
                    </p>

                </div>


                {{-- Número de série --}}
                <div>

                    <span class="text-xs font-medium text-slate-500">
                        Número de série
                    </span>

                    <p class="mt-0.5 text-xs font-medium text-slate-800 sm:text-sm">
                        {{ $maintenanceOrder->equipment?->serial_number ?? 'Não informado' }}
                    </p>

                </div>


                {{-- Ciclo anterior --}}
                <div>

                    <span class="text-xs font-medium text-slate-500">
                        Último ciclo
                    </span>

                    <p class="mt-0.5 text-xs font-medium text-slate-800 sm:text-sm">
                        #{{ $latestShipment->sequence }}
                    </p>

                </div>

            </div>

        </div>


        {{-- ============================================================
             AVISO DO REENVIO
        ============================================================ --}}

        <div
            class="
                rounded-xl
                border
                border-amber-200
                bg-amber-50
                p-3.5
                sm:p-5
            ">

            <div class="flex items-start gap-2.5 sm:gap-3">

                <div class="mt-0.5 shrink-0">

                    <svg class="h-4 w-4 text-amber-600 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86l-8.18 14A2 2 0 003.82 21h16.36a2 2 0 001.71-3.14l-8.18-14a2 2 0 00-3.42 0z" />

                    </svg>

                </div>


                <div>

                    <p class="text-xs font-semibold text-amber-900 sm:text-sm">
                        Novo envio dentro da mesma OS
                    </p>

                    <p class="mt-0.5 text-xs leading-relaxed text-amber-800 sm:mt-1">
                        Este procedimento não criará uma nova ordem de serviço.
                        Será criado apenas um novo ciclo de envio para a OS
                        #{{ $maintenanceOrder->id }}.
                    </p>

                </div>

            </div>

        </div>


        {{-- ============================================================
             FORMULÁRIO
        ============================================================ --}}

        <div
            class="
                rounded-xl
                border
                border-slate-200
                bg-white
                p-3.5
                shadow-sm
                sm:p-6
            ">

            <div class="mb-4 sm:mb-5">

                <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
                    Dados do reenvio
                </h2>

                <p class="mt-0.5 text-xs text-slate-500 sm:mt-1 sm:text-sm">
                    Informe os dados referentes ao novo envio do equipamento.
                </p>

            </div>


            @if ($errors->any())

                <div
                    class="
                        mb-4
                        rounded-lg
                        border
                        border-red-200
                        bg-red-50
                        p-3.5
                        sm:mb-5
                        sm:p-4
                    ">

                    <p class="text-xs font-semibold text-red-800 sm:text-sm">
                        Não foi possível realizar o reenvio.
                    </p>

                    <ul class="mt-1.5 list-disc space-y-1 pl-4 text-xs text-red-700 sm:mt-2 sm:pl-5 sm:text-sm">

                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach

                    </ul>

                </div>

            @endif


            <form method="POST"
                action="{{ route('reparos_externos.reenviar.store', $maintenanceOrder) }}">

                @csrf


                <div class="grid grid-cols-1 gap-4 sm:gap-5 md:grid-cols-2">


                    {{-- Empresa --}}
                    <div>

                        <label for="company_id" class="mb-1.5 block text-xs font-medium text-slate-700 sm:mb-2 sm:text-sm">
                            Empresa responsável pelo reparo
                        </label>

                        <select id="company_id" name="company_id" required
                            class="
                                w-full
                                rounded-lg
                                border
                                border-slate-300
                                bg-white
                                px-3.5
                                py-2.5
                                text-xs
                                text-slate-800
                                focus:border-blue-500
                                focus:outline-none
                                focus:ring-2
                                focus:ring-blue-500
                                sm:px-4
                                sm:py-3
                                sm:text-sm
                            ">

                            <option value="">
                                Selecione a empresa
                            </option>

                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" @selected(old('company_id', $latestShipment->company_id) == $company->id)>
                                    {{ $company->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('company_id')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Filial --}}
                    <div>

                        <label for="origin_branch_id" class="mb-1.5 block text-xs font-medium text-slate-700 sm:mb-2 sm:text-sm">
                            Filial de origem
                        </label>

                        <select id="origin_branch_id" name="origin_branch_id" required
                            class="
                                w-full
                                rounded-lg
                                border
                                border-slate-300
                                bg-white
                                px-3.5
                                py-2.5
                                text-xs
                                text-slate-800
                                focus:border-blue-500
                                focus:outline-none
                                focus:ring-2
                                focus:ring-blue-500
                                sm:px-4
                                sm:py-3
                                sm:text-sm
                            ">

                            <option value="">
                                Selecione a filial
                            </option>

                            @foreach ($branches as $state => $stateBranches)
                                <optgroup label="{{ $state }}">

                                    @foreach ($stateBranches as $branch)
                                        <option value="{{ $branch->id }}" @selected(old('origin_branch_id', $latestShipment->origin_branch_id) == $branch->id)>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach

                                </optgroup>
                            @endforeach

                        </select>

                        @error('origin_branch_id')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Data e hora --}}
                    <div>

                        <label for="sent_at" class="mb-1.5 block text-xs font-medium text-slate-700 sm:mb-2 sm:text-sm">
                            Data e hora do reenvio
                        </label>

                        <input id="sent_at" name="sent_at" type="datetime-local" required
                            value="{{ old('sent_at', now()->format('Y-m-d\TH:i')) }}"
                            class="
                                w-full
                                rounded-lg
                                border
                                border-slate-300
                                px-3.5
                                py-2.5
                                text-xs
                                text-slate-800
                                focus:border-blue-500
                                focus:outline-none
                                focus:ring-2
                                focus:ring-blue-500
                                sm:px-4
                                sm:py-3
                                sm:text-sm
                            ">

                        @error('sent_at')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Nota fiscal --}}
                    <div>

                        <label for="invoice_number" class="mb-1.5 block text-xs font-medium text-slate-700 sm:mb-2 sm:text-sm">
                            Nota fiscal
                            <span class="font-normal text-slate-400">
                                (nova NF do reenvio)
                            </span>
                        </label>

                        <input id="invoice_number" name="invoice_number" type="text" maxlength="50"
                            value="{{ old('invoice_number') }}"
                            class="
                                w-full
                                rounded-lg
                                border
                                border-slate-300
                                px-3.5
                                py-2.5
                                text-xs
                                text-slate-800
                                focus:border-blue-500
                                focus:outline-none
                                focus:ring-2
                                focus:ring-blue-500
                                sm:px-4
                                sm:py-3
                                sm:text-sm
                            "
                            placeholder="Número da nova nota fiscal">

                        @error('invoice_number')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- =================================================
                         DEFEITO INICIAL
                    ================================================== --}}
                    <div class="md:col-span-2">

                        <x-cards.card class="overflow-hidden">

                            <div class="border-b border-slate-100 px-3.5 py-2.5 sm:px-4 sm:py-3">

                                <div class="flex items-center justify-between gap-3">

                                    <div>

                                        <p class="text-xs font-semibold text-slate-800 sm:text-sm">
                                            Defeito inicial
                                        </p>

                                        <p class="mt-0.5 text-[11px] text-slate-500 sm:text-xs">
                                            Informado no primeiro envio desta OS.
                                        </p>

                                    </div>

                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            rounded-full
                                            bg-slate-100
                                            px-2
                                            py-0.5
                                            text-[10px]
                                            font-medium
                                            text-slate-600
                                            sm:px-2.5
                                            sm:py-1
                                            sm:text-xs
                                        ">
                                        Somente leitura
                                    </span>

                                </div>

                            </div>


                            <div class="px-3.5 py-2.5 sm:px-4 sm:py-3">

                                @if ($initialShipment?->defect_description)
                                    <p class="whitespace-pre-line text-xs leading-relaxed text-slate-700 sm:text-sm">
                                        {{ $initialShipment->defect_description }}
                                    </p>
                                @else
                                    <p class="text-xs italic text-slate-400 sm:text-sm">
                                        Nenhum defeito inicial informado.
                                    </p>
                                @endif

                            </div>

                        </x-cards.card>

                    </div>


                    {{-- =================================================
                         DEFEITO ATUAL
                    ================================================== --}}
                    <div class="md:col-span-2">

                        <label for="defect_description" class="mb-1.5 block text-xs font-medium text-slate-700 sm:mb-2 sm:text-sm">
                            Defeito atual / motivo do reenvio
                        </label>

                        <p class="mb-1.5 text-[11px] leading-relaxed text-slate-500 sm:mb-2 sm:text-xs">
                            Informe o problema identificado atualmente pelo técnico
                            e o motivo pelo qual o equipamento está sendo reenviado.
                        </p>

                        <textarea id="defect_description" name="defect_description" rows="4" required
                            class="
                                w-full
                                rounded-lg
                                border
                                border-slate-300
                                px-3.5
                                py-2.5
                                text-xs
                                text-slate-800
                                focus:border-blue-500
                                focus:outline-none
                                focus:ring-2
                                focus:ring-blue-500
                                sm:px-4
                                sm:py-3
                                sm:text-sm
                            "
                            placeholder="Descreva o defeito atual identificado no equipamento.">{{ old('defect_description') }}</textarea>

                        @error('defect_description')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Observação --}}
                    <div class="md:col-span-2">

                        <label for="observation" class="mb-1.5 block text-xs font-medium text-slate-700 sm:mb-2 sm:text-sm">
                            Observação
                            <span class="font-normal text-slate-400">
                                (opcional)
                            </span>
                        </label>

                        <textarea id="observation" name="observation" rows="3"
                            class="
                                w-full
                                rounded-lg
                                border
                                border-slate-300
                                px-3.5
                                py-2.5
                                text-xs
                                text-slate-800
                                focus:border-blue-500
                                focus:outline-none
                                focus:ring-2
                                focus:ring-blue-500
                                sm:px-4
                                sm:py-3
                                sm:text-sm
                            "
                            placeholder="Informações adicionais sobre o reenvio.">{{ old('observation') }}</textarea>

                        @error('observation')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>


                {{-- Ações --}}
                <div
                    class="
                        mt-5
                        flex
                        flex-col-reverse
                        gap-2.5
                        border-t
                        border-slate-200
                        pt-4
                        sm:mt-6
                        sm:flex-row
                        sm:justify-end
                        sm:gap-3
                        sm:pt-5
                    ">

                    <a href="{{ route('reparos_externos.show', $maintenanceOrder) }}"
                        class="
                            inline-flex
                            w-full
                            items-center
                            justify-center
                            rounded-lg
                            border
                            border-slate-300
                            bg-white
                            px-5
                            py-2.5
                            text-xs
                            font-medium
                            text-slate-700
                            transition
                            hover:bg-slate-50
                            sm:w-auto
                            sm:py-3
                            sm:text-sm
                        ">
                        Cancelar
                    </a>


                    <button type="submit"
                        class="
                            inline-flex
                            w-full
                            items-center
                            justify-center
                            rounded-lg
                            bg-blue-600
                            px-5
                            py-2.5
                            text-xs
                            font-medium
                            text-white
                            transition
                            hover:bg-blue-700
                            focus:outline-none
                            focus:ring-2
                            focus:ring-blue-500
                            focus:ring-offset-2
                            sm:w-auto
                            sm:py-3
                            sm:text-sm
                        ">
                        Reenviar equipamento
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
