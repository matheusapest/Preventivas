@extends('layout.app')

@section('title', 'Validar Reparo')

@section('content')

    <div class="w-full min-w-0 flex-1 space-y-4 p-3.5 sm:space-y-6 sm:p-6">

        <x-layout.page-header title="Validar Reparo"
            description="Registre o resultado da avaliação do equipamento recebido do reparo externo.">
            <x-slot:breadcrumb>

                <nav class="flex flex-wrap items-center gap-1.5 text-xs text-slate-500">

                    <span class="hidden sm:inline">Dashboard</span>

                    <span class="hidden sm:inline">/</span>

                    <a href="{{ route('reparos_externos.index') }}" class="transition hover:text-slate-800">
                        Reparo Externo
                    </a>

                    <span>/</span>

                    <a href="{{ route('reparos_externos.show', $maintenanceReceipt->maintenanceShipment->maintenanceOrder) }}"
                        class="transition hover:text-slate-800">
                        OS {{ $maintenanceReceipt->maintenanceShipment->maintenanceOrder->id }}
                    </a>

                    <span>/</span>

                    <span class="font-medium text-slate-800">
                        Validar Reparo
                    </span>

                </nav>

            </x-slot:breadcrumb>

            <x-slot:actions>

                <x-buttons.secondary :href="route('reparos_externos.show', $maintenanceReceipt->maintenanceShipment->maintenanceOrder)">
                    Voltar
                </x-buttons.secondary>

            </x-slot:actions>
        </x-layout.page-header>


        {{-- Mensagem de sucesso --}}
        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-3.5 sm:p-4">

                <p class="text-xs font-medium text-emerald-800 sm:text-sm">
                    {{ session('success') }}
                </p>

            </div>
        @endif


        {{-- Erros --}}
        @if ($errors->any())

            <x-alerts.error title="Não foi possível validar o reparo.">

                <ul class="list-disc space-y-1 pl-4 text-xs sm:pl-5 sm:text-sm">

                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach

                </ul>

            </x-alerts.error>

        @endif


        <div class="w-full min-w-0 space-y-4 sm:space-y-6">


            {{-- Informações do equipamento --}}
            <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-3.5 py-3 sm:px-6 sm:py-4">

                    <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                        Equipamento
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-500 sm:mt-1 sm:text-sm">
                        Confira os dados do equipamento antes de registrar a validação.
                    </p>

                </div>


                <div class="p-3.5 sm:p-6">

                    <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2 lg:grid-cols-4 sm:gap-5">


                        {{-- Patrimônio --}}
                        <div>

                            <span class="text-xs font-medium text-slate-500">
                                Patrimônio
                            </span>

                            <p class="mt-0.5 text-xs font-bold text-slate-800 sm:text-sm">
                                {{ $maintenanceReceipt->maintenanceShipment->maintenanceOrder->equipment->asset_number ?? 'Não informado' }}
                            </p>

                        </div>


                        {{-- Equipamento --}}
                        <div>

                            <span class="text-xs font-medium text-slate-500">
                                Equipamento
                            </span>

                            <p class="mt-0.5 text-xs font-semibold text-slate-800 sm:text-sm">
                                {{ $maintenanceReceipt->maintenanceShipment->maintenanceOrder->equipment->name ?? 'Não informado' }}
                            </p>

                        </div>


                        {{-- Modelo --}}
                        <div>

                            <span class="text-xs font-medium text-slate-500">
                                Modelo
                            </span>

                            <p class="mt-0.5 text-xs text-slate-700 sm:text-sm">
                                {{ $maintenanceReceipt->maintenanceShipment->maintenanceOrder->equipment->equipmentModel?->name ??
                                    'Não informado' }}
                            </p>

                        </div>


                        {{-- Número de série --}}
                        <div>

                            <span class="text-xs font-medium text-slate-500">
                                Número de Série
                            </span>

                            <p class="mt-0.5 font-mono text-xs text-slate-700 sm:text-sm">
                                {{ $maintenanceReceipt->maintenanceShipment->maintenanceOrder->equipment->serial_number ?? 'Não informado' }}
                            </p>

                        </div>


                        {{-- Categoria --}}
                        <div>

                            <span class="text-xs font-medium text-slate-500">
                                Categoria
                            </span>

                            <p class="mt-0.5 text-xs text-slate-700 sm:text-sm">
                                {{ $maintenanceReceipt->maintenanceShipment->maintenanceOrder->equipment->equipmentModel?->category?->name ??
                                    'Não informado' }}
                            </p>

                        </div>


                        {{-- Fabricante --}}
                        <div>

                            <span class="text-xs font-medium text-slate-500">
                                Fabricante
                            </span>

                            <p class="mt-0.5 text-xs text-slate-700 sm:text-sm">
                                {{ $maintenanceReceipt->maintenanceShipment->maintenanceOrder->equipment->equipmentModel?->manufacturer
                                    ?->name ?? 'Não informado' }}
                            </p>

                        </div>


                        {{-- Filial --}}
                        <div>

                            <span class="text-xs font-medium text-slate-500">
                                Filial Atual
                            </span>

                            <p class="mt-0.5 text-xs font-medium text-slate-800 sm:text-sm">
                                {{ $maintenanceReceipt->maintenanceShipment->maintenanceOrder->equipment->branch?->name ?? 'Não informado' }}
                            </p>

                        </div>


                        {{-- OS --}}
                        <div>

                            <span class="text-xs font-medium text-slate-500">
                                Ordem de Serviço
                            </span>

                            <p class="mt-0.5 text-xs font-bold text-slate-800 sm:text-sm">
                                OS
                                {{ $maintenanceReceipt->maintenanceShipment->maintenanceOrder->id }}
                            </p>

                        </div>

                    </div>

                </div>

            </x-cards.card>


            {{-- Informações do recebimento --}}
            <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-3.5 py-3 sm:px-6 sm:py-4">

                    <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                        Informações do Recebimento
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-500 sm:mt-1 sm:text-sm">
                        Dados registrados no retorno do equipamento.
                    </p>

                </div>


                <div class="p-3.5 sm:p-6">

                    <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-3 sm:gap-5">


                        {{-- Nota fiscal --}}
                        <div>

                            <span class="text-xs font-medium text-slate-500">
                                Nota Fiscal de Retorno
                            </span>

                            <p class="mt-0.5 text-xs font-medium text-slate-800 sm:text-sm">
                                {{ $maintenanceReceipt->invoice_number ?: 'Não informada' }}
                            </p>

                        </div>


                        {{-- Recebido por --}}
                        <div>

                            <span class="text-xs font-medium text-slate-500">
                                Recebido por
                            </span>

                            <p class="mt-0.5 text-xs font-medium text-slate-800 sm:text-sm">
                                {{ $maintenanceReceipt->receiver?->name ?? 'Não informado' }}
                            </p>

                        </div>


                        {{-- Data recebimento --}}
                        <div>

                            <span class="text-xs font-medium text-slate-500">
                                Recebido em
                            </span>

                            <p class="mt-0.5 text-xs font-medium text-slate-800 sm:text-sm">
                                {{ $maintenanceReceipt->received_at?->format('d/m/Y H:i') ?? 'Não informado' }}
                            </p>

                        </div>

                    </div>


                    @if ($maintenanceReceipt->receiving_observation)
                        <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-3.5 sm:mt-5 sm:p-4">

                            <p class="text-xs font-semibold text-slate-600">
                                Observação do recebimento
                            </p>

                            <p class="mt-0.5 text-xs leading-relaxed text-slate-700 sm:mt-1 sm:text-sm">
                                {{ $maintenanceReceipt->receiving_observation }}
                            </p>

                        </div>
                    @endif

                </div>

            </x-cards.card>


            {{-- Formulário de validação --}}
            <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-3.5 py-3 sm:px-6 sm:py-4">

                    <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                        Resultado da Validação
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-500 sm:mt-1 sm:text-sm">
                        Informe o resultado da avaliação técnica realizada no equipamento.
                    </p>

                </div>


                <form method="POST"
                    action="{{ route('reparos_externos.validar.store', $maintenanceReceipt) }}">

                    @csrf


                    <div class="space-y-4 p-3.5 sm:space-y-6 sm:p-6">


                        {{-- Resultado --}}
                        <div>

                            <label for="validation_status" class="block text-xs font-medium text-slate-700 sm:text-sm">
                                Resultado da validação
                            </label>

                            <select id="validation_status" name="validation_status" required
                                class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-xs text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm">

                                <option value="">
                                    Selecione o resultado
                                </option>

                                @foreach (\App\Enums\MaintenanceValidationStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(old('validation_status') === $status->value)>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach

                            </select>

                            @error('validation_status')
                                <p class="mt-1 text-xs text-red-600 sm:mt-1.5 sm:text-sm">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Encerrar OS sem novo envio --}}
                        <div id="close-without-resend-container"
                            class="hidden rounded-lg border border-amber-200 bg-amber-50 p-3.5 sm:p-4">

                            <label class="flex cursor-pointer items-start gap-2.5 sm:gap-3">

                                <input type="checkbox" id="close_without_resend" name="close_without_resend" value="1"
                                    @checked(old('close_without_resend'))
                                    class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">

                                <span>

                                    <span class="block text-xs font-semibold text-amber-900 sm:text-sm">
                                        Não reenviar equipamento
                                    </span>

                                    <span
                                        class="mt-0.5 block text-[11px] leading-relaxed text-amber-800 sm:mt-1 sm:text-xs">
                                        Marque esta opção quando o equipamento não será
                                        reenviado para a terceirizada e o reparo será tratado
                                        de outra forma.
                                    </span>

                                </span>

                            </label>

                            @error('close_without_resend')
                                <p class="mt-2 text-xs text-red-600 sm:text-sm">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Testes realizados --}}
                        <div>

                            <label for="tests_performed" class="block text-xs font-medium text-slate-700 sm:text-sm">
                                Testes realizados
                            </label>

                            <textarea id="tests_performed" name="tests_performed" rows="4" required
                                placeholder="Descreva os testes realizados no equipamento..."
                                class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-xs text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm">{{ old('tests_performed') }}</textarea>

                            @error('tests_performed')
                                <p class="mt-1 text-xs text-red-600 sm:mt-1.5 sm:text-sm">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Observação --}}
                        <div>

                            <label for="validation_observation" class="block text-xs font-medium text-slate-700 sm:text-sm">
                                Observação da validação
                            </label>

                            <textarea id="validation_observation" name="validation_observation" rows="3"
                                placeholder="Informe alguma observação adicional sobre a validação..."
                                class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-xs text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 sm:text-sm">{{ old('validation_observation') }}</textarea>

                            @error('validation_observation')
                                <p class="mt-1 text-xs text-red-600 sm:mt-1.5 sm:text-sm">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>


                    {{-- Ações --}}
                    <div
                        class="flex flex-col-reverse gap-2.5 border-t border-slate-200 px-3.5 py-3.5 sm:flex-row sm:items-center sm:justify-end sm:gap-3 sm:px-6 sm:py-4">

                        <x-buttons.secondary class="w-full justify-center sm:w-auto" :href="route(
                            'reparos_externos.show',
                            $maintenanceReceipt->maintenanceShipment->maintenanceOrder,
                        )">
                            Cancelar
                        </x-buttons.secondary>

                        <x-buttons.primary class="w-full justify-center sm:w-auto" type="submit">
                            Registrar Validação
                        </x-buttons.primary>

                    </div>

                </form>

            </x-cards.card>

        </div>

    </div>


    {{-- ============================================================
         MODAL DE DECISÃO DE REENVIO
         ============================================================ --}}

    @if (session('ask_resend'))
        <div id="resend-decision-modal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="resend-decision-title"
            role="dialog" aria-modal="true">

            {{-- Fundo --}}
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"></div>


            {{-- Conteúdo --}}
            <div class="relative flex min-h-full items-center justify-center p-3.5 sm:p-4">

                <div class="w-full max-w-md overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl">

                    {{-- Cabeçalho --}}
                    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-5 sm:py-4">

                        <div class="flex items-start gap-3">

                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-700 sm:h-10 sm:w-10">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" class="h-4 w-4 sm:h-5 sm:w-5"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m0 3.75h.007v.007H12v-.007ZM10.29 3.86l-8.12 14a2 2 0 001.73 3h16.2a2 2 0 001.73-3l-8.12-14a2 2 0 00-3.46 0Z" />
                                </svg>

                            </div>


                            <div>

                                <h2 id="resend-decision-title" class="text-sm font-semibold text-slate-900 sm:text-base">
                                    Reparo reprovado
                                </h2>

                                <p class="mt-0.5 text-xs text-slate-500 sm:mt-1 sm:text-sm">
                                    A validação técnica foi registrada como reprovada.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Corpo --}}
                    <div class="px-4 py-4 sm:px-5 sm:py-5">

                        <p class="text-xs leading-relaxed text-slate-700 sm:text-sm">
                            Deseja reenviar o equipamento para a terceirizada agora?
                        </p>

                        <p class="mt-1.5 text-xs leading-relaxed text-slate-500 sm:mt-2">
                            Se optar por não reenviar agora, a OS permanecerá em
                            <strong class="font-semibold text-slate-700">
                                Aguardando Reenvio
                            </strong>
                            e poderá ser reenviada posteriormente.
                        </p>

                    </div>


                    {{-- Ações --}}
                    <div
                        class="flex flex-col-reverse gap-2.5 border-t border-slate-200 bg-slate-50 px-4 py-3.5 sm:flex-row sm:justify-end sm:gap-3 sm:px-5 sm:py-4">

                        {{-- Agora não --}}
                        <a href="{{ route('reparos_externos.show', $maintenanceReceipt->maintenanceShipment->maintenanceOrder) }}"
                            class="inline-flex w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:w-auto sm:py-2.5 sm:text-sm">
                            Agora não
                        </a>


                        {{-- Reenviar --}}
                        <a href="{{ route('reparos_externos.reenviar.form', $maintenanceReceipt->maintenanceShipment->maintenanceOrder) }}"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/30 sm:w-auto sm:py-2.5 sm:text-sm">
                            Reenviar equipamento
                        </a>

                    </div>

                </div>

            </div>

        </div>
    @endif

@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const validationStatus =
                document.getElementById('validation_status');

            const closeWithoutResendContainer =
                document.getElementById(
                    'close-without-resend-container'
                );

            const closeWithoutResend =
                document.getElementById(
                    'close_without_resend'
                );


            /*
             * Controla a exibição da opção de encerrar
             * a OS sem realizar um novo envio.
             */
            function updateCloseWithoutResend() {

                if (
                    !validationStatus ||
                    !closeWithoutResendContainer ||
                    !closeWithoutResend
                ) {
                    return;
                }

                const isRejected =
                    validationStatus.value ===
                    '{{ \App\Enums\MaintenanceValidationStatus::REJECTED->value }}';


                if (isRejected) {

                    closeWithoutResendContainer.classList.remove('hidden');

                    return;
                }


                closeWithoutResendContainer.classList.add('hidden');

                closeWithoutResend.checked = false;
            }


            if (validationStatus) {

                validationStatus.addEventListener(
                    'change',
                    updateCloseWithoutResend
                );

                /*
                 * Executa no carregamento da página.
                 *
                 * Isso mantém o comportamento correto caso
                 * o Laravel retorne à tela após uma validação
                 * que apresentou erro.
                 */
                updateCloseWithoutResend();
            }

        });
    </script>
@endpush
