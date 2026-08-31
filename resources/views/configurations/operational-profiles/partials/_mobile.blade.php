{{-- VISÃO MOBILE --}}
<div class="divide-y divide-slate-200 md:hidden">

    @forelse ($operationalProfiles as $operationalProfile)

        <div class="space-y-3.5 p-4">

            {{-- Nome + Status na mesma linha --}}
            <div class="flex items-start justify-between gap-3">

                <div>
                    <h3 class="text-sm font-bold text-slate-900 break-words">
                        {{ $operationalProfile->name }}
                    </h3>

                    <p class="mt-0.5 text-xs font-medium text-slate-500">
                        Tipo: <span class="text-slate-700">{{ $operationalProfile->unitType->name }}</span>
                    </p>
                </div>

                <div class="shrink-0">
                    @if ($operationalProfile->active)
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


            {{-- Composição do Perfil --}}
            <div class="space-y-1.5 rounded-lg bg-slate-50/80 p-3">

                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Composição do Perfil
                </span>

                <div class="pt-1">
                    @include(
                        'configurations.operational-profiles.partials._composition',
                        [
                            'operationalProfile' => $operationalProfile,
                            'mobile' => true,
                        ]
                    )
                </div>

            </div>


            {{-- Ações --}}
            <div class="flex items-center gap-2 border-t border-slate-100 pt-3">

                @can('update', $operationalProfile)

                    <x-buttons.warning
                        :href="route('configuracoes.perfis-operacionais.edit', $operationalProfile)"
                        class="w-full justify-center text-xs py-2"
                    >
                        Editar
                    </x-buttons.warning>

                @endcan


                @can('toggleActive', $operationalProfile)

                    @if ($operationalProfile->active)

                        <form
                            action="{{ route('configuracoes.perfis-operacionais.destroy', $operationalProfile) }}"
                            method="POST"
                            class="w-full"
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
                            action="{{ route('configuracoes.perfis-operacionais.activate', $operationalProfile)"
                            method="POST"
                            class="w-full"
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

        <div class="p-8 text-center text-xs font-medium text-slate-500">
            Nenhum perfil operacional cadastrado.
        </div>

    @endforelse

</div>
