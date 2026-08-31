@extends('layout.app')

@section('title', 'Criar nova regra para o perfil')

@section('content')

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- Erros gerais --}}
        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-300 bg-red-50 px-5 py-4 text-sm text-red-800">
                <div class="font-semibold">
                    Existem erros no formulário.
                </div>

                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Mensagem de erro da sessão --}}
        @if (session('error'))
            <div class="mb-6 rounded-lg border border-red-300 bg-red-50 px-5 py-4 text-sm text-red-800">
                <div class="font-semibold">
                    Não foi possível salvar a regra.
                </div>

                <div class="mt-1">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- Cabeçalho --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

            <div>
                <div class="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Regras do perfil
                </div>

                <h1 class="text-2xl font-semibold text-slate-900">
                    Nova regra
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Configure a regra padrão da filial e, se necessário, suas exceções.
                </p>
            </div>

            <a href="{{ route('configuracoes.perfis-preventivas.regras.index', $preventiveProfile) }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                Voltar
            </a>

        </div>

        {{-- Informações do perfil --}}
        <div class="mb-6 rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="grid grid-cols-1 divide-y divide-slate-200 sm:grid-cols-3 sm:divide-x sm:divide-y-0">

                <div class="p-5">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Perfil
                    </div>

                    <div class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $preventiveProfile->name }}
                    </div>
                </div>

                <div class="p-5">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Tipo de preventiva
                    </div>

                    <div class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $preventiveProfile->preventiveType->name }}
                    </div>
                </div>

                <div class="p-5">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Unidade
                    </div>

                    <div class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $preventiveProfile->preventiveType->unitType->name ?? '-' }}
                    </div>
                </div>

            </div>

        </div>

        {{-- Formulário --}}
        <form method="POST" action="{{ route('configuracoes.perfis-preventivas.regras.store', $preventiveProfile) }}"
            class="space-y-6">

            @csrf

            {{--
                Como este formulário cria a regra padrão da filial,
                o tipo da regra é sempre ALL.
            --}}
            <input type="hidden" name="rule_type" value="all">

            {{-- Configuração da regra --}}
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-5 py-4">

                    <h2 class="text-base font-semibold text-slate-900">
                        Configuração da regra
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Defina a filial e as atividades que farão parte da regra padrão.
                    </p>

                </div>

                <div class="space-y-6 p-5">

                    {{-- Filial --}}
                    <div>

                        <label for="preventive_profile_branch_id" class="mb-2 block text-sm font-medium text-slate-700">
                            Filial
                        </label>

                        <select id="preventive_profile_branch_id" name="preventive_profile_branch_id"
                            class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>

                            <option value="">
                                Selecione a filial
                            </option>

                            @foreach ($branches as $profileBranch)
                                <option value="{{ $profileBranch->id }}" @selected(old('preventive_profile_branch_id') == $profileBranch->id)>
                                    {{ $profileBranch->branch->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('preventive_profile_branch_id')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Regra padrão --}}
                    <div>
                        <div class="mb-3">
                            <h3 class="text-sm font-semibold text-slate-900">
                                Regra padrão
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Estas atividades serão utilizadas por todas as unidades
                                elegíveis da filial.
                            </p>
                        </div>

                        <div class="space-y-4">

                            @forelse ($activityCategories as $category)

                                <div class="overflow-hidden rounded-lg border border-slate-200">

                                    {{-- CATEGORIA --}}
                                    <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
                                        <h4 class="text-sm font-semibold text-slate-800">
                                            {{ $category->name }}
                                        </h4>
                                    </div>

                                    {{-- ATIVIDADES DA CATEGORIA --}}
                                    <div class="divide-y divide-slate-200 bg-white">

                                        @forelse ($category->activities as $activity)
                                            <label class="flex cursor-pointer items-start gap-3 p-4 hover:bg-slate-50">
                                                <input type="checkbox" name="activity_ids[]" value="{{ $activity->id }}"
                                                    @checked(in_array((int) $activity->id, array_map('intval', old('activity_ids', [])), true))
                                                    class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">

                                                <div class="min-w-0">
                                                    <div class="text-sm font-medium text-slate-900">
                                                        {{ $activity->name }}
                                                    </div>

                                                    @if (!empty($activity->description))
                                                        <div class="mt-1 text-xs text-slate-500">
                                                            {{ $activity->description }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </label>

                                        @empty

                                            <div class="px-4 py-4 text-sm text-slate-500">
                                                Nenhuma atividade ativa nesta categoria.
                                            </div>
                                        @endforelse

                                    </div>

                                </div>

                            @empty

                                <div class="rounded-lg border border-dashed border-slate-300 px-6 py-6 text-center">
                                    <p class="text-sm font-medium text-slate-600">
                                        Nenhuma categoria de atividade disponível.
                                    </p>

                                    <p class="mt-1 text-sm text-slate-400">
                                        Não existem atividades ativas disponíveis para configuração.
                                    </p>
                                </div>

                            @endforelse

                        </div>

                        @error('activity_ids')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        @error('activity_ids.*')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

            </div>

            {{-- Informação sobre exceções --}}
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-5">

                <div class="flex gap-3">

                    <div class="shrink-0">

                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>

                    </div>

                    <div>

                        <h3 class="text-sm font-semibold text-blue-900">
                            Exceções por unidade
                        </h3>

                        <p class="mt-1 text-sm leading-6 text-blue-800">
                            A regra criada aqui será a configuração padrão da filial.
                            Depois de salvar, você poderá criar exceções para unidades
                            específicas, caso alguma delas precise de atividades diferentes.
                        </p>

                    </div>

                </div>

            </div>

            {{-- Ações --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a href="{{ route('configuracoes.perfis-preventivas.regras.index', $preventiveProfile) }}"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                    Cancelar
                </a>

                <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                    Salvar regra
                </button>

            </div>

        </form>

    </div>

@endsection
