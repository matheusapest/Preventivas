@extends('layout.app')

@section('title', 'Nova Preventiva')

@section('content')

    <div class="space-y-4 sm:space-y-6">

        {{-- PAGE HEADER --}}
        <x-layout.page-header
            title="Nova Preventiva"
            description="Crie uma nova preventiva a partir de um perfil configurado."
        >
            <x-slot:breadcrumb>
                Dashboard / Preventivas / Nova Preventiva
            </x-slot:breadcrumb>
        </x-layout.page-header>


        {{-- FEEDBACK DE ERRO --}}
        @if (session('error'))
            <x-alerts.error title="Não foi possível realizar a operação.">
                {{ session('error') }}
            </x-alerts.error>
        @endif


        {{-- FEEDBACK DE SUCESSO --}}
        @if (session('success'))
            <x-alerts.success title="Operação concluída">
                {{ session('success') }}
            </x-alerts.success>
        @endif


        {{-- ERROS DE VALIDAÇÃO --}}
        @if ($errors->any())
            <x-alerts.error title="Não foi possível concluir a operação.">

                <p class="mb-2 text-xs sm:text-sm">
                    Corrija os seguintes problemas antes de continuar:
                </p>

                <ul class="space-y-1.5">
                    @foreach ($errors->all() as $error)
                        <li class="flex items-start gap-2 text-xs sm:text-sm">
                            <span class="mt-1 h-1 w-1 flex-none rounded-full bg-current"></span>
                            <span>{{ $error }}</span>
                        </li>
                    @endforeach
                </ul>

            </x-alerts.error>
        @endif


        {{-- FORMULÁRIO --}}
        <x-cards.card class="overflow-hidden">

            {{-- HEADER DO FORMULÁRIO --}}
            <div class="border-b border-slate-200 bg-slate-50/60 px-4 py-5 sm:px-6 lg:px-8">

                <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
                    Dados da preventiva
                </h2>

                <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                    Selecione a filial, configuração e responsável pela execução.
                </p>

            </div>


            @include('configurations.preventives.partials.form', [

                'preventive' => null,

                // Dados carregados inicialmente pelo Controller.
                'branches' => $branches,
                'users' => $users,

                // Dados carregados dinamicamente pelo JavaScript.
                'profiles' => collect(),

                'formAction' => route('preventivas.store'),
                'formMethod' => 'POST',
                'submitLabel' => 'Criar preventiva',

            ])

        </x-cards.card>

    </div>

@endsection

@vite('resources/js/preventive/create.js')
