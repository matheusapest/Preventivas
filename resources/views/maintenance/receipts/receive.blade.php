@extends('layout.app')

@section('title', 'Receber Equipamento')

@section('content')

    <div class="w-full min-w-0 flex-1 space-y-4 p-3.5 sm:space-y-6 sm:p-6">

        <x-layout.page-header title="Receber Equipamento"
            description="Registre o recebimento do equipamento devolvido pela empresa terceirizada.">
            <x-slot:breadcrumb>

                <nav class="flex flex-wrap items-center gap-1.5 text-xs text-slate-500">

                    <span class="hidden sm:inline">Dashboard</span>

                    <span class="hidden sm:inline">/</span>

                    <a href="{{ route('reparos_externos.index') }}" class="transition hover:text-slate-800">
                        Reparo Externo
                    </a>

                    <span>/</span>

                    <a href="{{ route('reparos_externos.show', $maintenanceShipment->maintenanceOrder) }}"
                        class="transition hover:text-slate-800">
                        Ordem de Serviço
                    </a>

                    <span>/</span>

                    <span class="font-medium text-slate-800">
                        Receber
                    </span>

                </nav>

            </x-slot:breadcrumb>

            <x-slot:actions>

                <x-buttons.secondary :href="route('reparos_externos.show', $maintenanceShipment->maintenanceOrder)">
                    Voltar
                </x-buttons.secondary>

            </x-slot:actions>

        </x-layout.page-header>


        {{-- Erros de validação --}}
        @if ($errors->any())

            <div class="rounded-xl border border-red-200 bg-red-50 p-3.5 sm:p-4">

                <div class="flex items-start gap-3">

                    <div class="shrink-0">

                        <span
                            class="flex h-6 w-6 items-center justify-center rounded-full bg-red-100 text-xs font-bold text-red-700 sm:text-sm">
                            !
                        </span>

                    </div>

                    <div>

                        <h3 class="text-xs font-semibold text-red-800 sm:text-sm">
                            Não foi possível registrar o recebimento.
                        </h3>

                        <ul class="mt-1.5 list-disc space-y-1 pl-4 text-xs text-red-700 sm:mt-2 sm:pl-5 sm:text-sm">

                            @foreach ($errors->all() as $error)
                                <li>
                                    {{ $error }}
                                </li>
                            @endforeach

                        </ul>

                    </div>

                </div>

            </div>

        @endif


        {{-- Resumo do equipamento --}}
        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

                <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                    Equipamento
                </h2>

                <p class="mt-0.5 text-xs text-slate-500 sm:mt-1 sm:text-sm">
                    Equipamento relacionado ao envio que está sendo recebido.
                </p>

            </div>

            <div class="p-4 sm:p-6">

                <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2 lg:grid-cols-4 sm:gap-5">

                    {{-- Patrimônio --}}
                    <div>

                        <label class="text-xs font-medium text-slate-500">
                            Patrimônio
                        </label>

                        <p class="mt-0.5 text-sm font-bold text-slate-800 sm:text-base">
                            {{ $maintenanceShipment->maintenanceOrder->equipment->asset_number }}
                        </p>

                    </div>


                    {{-- Nome --}}
                    <div>

                        <label class="text-xs font-medium text-slate-500">
                            Nome
                        </label>

                        <p class="mt-0.5 text-sm font-semibold text-slate-800 sm:text-base">
                            {{ $maintenanceShipment->maintenanceOrder->equipment->name }}
                        </p>

                    </div>


                    {{-- Categoria --}}
                    <div>

                        <label class="text-xs font-medium text-slate-500">
                            Categoria
                        </label>

                        <p class="mt-0.5 text-sm text-slate-700">
                            {{ $maintenanceShipment->maintenanceOrder->equipment->equipmentModel?->category?->name ?? '—' }}
                        </p>

                    </div>


                    {{-- Fabricante --}}
                    <div>

                        <label class="text-xs font-medium text-slate-500">
                            Fabricante
                        </label>

                        <p class="mt-0.5 text-sm text-slate-700">
                            {{ $maintenanceShipment->maintenanceOrder->equipment->equipmentModel?->manufacturer?->name ?? '—' }}
                        </p>

                    </div>


                    {{-- Modelo --}}
                    <div>

                        <label class="text-xs font-medium text-slate-500">
                            Modelo
                        </label>

                        <p class="mt-0.5 text-sm text-slate-700">
                            {{ $maintenanceShipment->maintenanceOrder->equipment->equipmentModel?->name ?? '—' }}
                        </p>

                    </div>


                    {{-- Número de Série --}}
                    <div>

                        <label class="text-xs font-medium text-slate-500">
                            Número de Série
                        </label>

                        <p class="mt-0.5 font-mono text-sm text-slate-700">
                            {{ $maintenanceShipment->maintenanceOrder->equipment->serial_number ?: '—' }}
                        </p>

                    </div>


                    {{-- Filial --}}
                    <div>

                        <label class="text-xs font-medium text-slate-500">
                            Filial Atual
                        </label>

                        <p class="mt-0.5 text-sm font-medium text-slate-800">
                            {{ $maintenanceShipment->originBranch?->name ?? '—' }}
                        </p>

                    </div>


                    {{-- Status operacional --}}
                    <div>

                        <label class="text-xs font-medium text-slate-500">
                            Status Operacional
                        </label>

                        <p class="mt-0.5 text-sm font-medium text-slate-800">
                            {{ $maintenanceShipment->maintenanceOrder->equipment->operational_status?->label() ?? '—' }}
                        </p>

                    </div>

                </div>

            </div>

        </x-cards.card>


        {{-- Informações do envio --}}
        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

                <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                    Informações do Envio
                </h2>

                <p class="mt-0.5 text-xs text-slate-500 sm:mt-1 sm:text-sm">
                    Dados do envio que está retornando da empresa terceirizada.
                </p>

            </div>

            <div class="space-y-4 p-4 sm:space-y-5 sm:p-6">

                <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2 lg:grid-cols-4 sm:gap-4">

                    {{-- Empresa --}}
                    <div>

                        <label class="text-xs font-medium text-slate-500">
                            Empresa Terceirizada
                        </label>

                        <p class="mt-0.5 text-sm font-semibold text-slate-700">
                            {{ $maintenanceShipment->company?->nome ?? ($maintenanceShipment->company?->name ?? '—') }}
                        </p>

                    </div>


                    {{-- Data do envio --}}
                    <div>

                        <label class="text-xs font-medium text-slate-500">
                            Data do Envio
                        </label>

                        <p class="mt-0.5 text-sm text-slate-700">
                            {{ $maintenanceShipment->sent_at?->format('d/m/Y H:i') ?? '—' }}
                        </p>

                    </div>


                    {{-- Nota fiscal de envio --}}
                    <div>

                        <label class="text-xs font-medium text-slate-500">
                            Nota Fiscal de Envio
                        </label>

                        <p class="mt-0.5 text-sm text-slate-700">
                            {{ $maintenanceShipment->invoice_number ?: 'Não informada' }}
                        </p>

                    </div>


                    {{-- Enviado por --}}
                    <div>

                        <label class="text-xs font-medium text-slate-500">
                            Enviado por
                        </label>

                        <p class="mt-0.5 text-sm text-slate-700">
                            {{ $maintenanceShipment->sender?->name ?? '—' }}
                        </p>

                    </div>

                </div>


                {{-- Defeito --}}
                <div>

                    <label class="text-xs font-medium text-slate-500">
                        Defeito Informado
                    </label>

                    <p class="mt-0.5 text-xs leading-relaxed text-slate-700 sm:mt-1 sm:text-sm">
                        {{ $maintenanceShipment->defect_description }}
                    </p>

                </div>


                {{-- Observação do envio --}}
                @if ($maintenanceShipment->observation)
                    <div>

                        <label class="text-xs font-medium text-slate-500">
                            Observação do Envio
                        </label>

                        <p class="mt-0.5 text-xs leading-relaxed text-slate-700 sm:mt-1 sm:text-sm">
                            {{ $maintenanceShipment->observation }}
                        </p>

                    </div>
                @endif

            </div>

        </x-cards.card>


        {{-- Formulário de recebimento --}}
        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

                <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                    Dados do Recebimento
                </h2>

                <p class="mt-0.5 text-xs text-slate-500 sm:mt-1 sm:text-sm">
                    Informe os dados disponíveis no momento da devolução.
                    O usuário e a data do recebimento serão registrados automaticamente.
                </p>

            </div>


            <form action="{{ route('reparos_externos.receber.store', $maintenanceShipment) }}" method="POST"
                class="space-y-4 p-4 sm:space-y-6 sm:p-6">
                @csrf


                <div class="grid grid-cols-1 gap-4 sm:gap-5">

                    {{-- Filial de Recebimento --}}
                    <div>
                        <label for="receiving_branch_id" class="block text-xs font-medium text-slate-700 sm:text-sm">
                            Filial de Recebimento
                        </label>

                        <select id="receiving_branch_id" name="receiving_branch_id" required
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-xs text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm">
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
                            <p class="mt-1 text-xs text-red-600 sm:mt-1.5 sm:text-sm">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Nota fiscal de retorno --}}
                    <x-forms.input name="invoice_number" label="Nota Fiscal de Retorno" placeholder="Opcional"
                        maxlength="50" :value="old('invoice_number')" />


                    {{-- Observação --}}
                    <x-forms.textarea name="receiving_observation" label="Observação do Recebimento"
                        placeholder="Informe alguma observação sobre o recebimento, se necessário."
                        rows="4">{{ old('receiving_observation') }}</x-forms.textarea>

                </div>
                {{-- Aviso --}}
                <div class="rounded-lg border border-blue-200 bg-blue-50/80 p-3.5 sm:p-4">

                    <div class="flex items-start gap-2.5 sm:gap-3">

                        <span class="shrink-0 text-base sm:text-lg">
                            ℹ️
                        </span>

                        <div>

                            <h3 class="text-xs font-semibold text-blue-900 sm:text-sm">
                                Confirmação do recebimento
                            </h3>

                            <p class="mt-0.5 text-xs leading-relaxed text-blue-800 sm:mt-1 sm:text-sm">
                                Ao confirmar o recebimento, o envio será marcado como
                                retornado e a ordem de serviço passará para validação.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Ações --}}
                <div
                    class="flex flex-col-reverse gap-2.5 border-t border-slate-200 pt-4 sm:flex-row sm:justify-end sm:gap-3 sm:pt-5">

                    <x-buttons.secondary :href="route('reparos_externos.show', $maintenanceShipment->maintenanceOrder)" class="w-full justify-center sm:w-auto">
                        Cancelar
                    </x-buttons.secondary>


                    <x-buttons.primary type="submit" class="w-full justify-center sm:w-auto">
                        Receber Equipamento
                    </x-buttons.primary>

                </div>

            </form>

        </x-cards.card>

    </div>

@endsection
