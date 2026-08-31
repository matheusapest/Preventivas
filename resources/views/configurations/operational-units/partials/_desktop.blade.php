{{-- ========================================================= --}}
{{-- VISÃO DESKTOP                                             --}}
{{-- ========================================================= --}}

<div class="hidden max-w-full overflow-x-hidden md:block md:overflow-x-auto">

    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">

        {{-- Cabeçalho --}}
        <thead class="bg-slate-50">

            <tr>

                <th
                    scope="col"
                    class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500"
                >
                    Unidade
                </th>

                <th
                    scope="col"
                    class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500"
                >
                    Filial
                </th>

                <th
                    scope="col"
                    class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500"
                >
                    Tipo de Unidade
                </th>

                <th
                    scope="col"
                    class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500"
                >
                    Perfil Operacional
                </th>

                <th
                    scope="col"
                    class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500"
                >
                    Status
                </th>

                <th
                    scope="col"
                    class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500"
                >
                    Ações
                </th>

            </tr>

        </thead>

        {{-- Dados --}}
        <tbody class="divide-y divide-slate-200 bg-white">

            @forelse ($operationalUnits as $operationalUnit)

                <tr class="transition hover:bg-slate-50/80">

                    {{-- Identificador --}}
                    <td class="px-6 py-4">

                        <div class="font-semibold text-slate-900">
                            {{ $operationalUnit->identifier }}
                        </div>

                    </td>

                    {{-- Filial --}}
                    <td class="px-6 py-4 text-slate-700">
                        {{ $operationalUnit->branch->name }}
                    </td>

                    {{-- Tipo de Unidade --}}
                    <td class="px-6 py-4 text-slate-700">
                        {{ $operationalUnit->unitType->name }}
                    </td>

                    {{-- Perfil Operacional --}}
                    <td class="px-6 py-4">

                        <div class="font-medium text-slate-900">
                            {{ $operationalUnit->operationalProfile->name }}
                        </div>

                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4 text-center">

                        @if ($operationalUnit->active)

                            <x-badges.success>
                                Ativo
                            </x-badges.success>

                        @else

                            <x-badges.danger>
                                Inativo
                            </x-badges.danger>

                        @endif

                    </td>

                    {{-- Ações --}}
                    <td class="px-6 py-4">

                        <div class="flex justify-center gap-2">

                            @can('update', $operationalUnit)

                                <x-buttons.warning
                                    :href="route(
                                        'configuracoes.unidades-operacionais.edit',
                                        $operationalUnit
                                    )"
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
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <x-buttons.danger type="submit">
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
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <x-buttons.success type="submit">
                                            Ativar
                                        </x-buttons.success>

                                    </form>

                                @endif

                            @endcan

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="6"
                        class="px-6 py-8 text-center text-slate-500"
                    >
                        Nenhuma unidade operacional cadastrada.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>
