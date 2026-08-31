@extends('layout.app')

@section('title', 'Números de Filiais')

@section('content')

<div class="w-full min-w-0 flex-1 space-y-6 sm:space-y-8 p-4 sm:p-6">

    {{-- Page Header --}}
    <x-layout.page-header
        title="Números de Filiais"
        description="Gerencie os números disponíveis para utilização no cadastro das filiais."
    >
        <x-slot:breadcrumb>
            <nav class="flex text-xs text-slate-500 gap-1.5 items-center">
                <span>Dashboard</span>
                <span>/</span>
                <span>Cadastros</span>
                <span>/</span>
                <a href="{{ route('filiais.index') }}" class="hover:text-slate-800 transition">Filiais</a>
                <span>/</span>
                <span class="font-medium text-slate-800">Números</span>
            </nav>
        </x-slot:breadcrumb>

        <x-slot:actions>
            <div class="flex flex-wrap gap-2 items-center">
                @can('create', App\Models\BranchCode::class)
                    <x-buttons.secondary :href="route('filiais.index')">
                        <svg class="w-4 h-4 mr-1.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Voltar para Filiais
                    </x-buttons.secondary>

                    <x-buttons.primary :href="route('codigos-filiais.create')">
                        <svg class="w-4 h-4 mr-1.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Novo Número
                    </x-buttons.primary>
                @endcan
            </div>
        </x-slot:actions>
    </x-layout.page-header>

    {{-- Card Conteúdo Principal --}}
    <x-cards.card class="w-full min-w-0 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        @if($branchCodes->isNotEmpty())

            {{-- 1. VISÃO MOBILE (Cards Empilhados com Switch Liga/Desliga) --}}
            <div class="block sm:hidden divide-y divide-slate-100">
                @foreach($branchCodes as $branchCode)
                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-slate-400 font-medium">Código:</span>
                                <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-sm font-mono font-bold text-slate-800">
                                    {{ $branchCode->code }}
                                </span>
                            </div>

                            {{-- TOGGLE SWITCH LIGA/DESLIGA (MOBILE) --}}
                            @can('toggleActive', $branchCode)
                                <form
                                    action="{{ route('codigos-filiais.toggle-active', $branchCode) }}"
                                    method="POST"
                                    class="flex items-center gap-2"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <span class="text-xs font-semibold {{ $branchCode->active ? 'text-emerald-600' : 'text-slate-400' }}">
                                        {{ $branchCode->active ? 'Ativo' : 'Inativo' }}
                                    </span>

                                    <label class="relative inline-flex items-center cursor-pointer select-none">
                                        <input
                                            type="checkbox"
                                            class="sr-only peer"
                                            {{ $branchCode->active ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                        >
                                        {{-- Trilho do Switch --}}
                                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                    </label>
                                </form>
                            @else
                                <div>
                                    @if($branchCode->active)
                                        <x-badges.success>Ativo</x-badges.success>
                                    @else
                                        <x-badges.danger>Inativo</x-badges.danger>
                                    @endif
                                </div>
                            @endcan
                        </div>

                        {{-- Botão de Editar Mobile --}}
                        @can('update', $branchCode)
                            <div class="pt-2 border-t border-slate-100">
                                <x-buttons.warning
                                    :href="route('codigos-filiais.edit', $branchCode)"
                                    class="w-full text-center justify-center py-2 text-xs"
                                >
                                    Editar Número
                                </x-buttons.warning>
                            </div>
                        @endcan
                    </div>
                @endforeach
            </div>

            {{-- 2. VISÃO DESKTOP (Tabela Tradicional) --}}
            <div class="hidden sm:block w-full min-w-0 overflow-x-auto">
                <table class="w-full min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50/80 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 font-semibold">Número / Código</th>
                            <th scope="col" class="px-6 py-3.5 text-center font-semibold">Status</th>
                            <th scope="col" class="px-6 py-3.5 text-center font-semibold">Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach($branchCodes as $branchCode)
                            <tr class="transition hover:bg-slate-50/80">
                                {{-- Código --}}
                                <td class="px-6 py-4 font-mono font-semibold text-slate-900">
                                    {{ $branchCode->code }}
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if($branchCode->active)
                                        <x-badges.success>Ativo</x-badges.success>
                                    @else
                                        <x-badges.danger>Inativo</x-badges.danger>
                                    @endif
                                </td>

                                {{-- Ações --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        @can('update', $branchCode)
                                            <x-buttons.warning :href="route('codigos-filiais.edit', $branchCode)">
                                                Editar
                                            </x-buttons.warning>
                                        @endcan

                                        @can('toggleActive', $branchCode)
                                            <form
                                                action="{{ route('codigos-filiais.toggle-active', $branchCode) }}"
                                                method="POST"
                                                class="inline-block"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                @if($branchCode->active)
                                                    <x-buttons.danger type="submit">
                                                        Inativar
                                                    </x-buttons.danger>
                                                @else
                                                    <x-buttons.success type="submit">
                                                        Ativar
                                                    </x-buttons.success>
                                                @endif
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @else

            {{-- Empty State --}}
            <div class="px-6 py-12 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <p class="mt-3 text-sm font-semibold text-slate-700">
                    Nenhum número de filial cadastrado
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    Clique no botão "Novo Número" para cadastrar o primeiro código de filial.
                </p>
            </div>

        @endif

        {{-- Paginação --}}
        @if ($branchCodes->hasPages())
            <div class="border-t border-slate-200 px-4 py-3 sm:px-6">
                {{ $branchCodes->links() }}
            </div>
        @endif

    </x-cards.card>

</div>

@endsection
