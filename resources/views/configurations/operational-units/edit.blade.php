@extends('layout.app')

@section('title', 'Editar Unidade Operacional')

@section('content')

    <div class="space-y-6">

        {{-- PAGE HEADER --}}
        <x-layout.page-header
            title="Editar Unidade Operacional"
            description="Altere os dados e a configuração da unidade operacional."
        >
            <x-slot:breadcrumb>
                Dashboard / Configurações / Unidades Operacionais / Editar
            </x-slot:breadcrumb>
        </x-layout.page-header>

        {{-- ERROS DE VALIDAÇÃO --}}
        @if ($errors->any())

            <x-alerts.error title="Não foi possível atualizar a unidade">

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

        {{-- FORMULÁRIO --}}
        <form
            id="operational-unit-form"
            action="{{ route('configuracoes.unidades-operacionais.update', $operationalUnit) }}"
            method="POST"

            data-unit-types="{{ json_encode($unitTypesData) }}"
            data-operational-profiles="{{ json_encode($operationalProfilesData) }}"

            data-current-unit-type="{{ $operationalUnit->unit_type_id }}"
            data-current-profile="{{ $operationalUnit->operational_profile_id }}"
        >

            @csrf

            @method('PUT')

            @include('configurations.operational-units.partials._form', [
                'mode' => 'edit',
                'operationalUnit' => $operationalUnit,
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
                    Salvar Alterações
                </x-buttons.primary>

            </div>

        </form>

    </div>

@endsection

@vite('resources/js/operational-unit/operationalUnit.js')
