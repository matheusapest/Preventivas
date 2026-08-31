@extends('layout.app')

@section('title', 'Receber Equipamentos')

@section('content')

<div class="w-full min-w-0 flex-1 space-y-6 sm:space-y-8 p-4 sm:p-6">

    {{-- Page Header --}}
    <x-layout.page-header
        title="Receber Equipamentos"
        description="Consulte as transferências pendentes e confirme o recebimento dos equipamentos."
    >
        <x-slot:breadcrumb>
            <nav class="flex text-xs text-slate-500 gap-1.5 items-center">
                <span>Dashboard</span>
                <span>/</span>
                <span>Operação</span>
                <span>/</span>
                <a href="{{ route('transferencias.index') }}" class="hover:text-slate-800 transition">Transferências</a>
                <span>/</span>
                <span class="font-medium text-slate-800">Receber</span>
            </nav>
        </x-slot:breadcrumb>

        <x-slot:actions>
            <x-buttons.secondary :href="route('transferencias.index')">
                <svg class="w-4 h-4 mr-1.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Voltar
            </x-buttons.secondary>
        </x-slot:actions>
    </x-layout.page-header>

    {{-- Mensagem de sucesso --}}
    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50/80 p-4 transition-all">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-emerald-900">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    @endif

    {{-- Erros --}}
    @if ($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50/80 p-4 transition-all">
            <div class="flex items-start gap-3">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-rose-900">
                        Não foi possível concluir o recebimento:
                    </p>
                    <ul class="mt-1 list-disc pl-5 text-xs sm:text-sm text-rose-800 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Conteúdo Principal --}}
    <x-cards.card class="w-full min-w-0 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        {{-- Cabeçalho --}}
        <div class="border-b border-slate-200 px-4 py-4 sm:px-6">
            <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                Transferências Pendentes
            </h2>
            <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
                Equipamentos enviados para esta operação e que ainda aguardam recebimento.
            </p>
        </div>

        @if ($transfers->isNotEmpty())

            {{-- 1. VISÃO MOBILE (Cards Empilhados) --}}
            <div class="block sm:hidden divide-y divide-slate-100">
                @foreach ($transfers as $transfer)
                    <div class="p-4 space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 class="font-bold text-slate-800 text-sm">
                                    {{ $transfer->equipment?->name ?? '-' }}
                                </h3>
                                <p class="text-xs text-slate-500">
                                    S/N: {{ $transfer->equipment?->serial_number ?? '-' }}
                                </p>
                            </div>
                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-mono font-semibold text-slate-700 shrink-0">
                                {{ $transfer->equipment?->asset_number ?? '-' }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50 p-2.5 rounded-lg text-slate-600">
                            <div>
                                <span class="block text-[10px] font-medium uppercase tracking-wider text-slate-400">Origem</span>
                                <span class="font-semibold text-slate-700">{{ $transfer->originBranch?->name ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-medium uppercase tracking-wider text-slate-400">Destino</span>
                                <span class="font-semibold text-slate-700">{{ $transfer->destinationBranch?->name ?? '-' }}</span>
                            </div>
                            <div class="col-span-2 pt-1 border-t border-slate-200/60 flex justify-between items-center text-[11px]">
                                <span>Por: <strong class="text-slate-700">{{ $transfer->sentBy?->name ?? '-' }}</strong></span>
                                <span class="font-mono text-slate-500">{{ $transfer->sent_at?->format('d/m/Y H:i') ?? '-' }}</span>
                            </div>
                        </div>

                        {{-- Botão de Ação Visível no Mobile --}}
                        <form action="{{ route('transferencias.receive', ['transfer' => $transfer->id]) }}" method="POST" class="pt-1">
                            @csrf
                            <button
                                type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700 active:bg-emerald-800"
                            >
                                <svg class="h-4 w-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Confirmar Recebimento</span>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            {{-- 2. VISÃO DESKTOP (Tabela Completa) --}}
            <div class="hidden sm:block w-full min-w-0 overflow-x-auto">
                <table class="w-full min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50/80 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 font-semibold">Equipamento</th>
                            <th scope="col" class="px-6 py-3.5 font-semibold">Patrimônio</th>
                            <th scope="col" class="px-6 py-3.5 font-semibold">Origem</th>
                            <th scope="col" class="px-6 py-3.5 font-semibold">Destino</th>
                            <th scope="col" class="px-6 py-3.5 font-semibold">Enviado por</th>
                            <th scope="col" class="px-6 py-3.5 font-semibold">Data</th>
                            <th scope="col" class="px-6 py-3.5 text-right font-semibold">Ação</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($transfers as $transfer)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-6 py-3.5">
                                    <div class="font-semibold text-slate-800">
                                        {{ $transfer->equipment?->name ?? '-' }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        S/N: {{ $transfer->equipment?->serial_number ?? '-' }}
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-6 py-3.5">
                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-mono font-semibold text-slate-700">
                                        {{ $transfer->equipment?->asset_number ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-6 py-3.5 text-slate-700">
                                    {{ $transfer->originBranch?->name ?? '-' }}
                                </td>

                                <td class="px-6 py-3.5 text-slate-700">
                                    {{ $transfer->destinationBranch?->name ?? '-' }}
                                </td>

                                <td class="px-6 py-3.5 text-slate-600">
                                    {{ $transfer->sentBy?->name ?? '-' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-3.5 text-xs text-slate-500 font-mono">
                                    {{ $transfer->sent_at?->format('d/m/Y H:i') ?? '-' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-3.5 text-right">
                                    <form action="{{ route('transferencias.receive', ['transfer' => $transfer->id]) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-medium text-white shadow-sm transition hover:bg-emerald-700 active:bg-emerald-800"
                                        >
                                            <svg class="h-4 w-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span>Receber</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @else

            {{-- State Vazio (Quando não há registros) --}}
            <div class="px-6 py-12 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <p class="mt-3 text-sm font-semibold text-slate-700">
                    Nenhuma transferência pendente
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    Todos os equipamentos enviados para esta filial já foram recebidos.
                </p>
            </div>

        @endif

        {{-- Paginação --}}
        @if ($transfers->hasPages())
            <div class="border-t border-slate-200 px-4 py-3 sm:px-6">
                {{ $transfers->links() }}
            </div>
        @endif

    </x-cards.card>

</div>

@endsection
