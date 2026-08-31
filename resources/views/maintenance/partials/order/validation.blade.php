<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                    Validação
                </h2>
                <p class="mt-0.5 text-xs text-slate-500 sm:mt-1 sm:text-sm">
                    Histórico das avaliações técnicas realizadas no ciclo atual.
                </p>
            </div>
        </div>
    </div>

    @php
        /*
         * Considera o envio mais recente como o ciclo atual.
         */
        $latestShipment = $maintenanceOrder->shipments
            ->sortByDesc('sequence')
            ->first();

        $receipt = $latestShipment?->receipt;

        /*
         * Ordena as validações cronologicamente
         */
        $validations = $receipt?->validations
            ->sortBy(function ($validation) {
                return [
                    $validation->validated_at?->timestamp ?? 0,
                    $validation->id,
                ];
            })
            ->values()
            ?? collect();

        /*
         * A validação mais recente é a última da coleção cronológica.
         */
        $latestValidation = $validations->last();
    @endphp

    {{-- Nenhum recebimento --}}
    @if (!$receipt)

        <div class="p-4 sm:p-6">
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3.5 sm:p-4">
                <p class="text-xs font-semibold text-slate-700 sm:text-sm sm:font-medium">
                    A validação ainda não está disponível.
                </p>
                <p class="mt-0.5 text-xs text-slate-500">
                    O equipamento precisa ser recebido antes de ser validado.
                </p>
            </div>
        </div>

    {{-- Recebido, mas ainda não validado --}}
    @elseif ($validations->isEmpty())

        <div class="p-4 sm:p-6">
            <div class="rounded-lg border border-blue-200 bg-blue-50 p-3.5 sm:p-4">
                <p class="text-xs font-semibold text-blue-800 sm:text-sm sm:font-medium">
                    Aguardando validação.
                </p>
                <p class="mt-0.5 text-xs text-blue-700">
                    O recebimento foi registrado e o equipamento aguarda avaliação técnica.
                </p>
            </div>
        </div>

    {{-- Histórico de validações --}}
    @else

        <div class="space-y-3.5 p-4 sm:space-y-5 sm:p-6">

            @foreach ($validations->reverse()->values() as $index => $validation)

                @php
                    $validationNumber = $validations->count() - $index;
                    $isLatest = $index === 0;
                    $validationStatus = $validation->validation_status;

                    $validationClasses = match ($validationStatus) {
                        \App\Enums\MaintenanceValidationStatus::APPROVED => 'bg-emerald-100 text-emerald-700',
                        \App\Enums\MaintenanceValidationStatus::REJECTED => 'bg-red-100 text-red-700',
                        \App\Enums\MaintenanceValidationStatus::NO_REPAIR => 'bg-slate-100 text-slate-700',
                        default => 'bg-slate-100 text-slate-700',
                    };
                @endphp

                <div class="overflow-hidden rounded-xl border {{ $isLatest ? 'border-blue-200 bg-white shadow-sm' : 'border-slate-200 bg-slate-50/50' }}">

                    {{-- Cabeçalho da validação --}}
                    <div class="flex flex-row items-center justify-between gap-2 border-b border-slate-200 px-3.5 py-2.5 sm:px-5 sm:py-3">
                        <div>
                            <p class="text-xs font-semibold text-slate-800 sm:text-sm">
                                Validação {{ $validationNumber }}
                                @if ($isLatest)
                                    <span class="ml-1 inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold text-blue-700 sm:text-[11px]">
                                        Atual
                                    </span>
                                @endif
                            </p>

                            <p class="mt-0.5 text-[11px] text-slate-500 sm:text-xs">
                                {{ $validation->validated_at?->format('d/m/Y H:i') ?? 'Data não informada' }}
                            </p>
                        </div>

                        <span class="inline-flex shrink-0 items-center rounded-full px-2 py-0.5 text-xs font-semibold sm:px-2.5 sm:py-1 {{ $validationClasses }}">
                            {{ $validationStatus?->label() ?? 'Não informado' }}
                        </span>
                    </div>

                    <div class="p-3.5 sm:p-5">

                        {{-- Dados da validação --}}
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-5">

                            {{-- Validado por --}}
                            <div>
                                <span class="text-xs font-medium text-slate-500">
                                    Validado por
                                </span>
                                <p class="mt-0.5 text-sm font-medium text-slate-800">
                                    {{ $validation->validator?->name ?? 'Não informado' }}
                                </p>
                            </div>

                            {{-- Data --}}
                            <div>
                                <span class="text-xs font-medium text-slate-500">
                                    Validado em
                                </span>
                                <p class="mt-0.5 text-sm text-slate-700">
                                    {{ $validation->validated_at?->format('d/m/Y H:i') ?? 'Não informado' }}
                                </p>
                            </div>

                        </div>

                        {{-- Decisão de não reenviar --}}
                        @if ($validation->close_without_resend)
                            <div class="mt-3.5 rounded-lg border border-amber-200 bg-amber-50 p-3 sm:mt-5 sm:p-4">
                                <div class="flex items-start gap-2.5 sm:gap-3">
                                    <div class="mt-0.5 shrink-0">
                                        <svg class="h-4 w-4 text-amber-600 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-8.18 14A2 2 0 003.82 21h16.36a2 2 0 001.71-3.14l-8.18-14a2 2 0 00-3.42 0z"/>
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-xs font-semibold text-amber-900 sm:text-sm">
                                            Equipamento não foi reenviado
                                        </p>
                                        <p class="mt-0.5 text-[11px] leading-relaxed text-amber-800 sm:text-xs">
                                            O técnico decidiu não iniciar um novo envio neste ciclo. O equipamento permaneceu em processo de validação para posterior avaliação.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Testes realizados --}}
                        @if ($validation->tests_performed)
                            <div class="mt-3.5 rounded-lg border border-slate-200 bg-slate-50 p-3 sm:mt-5 sm:p-4">
                                <p class="text-xs font-semibold text-slate-600">
                                    Testes realizados
                                </p>
                                <p class="mt-1 text-xs leading-relaxed text-slate-700 sm:text-sm">
                                    {{ $validation->tests_performed }}
                                </p>
                            </div>
                        @endif

                        {{-- Observação da validação --}}
                        @if ($validation->validation_observation)
                            <div class="mt-2.5 rounded-lg border border-slate-200 bg-slate-50 p-3 sm:mt-3 sm:p-4">
                                <p class="text-xs font-semibold text-slate-600">
                                    Observação da validação
                                </p>
                                <p class="mt-1 text-xs leading-relaxed text-slate-700 sm:text-sm">
                                    {{ $validation->validation_observation }}
                                </p>
                            </div>
                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    @endif

</x-cards.card>
