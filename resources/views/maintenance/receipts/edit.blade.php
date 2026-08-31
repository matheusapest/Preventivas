@extends('layout.app')

@section('title', 'Editar Recebimento')

@section('content')

    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- Cabeçalho --}}
        <div class="mb-6">

            <div class="flex items-center gap-3">

                <a
                    href="{{ route(
                        'reparos_externos.show',
                        $maintenanceReceipt->maintenanceShipment->maintenanceOrder
                    ) }}"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-300 bg-white text-slate-600 transition hover:bg-slate-50"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                </a>

                <div>
                    <h1 class="text-xl font-semibold text-slate-800">
                        Editar Recebimento
                    </h1>

                    <p class="mt-0.5 text-sm text-slate-500">
                        Corrija os dados logísticos do recebimento do equipamento.
                    </p>
                </div>

            </div>

        </div>


        {{-- Informações do recebimento --}}
        <x-cards.card class="mb-5 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

                <h2 class="text-base font-semibold text-slate-800">
                    Informações do Recebimento
                </h2>

                <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
                    Consulte os dados do recebimento antes de realizar a correção.
                </p>

            </div>

            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-3 sm:p-6">

                {{-- Ciclo --}}
                <div>
                    <span class="text-xs font-medium text-slate-500">
                        Ciclo de Envio
                    </span>

                    <p class="mt-0.5 text-sm font-semibold text-slate-800">
                        #{{ $maintenanceReceipt->maintenanceShipment?->sequence ?? '-' }}
                    </p>
                </div>


                {{-- Empresa --}}
                <div>
                    <span class="text-xs font-medium text-slate-500">
                        Empresa Terceirizada
                    </span>

                    <p class="mt-0.5 text-sm font-semibold text-slate-800">
                        {{ $maintenanceReceipt->maintenanceShipment?->company?->name ?? 'Não informado' }}
                    </p>
                </div>


                {{-- Recebido por --}}
                <div>
                    <span class="text-xs font-medium text-slate-500">
                        Recebido por
                    </span>

                    <p class="mt-0.5 text-sm font-semibold text-slate-800">
                        {{ $maintenanceReceipt->receiver?->name ?? 'Não informado' }}
                    </p>
                </div>


                {{-- Data do recebimento --}}
                <div>
                    <span class="text-xs font-medium text-slate-500">
                        Recebido em
                    </span>

                    <p class="mt-0.5 text-sm text-slate-700">
                        {{ $maintenanceReceipt->received_at?->format('d/m/Y H:i') ?? 'Não informado' }}
                    </p>
                </div>


                {{-- Filial de origem --}}
                <div>
                    <span class="text-xs font-medium text-slate-500">
                        Filial de Envio
                    </span>

                    <p class="mt-0.5 text-sm text-slate-700">
                        {{ $maintenanceReceipt->maintenanceShipment?->originBranch?->name ?? 'Não informado' }}
                    </p>
                </div>

            </div>

        </x-cards.card>


        {{-- Dados logísticos --}}
        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

                <h2 class="text-base font-semibold text-slate-800">
                    Dados Logísticos
                </h2>

                <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
                    Apenas a filial de recebimento e a nota fiscal podem ser alteradas.
                </p>

            </div>

            <form
                method="POST"
                action="{{ route(
                    'reparos_externos.recebimentos.editar.update',
                    $maintenanceReceipt
                ) }}"
                class="p-4 sm:p-6"
            >

                @csrf
                @method('PUT')

                @include(
                    'maintenance.components.logistics-form',
                    [
                        'branches' => $branches,
                        'branchName' => 'receiving_branch_id',
                        'branchLabel' => 'Filial de Recebimento',
                        'selectedBranchId' => $maintenanceReceipt->receiving_branch_id,
                        'invoiceLabel' => 'Nota Fiscal de Retorno',
                        'invoiceNumber' => $maintenanceReceipt->invoice_number,
                    ]
                )


                {{-- Erro geral --}}
                @error('receipt')
                    <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                        <p class="text-sm text-red-700">
                            {{ $message }}
                        </p>
                    </div>
                @enderror


                {{-- Ações --}}
                <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">

                    <a
                        href="{{ route(
                            'reparos_externos.show',
                            $maintenanceReceipt->maintenanceShipment->maintenanceOrder
                        ) }}"
                        class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800"
                    >
                        Salvar alterações
                    </button>

                </div>

            </form>

        </x-cards.card>

    </div>

@endsection
