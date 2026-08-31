<div class="divide-y divide-slate-200">

    @forelse ($profiles as $profile)

        <div class="p-4 space-y-3">

            {{-- Nome e Status --}}
            <div class="flex items-start justify-between gap-3">

                <div>
                    <span class="text-xs text-slate-500 uppercase font-semibold tracking-wider">
                        Perfil
                    </span>

                    <h3 class="text-sm font-semibold text-slate-900 mt-0.5">
                        {{ $profile->name }}
                    </h3>

                    @if ($profile->preventiveType)
                        <p class="text-xs text-slate-500 mt-0.5">
                            Tipo: {{ $profile->preventiveType->name }}
                        </p>
                    @endif
                </div>

                @if ($profile->active)

                    <span class="inline-flex shrink-0 items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                        Ativo
                    </span>

                @else

                    <span class="inline-flex shrink-0 items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                        Inativo
                    </span>

                @endif

            </div>

            {{-- Filiais --}}
            <div class="space-y-1">

                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Filiais Participantes
                </span>

                <div class="flex flex-wrap gap-1.5 pt-0.5">

                    @forelse ($profile->branches as $profileBranch)

                        <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700">
                            {{ $profileBranch->branch->name ?? $profileBranch->name }}
                        </span>

                    @empty

                        <span class="text-xs text-slate-400 italic">
                            Nenhuma filial vinculada
                        </span>

                    @endforelse

                </div>

            </div>

            {{-- Ações Mobile --}}
            <div class="pt-2 flex flex-wrap items-center gap-2 border-t border-slate-100">

                {{-- Editar --}}
                @can('update', $profile)

                    <x-buttons.secondary
                        :href="route('configuracoes.perfis-preventivas.edit', $profile)"
                        class="flex-1 justify-center"
                    >
                        Editar
                    </x-buttons.secondary>

                @endcan

                {{-- Ativar / Inativar --}}
                @can('toggleActive', $profile)

                    <form
                        method="POST"
                        action="{{ route('configuracoes.perfis-preventivas.toggle-active', $profile) }}"
                        class="flex-1"
                    >

                        @csrf
                        @method('PATCH')

                        @if ($profile->active)

                            <x-buttons.danger
                                type="submit"
                                class="w-full justify-center"
                            >
                                Inativar
                            </x-buttons.danger>

                        @else

                            <x-buttons.primary
                                type="submit"
                                class="w-full justify-center"
                            >
                                Ativar
                            </x-buttons.primary>

                        @endif

                    </form>

                @endcan

            </div>

        </div>

    @empty

        <div class="px-4 py-8 text-center text-xs text-slate-500">
            Nenhum perfil de preventiva cadastrado.
        </div>

    @endforelse

</div>
