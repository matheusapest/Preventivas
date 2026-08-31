<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">

        <thead class="border-b border-slate-200 bg-slate-50">
            <tr>
                <th scope="col"
                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                    Nome
                </th>

                <th scope="col"
                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                    Tipo
                </th>

                <th scope="col"
                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                    Filiais
                </th>

                <th scope="col"
                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                    Status
                </th>

                <th scope="col"
                    class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">
                    Ações
                </th>
            </tr>
        </thead>

        <tbody class="divide-y divide-slate-200 bg-white">

            @forelse ($profiles as $profile)

                <tr class="hover:bg-slate-50">

                    {{-- =====================================================
                         NOME
                         ===================================================== --}}
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">
                        {{ $profile->name }}
                    </td>

                    {{-- =====================================================
                         TIPO
                         ===================================================== --}}
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                        {{ $profile->preventiveType->name ?? '-' }}
                    </td>

                    {{-- =====================================================
                         FILIAIS
                         ===================================================== --}}
                    <td class="px-6 py-4">
                        <div class="flex max-w-xs flex-wrap gap-1">

                            @forelse ($profile->branches as $profileBranch)
                                <span
                                    class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700">
                                    {{ $profileBranch->branch->name ?? $profileBranch->name }}
                                </span>

                            @empty

                                <span class="text-xs italic text-slate-400">
                                    Nenhuma
                                </span>
                            @endforelse

                        </div>
                    </td>

                    {{-- =====================================================
                         STATUS
                         ===================================================== --}}
                    <td class="whitespace-nowrap px-6 py-4">

                        @if ($profile->active)
                            <span
                                class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                Ativo
                            </span>
                        @else
                            <span
                                class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                Inativo
                            </span>
                        @endif

                    </td>

                    {{-- Ações --}}
                    <td class="whitespace-nowrap px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">

                            <a href="{{ route('configuracoes.perfis-preventivas.regras.index', $profile) }}"
                                class="inline-flex items-center justify-center rounded-lg border border-orange-500 bg-orange-500 px-4 py-2 text-sm font-medium text-white transition duration-200 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2">
                                Regras
                            </a>
                            {{-- Editar --}}
                            @can('update', $profile)
                                <x-buttons.secondary :href="route('configuracoes.perfis-preventivas.edit', $profile)">
                                    Editar
                                </x-buttons.secondary>
                            @endcan



                            {{-- Ativar / Inativar --}}
                            @can('toggleActive', $profile)
                                <form method="POST"
                                    action="{{ route('configuracoes.perfis-preventivas.toggle-active', $profile) }}"
                                    class="inline-block">
                                    @csrf
                                    @method('PATCH')

                                    @if ($profile->active)
                                        <x-buttons.danger type="submit">
                                            Inativar
                                        </x-buttons.danger>
                                    @else
                                        <x-buttons.primary type="submit">
                                            Ativar
                                        </x-buttons.primary>
                                    @endif
                                </form>
                            @endcan

                        </div>
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">
                        Nenhum perfil de preventiva cadastrado.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>
</div>
