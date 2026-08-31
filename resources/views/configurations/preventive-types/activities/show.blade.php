@extends('layout.app')

@section('title', 'Visualizar Atividade')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- CABEÇALHO                                                 --}}
        {{-- ========================================================= --}}

        <x-layout.page-header
            title="Visualizar Atividade"
            description="Visualize os detalhes e configurações desta atividade."
        >
            <x-slot:breadcrumb>
                Dashboard / Configurações / Tipos de Preventiva / Atividades / Visualizar
            </x-slot:breadcrumb>

            <x-slot:actions>

                <x-buttons.secondary
                    :href="route(
                        'configuracoes.tipos-preventivas.activities.index',
                        $preventiveType
                    )"
                >
                    Voltar
                </x-buttons.secondary>

                @can('update', $activity)

                    <x-buttons.primary
                        :href="route(
                            'configuracoes.tipos-preventivas.activities.edit',
                            [
                                'preventiveType' => $preventiveType,
                                'activity' => $activity,
                            ]
                        )"
                    >
                        Editar
                    </x-buttons.primary>

                @endcan

            </x-slot:actions>

        </x-layout.page-header>


        {{-- ========================================================= --}}
        {{-- DADOS DA ATIVIDADE                                        --}}
        {{-- ========================================================= --}}

        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div>

                {{-- ================================================= --}}
                {{-- CABEÇALHO DO CARD                                 --}}
                {{-- ================================================= --}}

                <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

                    <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
                        Dados da Atividade
                    </h2>

                    <p class="mt-0.5 text-xs leading-relaxed text-slate-500 sm:text-sm">
                        Informações cadastradas para esta atividade.
                    </p>

                </div>


                {{-- ================================================= --}}
                {{-- CORPO                                              --}}
                {{-- ================================================= --}}

                <div class="space-y-6 p-4 sm:p-6">

                    {{-- ================================================= --}}
                    {{-- NOME, TIPO E CATEGORIA                             --}}
                    {{-- ================================================= --}}

                    <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-3">

                        {{-- Nome --}}

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Nome
                            </label>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-700">
                                {{ $activity->name }}
                            </div>

                        </div>


                        {{-- Tipo --}}

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Tipo
                            </label>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5">

                                <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">
                                    {{ $activity->type->label() }}
                                </span>

                            </div>

                        </div>


                        {{-- Categoria --}}

                        <div>

                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Categoria
                            </label>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5">

                                @if ($activity->activityCategory)

                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">
                                        {{ $activity->activityCategory->name }}
                                    </span>

                                @else

                                    <span class="text-sm text-slate-400">
                                        Nenhuma categoria informada.
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- TIPO DE PREVENTIVA                                --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Tipo de Preventiva
                        </label>

                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-700">
                            {{ $preventiveType->name }}
                        </div>

                        <p class="mt-1 text-xs text-slate-500">
                            Tipo de preventiva ao qual esta atividade está vinculada.
                        </p>

                    </div>


                    {{-- ================================================= --}}
                    {{-- DESCRIÇÃO                                          --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Descrição
                        </label>

                        <div class="min-h-24 rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-3 text-sm leading-relaxed text-slate-700">

                            @if ($activity->description)

                                {{ $activity->description }}

                            @else

                                <span class="text-slate-400">
                                    Nenhuma descrição informada.
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- STATUS                                             --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Status
                        </label>

                        <div>

                            @if ($activity->active)

                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                    Ativa
                                </span>

                            @else

                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                    Inativa
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </x-cards.card>


        {{-- ========================================================= --}}
        {{-- INFORMAÇÕES DO REGISTRO                                   --}}
        {{-- ========================================================= --}}

        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div>

                <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

                    <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
                        Informações do Registro
                    </h2>

                    <p class="mt-0.5 text-xs leading-relaxed text-slate-500 sm:text-sm">
                        Informações de controle do cadastro.
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 sm:gap-6 sm:p-6">

                    {{-- ID --}}

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            ID
                        </label>

                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-700">
                            {{ $activity->id }}
                        </div>

                    </div>


                    {{-- Criado em --}}

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Cadastrado em
                        </label>

                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-700">
                            {{ $activity->created_at?->format('d/m/Y H:i') ?? '—' }}
                        </div>

                    </div>


                    {{-- Atualizado em --}}

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Última atualização
                        </label>

                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-700">
                            {{ $activity->updated_at?->format('d/m/Y H:i') ?? '—' }}
                        </div>

                    </div>

                </div>

            </div>

        </x-cards.card>

    </div>

@endsection
