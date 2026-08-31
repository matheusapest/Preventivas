<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- CABEÇALHO DO CARD --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

        <h2 class="text-base font-semibold text-slate-900">
            Regras configuradas
        </h2>

        <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
            Filiais que já possuem uma configuração definida para este perfil.
        </p>

    </div>


    @if ($rules->isEmpty())

        {{-- ESTADO VAZIO --}}
        <div class="px-4 py-12 text-center sm:px-6">

            <div class="mx-auto max-w-md">

                <h3 class="text-base font-semibold text-slate-900">
                    Nenhuma regra configurada
                </h3>

                <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                    Nenhuma filial possui uma regra configurada para este perfil.
                </p>

                @can('update', $preventiveProfile)

                    <div class="mt-5">

                        <x-buttons.primary
                            :href="route('configuracoes.perfis-preventivas.regras.create', $preventiveProfile)"
                            class="inline-flex justify-center"
                        >
                            Nova Regra
                        </x-buttons.primary>

                    </div>

                @endcan

            </div>

        </div>

    @else

        {{-- MOBILE --}}
        <div class="divide-y divide-slate-200 md:hidden">

            @foreach ($rules as $rule)

                <div class="p-4 space-y-3">

                    <div class="flex items-start justify-between gap-3">

                        <div>

                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Filial
                            </span>

                            <h3 class="mt-0.5 text-sm font-semibold text-slate-900">
                                {{ $rule->preventiveProfileBranch->branch->name ?? 'Filial não identificada' }}
                            </h3>

                        </div>

                        {{-- TIPO DE REGRA --}}
                        @if ($rule->rule_type === 'all' || (is_object($rule->rule_type) && $rule->rule_type->value === 'all'))

                            <span class="inline-flex shrink-0 items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700">
                                Todas as unidades
                            </span>

                        @else

                            <span class="inline-flex shrink-0 items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">
                                Específica
                            </span>

                        @endif

                    </div>

                    {{-- DETALHES DE ATIVIDADES --}}
                    <div class="flex items-center gap-2 pt-1">

                        <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                            {{ $rule->activities->count() }}
                            {{ $rule->activities->count() === 1 ? 'atividade' : 'atividades' }}
                        </span>

                    </div>

                    {{-- AÇÕES MOBILE --}}
                    @can('update', $preventiveProfile)

                        <div class="pt-2 border-t border-slate-100">

                            <x-buttons.secondary
                                :href="route(
                                    'configuracoes.perfis-preventivas.regras.edit',
                                    [
                                        'preventiveProfile' => $preventiveProfile,
                                        'rule' => $rule,
                                    ]
                                )"
                                class="w-full justify-center"
                            >
                                Editar Regra
                            </x-buttons.secondary>

                        </div>

                    @endcan

                </div>

            @endforeach

        </div>


        {{-- DESKTOP --}}
        <div class="hidden overflow-x-auto md:block">

            <table class="w-full text-left border-collapse">

                <thead class="border-b border-slate-200 bg-slate-50">

                    <tr>

                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                            Filial
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                            Tipo de Regra
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                            Atividades
                        </th>

                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">
                            Ações
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-200 bg-white">

                    @foreach ($rules as $rule)

                        <tr class="hover:bg-slate-50">

                            {{-- FILIAL --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">
                                {{ $rule->preventiveProfileBranch->branch->name ?? 'Filial não identificada' }}
                            </td>

                            {{-- TIPO DE REGRA --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                @if ($rule->rule_type === 'all' || (is_object($rule->rule_type) && $rule->rule_type->value === 'all'))

                                    <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700">
                                        Todas as unidades
                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">
                                        Específica
                                    </span>

                                @endif

                            </td>

                            {{-- ATIVIDADES --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                {{ $rule->activities->count() }}
                                {{ $rule->activities->count() === 1 ? 'atividade' : 'atividades' }}
                            </td>

                            {{-- AÇÕES DESKTOP --}}
                            <td class="whitespace-nowrap px-6 py-4 text-right">

                                <div class="flex items-center justify-end gap-2">

                                    @can('update', $preventiveProfile)

                                        <x-buttons.secondary
                                            :href="route(
                                                'configuracoes.perfis-preventivas.regras.edit',
                                                [
                                                    'preventiveProfile' => $preventiveProfile,
                                                    'rule' => $rule,
                                                ]
                                            )"
                                        >
                                            Editar
                                        </x-buttons.secondary>

                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

</x-cards.card>
