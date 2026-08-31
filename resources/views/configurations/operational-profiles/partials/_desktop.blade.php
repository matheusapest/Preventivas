{{-- VISÃO DESKTOP --}}
<div class="hidden max-w-full overflow-x-auto md:block">

    <table class="w-full text-left border-collapse text-xs">

        <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-500">

            <tr>

                <th scope="col" class="px-4 py-3.5 pl-6 min-w-[180px]">
                    Perfil
                </th>

                <th scope="col" class="px-4 py-3.5 min-w-[160px]">
                    Tipo de Unidade
                </th>

                <th scope="col" class="px-4 py-3.5 w-full">
                    Composição do Perfil
                </th>

                <th scope="col" class="px-4 py-3.5 text-center">
                    Status
                </th>

                <th scope="col" class="px-4 py-3.5 text-right pr-6 whitespace-nowrap">
                    Ações
                </th>

            </tr>

        </thead>

        <tbody class="divide-y divide-slate-200 bg-white">

            @forelse ($operationalProfiles as $operationalProfile)

                <tr class="transition-colors hover:bg-slate-50/80">

                    {{-- Perfil --}}
                    <td class="px-4 py-3.5 pl-6 font-semibold text-slate-800">
                        {{ $operationalProfile->name }}
                    </td>

                    {{-- Tipo --}}
                    <td class="px-4 py-3.5 font-medium text-slate-600">
                        {{ $operationalProfile->unitType->name }}
                    </td>

                    {{-- Composição --}}
                    <td class="px-4 py-3.5">
                        @include(
                            'configurations.operational-profiles.partials._composition',
                            [
                                'operationalProfile' => $operationalProfile,
                                'mobile' => false,
                            ]
                        )
                    </td>

                    {{-- Status --}}
                    <td class="px-4 py-3.5 text-center whitespace-nowrap">
                        @if ($operationalProfile->active)
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
                    <td class="px-4 py-3.5 text-right pr-6 whitespace-nowrap">

                        <div class="flex items-center justify-end gap-1.5">

                            @can('update', $operationalProfile)
                                <x-buttons.warning
                                    :href="route('configuracoes.perfis-operacionais.edit', $operationalProfile)"
                                    class="text-xs px-2.5 py-1"
                                >
                                    Editar
                                </x-buttons.warning>
                            @endcan

                            @can('toggleActive', $operationalProfile)
                                @if ($operationalProfile->active)
                                    <form
                                        action="{{ route('configuracoes.perfis-operacionais.destroy', $operationalProfile) }}"
                                        method="POST"
                                        class="inline"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <x-buttons.danger type="submit" class="text-xs px-2.5 py-1">
                                            Inativar
                                        </x-buttons.danger>
                                    </form>
                                @else
                                    <form
                                        action="{{ route('configuracoes.perfis-operacionais.activate', $operationalProfile) }}"
                                        method="POST"
                                        class="inline"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <x-buttons.success type="submit" class="text-xs px-2.5 py-1">
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
                        colspan="5"
                        class="px-6 py-12 text-center text-slate-500 text-xs font-medium"
                    >
                        Nenhum perfil operacional cadastrado.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>
