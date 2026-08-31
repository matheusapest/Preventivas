@extends('layout.app')

@section('title', 'Nova Unidade Operacional')

@section('content')

    <div class="space-y-6">

        {{-- PAGE HEADER --}}
        <x-layout.page-header
            title="Nova Unidade Operacional"
            description="Cadastre uma nova unidade operacional vinculada a uma filial."
        >
            <x-slot:breadcrumb>
                Dashboard / Configurações / Unidades Operacionais / Nova
            </x-slot:breadcrumb>
        </x-layout.page-header>

        {{-- ERROS DE VALIDAÇÃO --}}
        @if ($errors->any())

            <x-alerts.error title="Não foi possível cadastrar as unidades">

                <div class="space-y-2">

                    <p>
                        Verifique os dados informados antes de continuar.
                    </p>

                    <ul class="list-inside list-disc space-y-1">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </x-alerts.error>

        @endif

        {{-- ================================================================ --}}
        {{-- SELETOR DO MODO DE CADASTRO --}}
        {{-- ================================================================ --}}

        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">

            <label class="flex cursor-pointer items-center gap-3">

                <input
                    type="checkbox"
                    id="multiple-mode"
                    class="rounded border-gray-300"
                >

                <span class="text-sm font-medium text-gray-700">
                    Cadastrar unidades em lote
                </span>

            </label>

            <p class="mt-1 ml-7 text-sm text-gray-500">
                Ative esta opção para cadastrar várias unidades operacionais de uma vez.
            </p>

        </div>

        {{-- ================================================================ --}}
        {{-- CADASTRO INDIVIDUAL --}}
        {{-- ================================================================ --}}

        <div id="single-form-container">

            <form
                id="operational-unit-form"
                action="{{ route('configuracoes.unidades-operacionais.store') }}"
                method="POST"
                data-unit-types="{{ json_encode($unitTypesData) }}"
                data-operational-profiles="{{ json_encode($operationalProfilesData) }}"
                data-current-unit-type=""
                data-current-profile=""
            >

                @csrf

                @include('configurations.operational-units.partials._form', [
                    'mode' => 'create',
                    'operationalUnit' => null,
                    'branches' => $branches,
                    'unitTypesData' => $unitTypesData,
                    'operationalProfilesData' => $operationalProfilesData,
                ])

                {{-- AÇÕES --}}
                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                    <x-buttons.secondary
                        :href="route('configuracoes.unidades-operacionais.index')"
                        class="w-full justify-center sm:w-auto"
                    >
                        Cancelar
                    </x-buttons.secondary>

                    <x-buttons.primary
                        type="submit"
                        class="w-full justify-center sm:w-auto"
                    >
                        Salvar Unidade Operacional
                    </x-buttons.primary>

                </div>

            </form>

        </div>

        {{-- ================================================================ --}}
        {{-- CADASTRO EM LOTE --}}
        {{-- ================================================================ --}}

        <div
            id="multiple-form-container"
            class="hidden"
        >

            <form
                id="operational-unit-multiple-form"
                action="{{ route('configuracoes.unidades-operacionais.store-multiple') }}"
                method="POST"
                data-unit-types="{{ json_encode($unitTypesData) }}"
                data-operational-profiles="{{ json_encode($operationalProfilesData) }}"
            >

                @csrf

                @include('configurations.operational-units.partials._form_multiple', [
                    'mode' => 'create',
                    'branches' => $branches,
                    'unitTypesData' => $unitTypesData,
                    'operationalProfilesData' => $operationalProfilesData,
                ])

                {{-- AÇÕES --}}
                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                    <x-buttons.secondary
                        :href="route('configuracoes.unidades-operacionais.index')"
                        class="w-full justify-center sm:w-auto"
                    >
                        Cancelar
                    </x-buttons.secondary>

                    <x-buttons.primary
                        type="submit"
                        class="w-full justify-center sm:w-auto"
                    >
                        Cadastrar Unidades
                    </x-buttons.primary>

                </div>

            </form>

        </div>

    </div>

@endsection

@vite([
    'resources/js/operational-unit/operationalUnit.js',
    'resources/js/operational-unit/operationalMultipleUnit.js',
    'resources/js/operational-unit/operationalUnitMode.js'
])
