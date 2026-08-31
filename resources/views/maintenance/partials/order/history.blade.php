<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Header --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">
        <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
            Histórico da Ordem de Serviço
        </h2>
        <p class="mt-0.5 text-xs text-slate-500 sm:mt-1 sm:text-sm">
            Histórico dos ciclos de envio, recebimento e validação do equipamento.
        </p>
    </div>

    @if ($maintenanceOrder->shipments->isNotEmpty())

        <div class="divide-y divide-slate-200">

            @foreach ($maintenanceOrder->shipments->sortByDesc('sequence') as $shipment)
                @php
                    $receipt = $shipment->receipt;

                    $validations =
                        $receipt?->validations
                            ->sortBy(function ($validation) {
                                return [$validation->validated_at?->timestamp ?? 0, $validation->id];
                            })
                            ->values() ?? collect();

                    $latestValidation = $validations->last();
                @endphp

                <div class="p-4 sm:p-6">

                    {{-- CABEÇALHO DO CICLO --}}
                    <div class="flex flex-row items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-slate-800">
                                    Ciclo #{{ $shipment->sequence }}
                                </span>

                                @if ($shipment->status === \App\Enums\MaintenanceShipmentStatus::SENT)
                                    <span
                                        class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700">
                                        Enviado
                                    </span>
                                @elseif ($shipment->status === \App\Enums\MaintenanceShipmentStatus::RETURNED)
                                    <span
                                        class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                                        Retornado
                                    </span>
                                @endif
                            </div>

                            <p class="mt-0.5 text-xs text-slate-500">
                                Enviado em {{ $shipment->sent_at?->format('d/m/Y H:i') ?? 'Não informado' }}
                            </p>
                        </div>
                    </div>


                    {{-- ENVIO --}}
                    <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-3.5 sm:mt-5 sm:p-4">
                        <h3
                            class="text-xs font-bold uppercase tracking-wider text-slate-500 sm:text-sm sm:normal-case sm:tracking-normal sm:text-slate-800">
                            Envio
                        </h3>

                        <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:grid-cols-4">
                            <div>
                                <span class="text-xs font-medium text-slate-500">Empresa</span>
                                <p class="mt-0.5 text-sm font-medium text-slate-700 sm:font-normal">
                                    {{ $shipment->company?->name ?? 'Não informado' }}
                                </p>
                            </div>

                            <div>
                                <span class="text-xs font-medium text-slate-500">Nota Fiscal</span>
                                <p class="mt-0.5 text-sm font-medium text-slate-700 sm:font-normal">
                                    {{ $shipment->invoice_number ?: 'Não informada' }}
                                </p>
                            </div>

                            <div>
                                <span class="text-xs font-medium text-slate-500">Filial de Envio</span>
                                <p class="mt-0.5 text-sm text-slate-700">
                                    {{ $shipment->originBranch?->name ?? 'Não informado' }}
                                </p>
                            </div>

                            <div>
                                <span class="text-xs font-medium text-slate-500">Enviado por</span>
                                <p class="mt-0.5 text-sm text-slate-700">
                                    {{ $shipment->sender?->name ?? 'Não informado' }}
                                </p>
                            </div>
                        </div>

                        @if ($shipment->defect_description)
                            <div class="mt-3 border-t border-slate-200/60 pt-2.5 sm:border-0 sm:pt-0 sm:mt-4">
                                <span class="text-xs font-medium text-slate-500">Defeito informado</span>
                                <p class="mt-0.5 text-sm leading-relaxed text-slate-700">
                                    {{ $shipment->defect_description }}
                                </p>
                            </div>
                        @endif
                    </div>


                    {{-- RECEBIMENTO --}}
                    @if ($receipt)
                        <div class="mt-3.5 rounded-lg border border-emerald-200 bg-emerald-50/50 p-3.5 sm:mt-4 sm:p-4">
                            <div class="flex items-center justify-between gap-3">
                                <h3
                                    class="text-xs font-bold uppercase tracking-wider text-slate-800 sm:text-sm sm:normal-case sm:tracking-normal">
                                    Recebimento
                                </h3>

                                <span
                                    class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                                    Recebido
                                </span>
                            </div>

                            <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:grid-cols-3">
                                <div>
                                    <span class="text-xs font-medium text-slate-500">NF de Retorno</span>
                                    <p class="mt-0.5 text-sm text-slate-700">
                                        {{ $receipt->invoice_number ?: 'Não informada' }}
                                    </p>
                                </div>

                                <div>
                                    <span class="text-xs font-medium text-slate-500">Recebido por</span>
                                    <p class="mt-0.5 text-sm text-slate-700">
                                        {{ $receipt->receiver?->name ?? 'Não informado' }}
                                    </p>
                                </div>

                                <div>
                                    <span class="text-xs font-medium text-slate-500">Recebido em</span>
                                    <p class="mt-0.5 text-sm text-slate-700">
                                        {{ $receipt->received_at?->format('d/m/Y H:i') ?? 'Não informado' }}
                                    </p>
                                </div>

                                {{-- Filial de Recebimento --}}
                                <div>
                                    <span class="text-xs font-medium text-slate-500">
                                        Filial de Recebimento
                                    </span>

                                    <p class="mt-0.5 text-sm text-slate-700">
                                        {{ $receipt->receivingBranch?->name ?? 'Não informado' }}
                                    </p>
                                </div>
                            </div>

                            @if ($receipt->receiving_observation)
                                <div class="mt-3 border-t border-emerald-200/60 pt-2.5 sm:border-0 sm:pt-0 sm:mt-4">
                                    <span class="text-xs font-medium text-slate-500">Observação</span>
                                    <p class="mt-0.5 text-sm leading-relaxed text-slate-700">
                                        {{ $receipt->receiving_observation }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="mt-3.5 rounded-lg border border-amber-200 bg-amber-50 p-3.5 sm:mt-4 sm:p-4">
                            <p class="text-xs font-semibold text-amber-800 sm:text-sm sm:font-medium">
                                Aguardando recebimento.
                            </p>
                        </div>
                    @endif


                    {{-- VALIDAÇÕES DO CICLO --}}
                    @if ($receipt)
                        @if ($validations->isNotEmpty())
                            <div class="mt-3.5 rounded-lg border border-blue-200 bg-blue-50/30 p-3.5 sm:mt-4 sm:p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <h3
                                            class="text-xs font-bold uppercase tracking-wider text-slate-800 sm:text-sm sm:normal-case sm:tracking-normal">
                                            Validações
                                        </h3>
                                        <p class="mt-0.5 text-xs text-slate-500">
                                            {{ $validations->count() }}
                                            {{ $validations->count() === 1 ? 'avaliação registrada' : 'avaliações registradas' }}
                                            neste ciclo.
                                        </p>
                                    </div>
                                </div>

                                {{-- Histórico das validações --}}
                                <div class="mt-3 space-y-3 sm:mt-4">
                                    @foreach ($validations->reverse()->values() as $index => $validation)
                                        @php
                                            $validationNumber = $validations->count() - $index;
                                            $isLatest = $index === 0;
                                            $validationStatus = $validation->validation_status;

                                            $validationClasses = match ($validationStatus) {
                                                \App\Enums\MaintenanceValidationStatus::APPROVED
                                                    => 'bg-emerald-100 text-emerald-700',
                                                \App\Enums\MaintenanceValidationStatus::REJECTED
                                                    => 'bg-red-100 text-red-700',
                                                \App\Enums\MaintenanceValidationStatus::NO_REPAIR
                                                    => 'bg-slate-100 text-slate-700',
                                                default => 'bg-slate-100 text-slate-700',
                                            };
                                        @endphp

                                        <div
                                            class="overflow-hidden rounded-lg border {{ $isLatest ? 'border-blue-200 bg-white' : 'border-slate-200 bg-white' }}">

                                            {{-- Cabeçalho da validação --}}
                                            <div
                                                class="flex flex-row items-center justify-between gap-2 border-b border-slate-200 px-3.5 py-2.5 sm:px-4 sm:py-3">
                                                <div>
                                                    <p class="text-xs font-semibold text-slate-800 sm:text-sm">
                                                        Validação {{ $validationNumber }}
                                                        @if ($isLatest)
                                                            <span
                                                                class="ml-1 inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold text-blue-700 sm:text-[11px]">
                                                                Atual
                                                            </span>
                                                        @endif
                                                    </p>
                                                    <p class="mt-0.5 text-[11px] text-slate-500 sm:text-xs">
                                                        {{ $validation->validated_at?->format('d/m/Y H:i') ?? 'Data não informada' }}
                                                    </p>
                                                </div>

                                                <span
                                                    class="inline-flex shrink-0 items-center rounded-full px-2 py-0.5 text-xs font-semibold sm:px-2.5 sm:py-1 {{ $validationClasses }}">
                                                    {{ $validationStatus?->label() ?? 'Não informado' }}
                                                </span>
                                            </div>

                                            <div class="p-3.5 sm:p-4">
                                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4">
                                                    <div>
                                                        <span class="text-xs font-medium text-slate-500">Validado
                                                            por</span>
                                                        <p class="mt-0.5 text-sm text-slate-700">
                                                            {{ $validation->validator?->name ?? 'Não informado' }}
                                                        </p>
                                                    </div>

                                                    <div>
                                                        <span class="text-xs font-medium text-slate-500">Validado
                                                            em</span>
                                                        <p class="mt-0.5 text-sm text-slate-700">
                                                            {{ $validation->validated_at?->format('d/m/Y H:i') ?? 'Não informado' }}
                                                        </p>
                                                    </div>
                                                </div>

                                                @if ($validation->close_without_resend)
                                                    <div
                                                        class="mt-3 rounded-lg border border-amber-200 bg-amber-50 p-2.5 sm:mt-4 sm:p-3">
                                                        <div class="flex items-start gap-2.5">
                                                            <div class="mt-0.5 shrink-0">
                                                                <svg class="h-4 w-4 text-amber-600 sm:h-5 sm:w-5"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M12 9v2m0 4h.01M10.29 3.86l-8.18 14A2 2 0 003.82 21h16.36a2 2 0 001.71-3.14l-8.18-14a2 2 0 00-3.42 0z" />
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <p
                                                                    class="text-xs font-semibold text-amber-900 sm:text-sm">
                                                                    Equipamento não foi reenviado
                                                                </p>
                                                                <p
                                                                    class="mt-0.5 text-[11px] leading-relaxed text-amber-800 sm:text-xs">
                                                                    O técnico decidiu não iniciar um novo envio neste
                                                                    ciclo. O equipamento permaneceu em processo de
                                                                    validação para posterior avaliação.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($validation->tests_performed)
                                                    <div
                                                        class="mt-3 border-t border-slate-100 pt-2.5 sm:border-0 sm:pt-0 sm:mt-4">
                                                        <span class="text-xs font-medium text-slate-500">Testes
                                                            realizados</span>
                                                        <p class="mt-0.5 text-sm leading-relaxed text-slate-700">
                                                            {{ $validation->tests_performed }}
                                                        </p>
                                                    </div>
                                                @endif

                                                @if ($validation->validation_observation)
                                                    <div
                                                        class="mt-3 border-t border-slate-100 pt-2.5 sm:border-0 sm:pt-0 sm:mt-4">
                                                        <span class="text-xs font-medium text-slate-500">Observação da
                                                            validação</span>
                                                        <p class="mt-0.5 text-sm leading-relaxed text-slate-700">
                                                            {{ $validation->validation_observation }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="mt-3.5 rounded-lg border border-blue-200 bg-blue-50 p-3.5 sm:mt-4 sm:p-4">
                                <p class="text-xs font-semibold text-blue-800 sm:text-sm sm:font-medium">
                                    Aguardando validação.
                                </p>
                                <p class="mt-0.5 text-xs text-blue-700">
                                    O recebimento foi registrado e o equipamento aguarda avaliação técnica.
                                </p>
                            </div>
                        @endif
                    @endif

                </div>
            @endforeach

        </div>
    @else
        <div class="p-4 text-xs text-slate-500 sm:p-6 sm:text-sm">
            Nenhum histórico de envio registrado para esta ordem de serviço.
        </div>

    @endif

</x-cards.card>
