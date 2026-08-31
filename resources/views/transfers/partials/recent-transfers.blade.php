<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Cabeçalho --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">
        <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
            Transferências Efetuadas
        </h2>
    </div>

    {{-- 1. VISÃO MOBILE (md:hidden) --}}
    <div class="divide-y divide-slate-200 md:hidden">
        @forelse ($transfers as $transfer)
            <div class="p-4 space-y-3">

                {{-- Equipamento + Patrimônio + Badge Status --}}
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <h3 class="text-base font-bold leading-snug text-slate-900">
                            {{ $transfer->equipment?->name ?? 'Equipamento não informado' }}
                        </h3>
                        @if ($transfer->equipment?->asset_number)
                            <p class="mt-0.5 text-xs text-slate-500">
                                Patrimônio: <span class="font-semibold text-slate-700">{{ $transfer->equipment->asset_number }}</span>
                            </p>
                        @endif
                    </div>

                    {{-- Status Badge --}}
                    <div class="shrink-0">
                        @if($transfer->status === \App\Enums\TransferStatus::SENT)
                            <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Em Trânsito</span>
                        @elseif($transfer->status === \App\Enums\TransferStatus::RECEIVED)
                            <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Recebido</span>
                        @else
                            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-700">{{ $transfer->status?->value ?? '-' }}</span>
                        @endif
                    </div>
                </div>

                {{-- Fluxo Origem → Destino --}}
                <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50 p-2.5 text-xs text-slate-600">
                    <div>
                        <span class="block font-semibold text-slate-800">Origem:</span>
                        {{ $transfer->originBranch?->name ?? '-' }}
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                    <div class="text-right">
                        <span class="block font-semibold text-slate-800">Destino:</span>
                        {{ $transfer->destinationBranch?->name ?? '-' }}
                    </div>
                </div>

                {{-- BLOCO 1: DETALHES DO ENVIO --}}
                <div class="rounded-lg border border-slate-200 bg-slate-50/50 p-2.5 text-xs space-y-1">
                    <div class="flex items-center justify-between text-slate-500 font-semibold border-b border-slate-200/60 pb-1 mb-1">
                        <span class="text-slate-700">📤 Etapa de Envio</span>
                        <span>{{ $transfer->sent_at?->format('d/m/Y H:i') ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Enviado por:</span>
                        <span class="font-medium text-slate-800">{{ $transfer->sentBy?->name ?? '-' }}</span>
                    </div>
                </div>

                {{-- BLOCO 2: DETALHES DO RECEBIMENTO --}}
                @if ($transfer->status === \App\Enums\TransferStatus::RECEIVED)
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50/60 p-2.5 text-xs space-y-1">
                        <div class="flex items-center justify-between text-emerald-800 font-semibold border-b border-emerald-200/60 pb-1 mb-1">
                            <span>📥 Etapa de Recebimento</span>
                            <span>{{ $transfer->received_at?->format('d/m/Y H:i') ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-emerald-900">
                            <span>Recebido por:</span>
                            <span class="font-medium text-emerald-800">{{ $transfer->receivedBy?->name ?? 'Não informado' }}</span>
                        </div>
                    </div>
                @else
                    <div class="rounded-lg border border-dashed border-amber-200 bg-amber-50/40 p-2 text-center text-xs text-amber-700 font-medium">
                        ⏳ Aguardando confirmação de recebimento no destino
                    </div>
                @endif

            </div>
        @empty
            <div class="p-6 text-center text-sm text-slate-500">
                Nenhuma transferência encontrada.
            </div>
        @endforelse
    </div>

    {{-- 2. VISÃO DESKTOP (hidden md:block) --}}
    <div class="hidden max-w-full overflow-x-auto md:block">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Equipamento</th>
                    <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Patrimônio</th>
                    <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Origem / Destino</th>
                    <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Status</th>
                    <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Enviado Por</th>
                    <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Data Envio</th>
                    <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Recebido Por</th>
                    <th scope="col" class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-600">Data Recebimento</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse ($transfers as $transfer)
                    <tr class="transition hover:bg-slate-50/80">
                        {{-- Equipamento --}}
                        <td class="px-5 py-4 font-medium text-slate-900">
                            {{ $transfer->equipment?->name ?? '-' }}
                        </td>

                        {{-- Patrimônio --}}
                        <td class="px-5 py-4 font-medium text-slate-700">
                            {{ $transfer->equipment?->asset_number ?? '-' }}
                        </td>

                        {{-- Origem / Destino --}}
                        <td class="px-5 py-4 text-xs text-slate-600 whitespace-nowrap">
                            <span class="font-medium text-slate-800">{{ $transfer->originBranch?->name ?? '-' }}</span>
                            <span class="text-slate-400 mx-1">→</span>
                            <span class="font-medium text-slate-800">{{ $transfer->destinationBranch?->name ?? '-' }}</span>
                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($transfer->status === \App\Enums\TransferStatus::SENT)
                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Em Trânsito</span>
                            @elseif($transfer->status === \App\Enums\TransferStatus::RECEIVED)
                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">Recebido</span>
                            @else
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $transfer->status?->value ?? '-' }}</span>
                            @endif
                        </td>

                        {{-- Dados do Envio --}}
                        <td class="px-5 py-4 text-slate-600">{{ $transfer->sentBy?->name ?? '-' }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-slate-600 text-xs">{{ $transfer->sent_at?->format('d/m/Y H:i') ?? '-' }}</td>

                        {{-- Dados do Recebimento --}}
                        <td class="px-5 py-4 text-slate-600">
                            @if ($transfer->status === \App\Enums\TransferStatus::RECEIVED)
                                <span class="text-emerald-700 font-medium">{{ $transfer->receivedBy?->name ?? 'Não informado' }}</span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-xs">
                            @if ($transfer->status === \App\Enums\TransferStatus::RECEIVED)
                                <span class="text-emerald-700 font-medium">{{ $transfer->received_at?->format('d/m/Y H:i') ?? '-' }}</span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center text-slate-500">
                            Nenhuma transferência encontrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginação --}}
    @if ($transfers instanceof \Illuminate\Contracts\Pagination\Paginator && $transfers->hasPages())
        <div class="border-t border-slate-200 px-4 py-3 sm:px-6 sm:py-4">
            {{ $transfers->links() }}
        </div>
    @endif

</x-cards.card>
