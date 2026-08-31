@php
    use App\Enums\PreventiveProfileRuleType;

    $queryParams = request()->query();
@endphp

<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- CABEÇALHO --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

        <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
            Configuração por filial
        </h2>

        <p class="mt-0.5 text-xs leading-relaxed text-slate-500 sm:text-sm">
            Visualize as regras base e as regras específicas configuradas para cada filial.
        </p>

    </div>

    @if ($branchConfigurations->isEmpty())

        {{-- ESTADO VAZIO --}}
        <div class="px-4 py-12 text-center sm:px-6">

            <div class="mx-auto max-w-md">

                <h3 class="text-base font-semibold text-slate-900">
                    Nenhuma filial encontrada
                </h3>

                <p class="mt-1 text-xs text-slate-500 sm:text-sm">
                    Nenhum registro corresponde aos filtros selecionados ou não há filiais vinculadas a este perfil.
                </p>

                @can('update', $preventiveProfile)

                    <div class="mt-5">

                        <x-buttons.primary
                            :href="route(
                                'configuracoes.perfis-preventivas.regras.create',
                                array_merge(
                                    ['preventiveProfile' => $preventiveProfile],
                                    $queryParams,
                                ),
                            )"
                            class="inline-flex justify-center"
                        >
                            Nova Regra
                        </x-buttons.primary>

                    </div>

                @endcan

            </div>

        </div>

    @else

        {{-- ========================================================= --}}
        {{-- MOBILE                                                     --}}
        {{-- ========================================================= --}}

        <div class="divide-y divide-slate-200 md:hidden">

            @foreach ($branchConfigurations as $configuration)

                @php
                    $profileBranch = $configuration['profileBranch'] ?? null;
                    $branch = $configuration['branch'] ?? null;
                    $allRule = $configuration['allRule'] ?? null;
                    $specificRules = $configuration['specificRules'] ?? collect();
                    $specificCount = $configuration['specificCount'] ?? 0;
                    $activityCount = $configuration['activityCount'] ?? 0;
                    $configured = $configuration['configured'] ?? false;
                @endphp

                <div class="space-y-4 p-4">

                    {{-- FILIAL --}}
                    <div>

                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Filial
                        </span>

                        <h3 class="mt-0.5 text-sm font-semibold text-slate-900">
                            {{ $branch->name ?? 'Filial não identificada' }}
                        </h3>

                    </div>

                    {{-- CONFIGURAÇÃO --}}
                    <div class="grid grid-cols-2 gap-3">

                        {{-- REGRA BASE --}}
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">

                            <span class="text-xs font-medium text-slate-500">
                                Regra base
                            </span>

                            @if ($allRule)

                                <div class="mt-1">

                                    <x-badges.info>
                                        {{ PreventiveProfileRuleType::ALL->label() }}
                                    </x-badges.info>

                                </div>

                            @else

                                <div class="mt-1">

                                    <span
                                        class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-500"
                                    >
                                        Não configurada
                                    </span>

                                </div>

                            @endif

                        </div>

                        {{-- REGRAS ESPECÍFICAS --}}
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">

                            <span class="text-xs font-medium text-slate-500">
                                Regras específicas
                            </span>

                            <div class="mt-1">

                                @if ($specificCount > 0)

                                    <x-badges.warning>
                                        {{ $specificCount }}
                                        {{ $specificCount === 1 ? 'regra' : 'regras' }}
                                    </x-badges.warning>

                                @else

                                    <span class="text-xs text-slate-400">
                                        Nenhuma
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                    {{-- ATIVIDADES --}}
                    <div class="flex flex-wrap items-center gap-2">

                        <span class="text-xs font-medium text-slate-500">
                            Atividades da regra padrão:
                        </span>

                        @if ($allRule)

                            <span
                                class="inline-flex items-center rounded-md border border-slate-200 bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700"
                            >
                                {{ $activityCount }}
                                {{ $activityCount === 1 ? 'atividade' : 'atividades' }}
                            </span>

                        @else

                            <span class="text-xs text-slate-400">
                                —
                            </span>

                        @endif

                    </div>

                    {{-- SITUAÇÃO --}}
                    <div>

                        @if ($configured)

                            <x-badges.success>
                                Configurada
                            </x-badges.success>

                        @else

                            <x-badges.warning>
                                Pendente
                            </x-badges.warning>

                        @endif

                    </div>

                    {{-- AÇÕES MOBILE --}}
                    <div class="border-t border-slate-100 pt-3">

                        <div class="flex flex-col gap-2">

                            @if ($allRule)

                                <x-buttons.secondary
                                    :href="route(
                                        'configuracoes.perfis-preventivas.regras.show',
                                        array_merge(
                                            [
                                                'preventiveProfile' => $preventiveProfile,
                                                'rule' => $allRule,
                                            ],
                                            $queryParams,
                                        ),
                                    )"
                                    class="w-full justify-center"
                                >
                                    Visualizar regra
                                </x-buttons.secondary>

                                @can('update', $preventiveProfile)

                                    <x-buttons.warning
                                        :href="route(
                                            'configuracoes.perfis-preventivas.regras.edit',
                                            array_merge(
                                                [
                                                    'preventiveProfile' => $preventiveProfile,
                                                    'rule' => $allRule,
                                                ],
                                                $queryParams,
                                            ),
                                        )"
                                        class="w-full justify-center"
                                    >
                                        Editar
                                    </x-buttons.warning>

                                    <form
                                        action="{{ route(
                                            'configuracoes.perfis-preventivas.regras.branch.destroy',
                                            [
                                                'preventiveProfile' => $preventiveProfile,
                                                'profileBranch' => $profileBranch,
                                            ],
                                        ) }}"
                                        method="POST"
                                        onsubmit="return confirm(
                                            'Tem certeza que deseja excluir a configuração desta filial?\n\n' +
                                            'A regra padrão e todas as regras específicas desta filial serão removidas. ' +
                                            'A filial voltará para o estado Não configurada.'
                                        );"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <x-buttons.danger
                                            type="submit"
                                            class="w-full justify-center"
                                        >
                                            Excluir configuração
                                        </x-buttons.danger>

                                    </form>

                                @endcan

                            @else

                                @can('update', $preventiveProfile)

                                    <x-buttons.primary
                                        :href="route(
                                            'configuracoes.perfis-preventivas.regras.create',
                                            array_merge(
                                                ['preventiveProfile' => $preventiveProfile],
                                                $queryParams,
                                            ),
                                        )"
                                        class="w-full justify-center"
                                    >
                                        Configurar regra
                                    </x-buttons.primary>

                                @endcan

                            @endif

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

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
                            Filial
                        </th>

                        <th
                            scope="col"
                            class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500"
                        >
                            Regra base
                        </th>

                        <th
                            scope="col"
                            class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500"
                        >
                            Regras específicas
                        </th>

                        <th
                            scope="col"
                            class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500"
                        >
                            Atividades
                        </th>

                        <th
                            scope="col"
                            class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500"
                        >
                            Situação
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

                    @foreach ($branchConfigurations as $configuration)

                        @php
                            $profileBranch = $configuration['profileBranch'] ?? null;
                            $branch = $configuration['branch'] ?? null;
                            $allRule = $configuration['allRule'] ?? null;
                            $specificRules = $configuration['specificRules'] ?? collect();
                            $specificCount = $configuration['specificCount'] ?? 0;
                            $activityCount = $configuration['activityCount'] ?? 0;
                            $configured = $configuration['configured'] ?? false;
                        @endphp

                        <tr class="transition hover:bg-slate-50/80">

                            {{-- Filial --}}
                            <td class="px-6 py-4">

                                <div class="font-semibold text-slate-900">
                                    {{ $branch->name ?? 'Filial não identificada' }}
                                </div>

                            </td>

                            {{-- Regra Base --}}
                            <td class="px-6 py-4">

                                @if ($allRule)

                                    <x-badges.info>
                                        {{ PreventiveProfileRuleType::ALL->label() }}
                                    </x-badges.info>

                                @else

                                    <span
                                        class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-500"
                                    >
                                        Não configurada
                                    </span>

                                @endif

                            </td>

                            {{-- Regras Específicas --}}
                            <td class="px-6 py-4">

                                @if ($specificCount > 0)

                                    <x-badges.warning>
                                        {{ $specificCount }}
                                        {{ $specificCount === 1 ? 'regra' : 'regras' }}
                                    </x-badges.warning>

                                @else

                                    <span class="text-slate-400">
                                        Nenhuma
                                    </span>

                                @endif

                            </td>

                            {{-- Atividades --}}
                            <td class="px-6 py-4 text-slate-700">

                                @if ($allRule)

                                    {{ $activityCount }}
                                    {{ $activityCount === 1 ? 'atividade' : 'atividades' }}

                                @else

                                    —

                                @endif

                            </td>

                            {{-- Situação --}}
                            <td class="px-6 py-4 text-center">

                                @if ($configured)

                                    <x-badges.success>
                                        Configurada
                                    </x-badges.success>

                                @else

                                    <x-badges.warning>
                                        Pendente
                                    </x-badges.warning>

                                @endif

                            </td>

                            {{-- Ações --}}
                            <td class="px-6 py-4">

                                <div class="flex justify-center gap-2">

                                    @if ($allRule)

                                        <x-buttons.secondary
                                            :href="route(
                                                'configuracoes.perfis-preventivas.regras.show',
                                                array_merge(
                                                    [
                                                        'preventiveProfile' => $preventiveProfile,
                                                        'rule' => $allRule,
                                                    ],
                                                    $queryParams,
                                                ),
                                            )"
                                        >
                                            Visualizar
                                        </x-buttons.secondary>

                                        @can('update', $preventiveProfile)

                                            <x-buttons.warning
                                                :href="route(
                                                    'configuracoes.perfis-preventivas.regras.edit',
                                                    array_merge(
                                                        [
                                                            'preventiveProfile' => $preventiveProfile,
                                                            'rule' => $allRule,
                                                        ],
                                                        $queryParams,
                                                    ),
                                                )"
                                            >
                                                Editar
                                            </x-buttons.warning>

                                            <form
                                                action="{{ route(
                                                    'configuracoes.perfis-preventivas.regras.branch.destroy',
                                                    [
                                                        'preventiveProfile' => $preventiveProfile,
                                                        'profileBranch' => $profileBranch,
                                                    ],
                                                ) }}"
                                                method="POST"
                                                onsubmit="return confirm(
                                                    'Tem certeza que deseja excluir a configuração desta filial?\n\n' +
                                                    'A regra padrão e todas as regras específicas desta filial serão removidas. ' +
                                                    'A filial voltará para o estado Não configurada.'
                                                );"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <x-buttons.danger type="submit">
                                                    Excluir
                                                </x-buttons.danger>

                                            </form>

                                        @endcan

                                    @else

                                        @can('update', $preventiveProfile)

                                            <x-buttons.primary
                                                :href="route(
                                                    'configuracoes.perfis-preventivas.regras.create',
                                                    array_merge(
                                                        ['preventiveProfile' => $preventiveProfile],
                                                        $queryParams,
                                                    ),
                                                )"
                                            >
                                                Configurar
                                            </x-buttons.primary>

                                        @endcan

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

    {{-- PAGINAÇÃO --}}
    @if (method_exists($branchConfigurations, 'hasPages') && $branchConfigurations->hasPages())

        <div class="border-t border-slate-200 px-4 py-3 sm:px-6">

            {{ $branchConfigurations->withQueryString()->links() }}

        </div>

    @endif

</x-cards.card>
