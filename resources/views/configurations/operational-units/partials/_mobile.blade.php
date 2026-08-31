{{-- ========================================================= --}}
{{-- VISÃO MOBILE                                              --}}
{{-- ========================================================= --}}

<div class="divide-y divide-slate-200 md:hidden">

    @forelse ($operationalUnits as $operationalUnit)

        <div class="space-y-3 p-4">

            {{-- Identificação --}}
            <div>

                <div class="flex items-center justify-between gap-3">

                    <span class="truncate text-base font-bold text-slate-900">
                        {{ $operationalUnit->identifier }}
                    </span>

                    @if ($operationalUnit->active)

                        <x-badges.success>
                            Ativo
                        </x-badges.success>

                    @else

                        <x-badges.danger>
                            Inativo
                        </x-badges.danger>

                    @endif

                </div>

            </div>

            {{-- Informações --}}
            <div class="space-y-2 text-sm">

                {{-- Filial --}}
                <div class="flex items-start justify-between gap-4">

                    <span class="text-slate-500">
                        Filial
                    </span>

                    <span class="text-right font-medium text-slate-800">
                        {{ $operationalUnit->branch->name }}
                    </span>

                </div>

                {{-- Tipo --}}
                <div class="flex items-start justify-between gap-4">

                    <span class="text-slate-500">
                        Tipo
                    </span>

                    <span class="text-right font-medium text-slate-800">
                        {{ $operationalUnit->unitType->name }}
                    </span>

                </div>

                {{-- Perfil --}}
                <div class="flex items-start justify-between gap-4">

                    <span class="text-slate-500">
                        Perfil
                    </span>

                    <span class="text-right font-medium text-slate-800">
                        {{ $operationalUnit->operationalProfile->name }}
                    </span>

                </div>

            </div>

            {{-- Ações --}}
            <div class="flex items-center justify-between gap-3 border-t border-slate-100 pt-2">

                @can('update', $operationalUnit)

                    <x-buttons.warning
                        :href="route(
                            'configuracoes.unidades-operacionais.edit',
                            $operationalUnit
                        )"
                        class="flex-1 justify-center text-xs py-2"
                    >
                        Editar
                    </x-buttons.warning>

                @endcan

                @can('toggleActive', $operationalUnit)

                    @if ($operationalUnit->active)

                        <form
                            action="{{ route(
                                'configuracoes.unidades-operacionais.destroy',
                                $operationalUnit
                            ) }}"
                            method="POST"
                            class="flex-1"
                        >

                            @csrf
                            @method('DELETE')

                            <x-buttons.danger
                                type="submit"
                                class="w-full justify-center text-xs py-2"
                            >
                                Inativar
                            </x-buttons.danger>

                        </form>

                    @else

                        <form
                            action="{{ route(
                                'configuracoes.unidades-operacionais.activate',
                                $operationalUnit
                            ) }}"
                            method="POST"
                            class="flex-1"
                        >

                            @csrf
                            @method('PATCH')

                            <x-buttons.success
                                type="submit"
                                class="w-full justify-center text-xs py-2"
                            >
                                Ativar
                            </x-buttons.success>

                        </form>

                    @endif

                @endcan

            </div>

        </div>

    @empty

        <div class="p-6 text-center text-sm text-slate-500">
            Nenhuma unidade operacional cadastrada.
        </div>

    @endforelse

</div>
