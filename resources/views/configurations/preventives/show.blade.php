@extends('layout.app')

@section('content')

    <div class="w-full space-y-6 px-4 py-4 sm:px-6 lg:px-8">

        {{-- CABEÇALHO --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl font-semibold text-slate-900 sm:text-2xl">
                        Preventiva #{{ $preventive->id }}
                    </h1>

                    @php
                        $isNova = strtolower($preventive->status?->label() ?? $preventive->status) === 'nova';
                        $statusClass = $isNova
                            ? 'border border-slate-300 bg-white text-slate-700'
                            : ($preventive->status?->colorClass() ?? 'border border-slate-200 bg-slate-100 text-slate-600');
                    @endphp

                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold shadow-sm {{ $statusClass }}">
                        {{ $preventive->status?->label() ?? $preventive->status }}
                    </span>
                </div>

                <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                    Detalhes e histórico da preventiva.
                </p>
            </div>

            <div class="flex items-center">
                <a href="{{ route('preventivas.index') }}"
                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 active:scale-[0.98] sm:w-auto sm:text-sm">
                    <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Voltar</span>
                </a>
            </div>
        </div>

        {{-- DADOS PRINCIPAIS --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 bg-slate-50/50 px-4 py-3.5 sm:px-6 sm:py-4">
                <h2 class="text-sm font-semibold text-slate-800 sm:text-base">Dados da preventiva</h2>
            </div>
            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 lg:grid-cols-3 sm:p-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Filial</p>
                    <p class="mt-1 text-sm font-medium text-slate-900">{{ $preventive->branch?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tipo de preventiva</p>
                    <p class="mt-1 text-sm font-medium text-slate-900">{{ $preventive->preventiveType?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Perfil</p>
                    <p class="mt-1 text-sm font-medium text-slate-900">{{ $preventive->preventiveProfile?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Responsável pela execução</p>
                    <p class="mt-1 text-sm font-medium text-slate-900">{{ $preventive->assignedUser?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Criado por</p>
                    <p class="mt-1 text-sm font-medium text-slate-900">{{ $preventive->createdBy?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Ciclo atual</p>
                    <p class="mt-1 text-sm font-medium text-slate-900">{{ $preventive->current_cycle ?? 1 }}</p>
                </div>
            </div>
        </div>

        {{-- HISTÓRICO DE DATAS --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 bg-slate-50/50 px-4 py-3.5 sm:px-6 sm:py-4">
                <h2 class="text-sm font-semibold text-slate-800 sm:text-base">Histórico de datas</h2>
            </div>
            <div class="grid grid-cols-2 gap-4 p-4 sm:grid-cols-2 lg:grid-cols-4 sm:p-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Data programada</p>
                    <p class="mt-1 text-sm font-medium text-slate-900">{{ $preventive->start_date?->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Iniciada em</p>
                    <p class="mt-1 text-sm font-medium text-slate-900">{{ $preventive->start_at?->format('d/m/Y H:i') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Finalizada em</p>
                    <p class="mt-1 text-sm font-medium text-slate-900">{{ $preventive->completed_at?->format('d/m/Y H:i') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Validada em</p>
                    <p class="mt-1 text-sm font-medium text-slate-900">{{ $preventive->approved_at?->format('d/m/Y H:i') ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- VALIDAÇÃO --}}
        @if ($preventive->approved_at || $preventive->approvedBy)
            <div class="overflow-hidden rounded-xl border border-green-200 bg-white shadow-sm">
                <div class="border-b border-green-100 bg-green-50/60 px-4 py-3.5 sm:px-6 sm:py-4">
                    <h2 class="text-sm font-semibold text-green-900 sm:text-base">Validação da preventiva</h2>
                </div>
                <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 sm:p-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Validada por</p>
                        <p class="mt-1 text-sm font-medium text-slate-900">{{ $preventive->approvedBy?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Data da validação</p>
                        <p class="mt-1 text-sm font-medium text-slate-900">{{ $preventive->approved_at?->format('d/m/Y H:i') ?? '—' }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- INFORMAÇÕES DO REGISTRO --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 bg-slate-50/50 px-4 py-3.5 sm:px-6 sm:py-4">
                <h2 class="text-sm font-semibold text-slate-800 sm:text-base">Informações do Registro</h2>
            </div>
            <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-3 sm:p-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">ID</p>
                    <p class="mt-1 text-sm text-slate-900">#{{ $preventive->id }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Status Geral</p>
                    <p class="mt-1 text-sm font-medium text-slate-900">{{ $preventive->status?->label() ?? $preventive->status }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Criada em</p>
                    <p class="mt-1 text-sm text-slate-900">{{ $preventive->created_at?->format('d/m/Y H:i') ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- INCLUSÃO DO PARTIAL DOS CICLOS --}}
        @include(
    'configurations.preventives.partials.cycles',
    ['cycles' => $cycles]
)

    </div>

@endsection
