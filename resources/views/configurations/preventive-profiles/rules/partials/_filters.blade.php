<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Cabeçalho --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">
        <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
            Filtros
        </h2>

        <p class="mt-0.5 text-xs leading-relaxed text-slate-500 sm:text-sm">
            Localize rapidamente uma filial ou visualize apenas as configurações pendentes.
        </p>
    </div>

    {{-- Corpo --}}
    <form
        action="{{ route('configuracoes.perfis-preventivas.regras.index', $preventiveProfile) }}"
        method="GET"
    >
        <div class="space-y-4 p-4 sm:p-6">

            {{-- Linha principal --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                {{-- Filial --}}
                <div>
                    <label
                        for="branch_id"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Filial
                    </label>

                    <select
                        id="branch_id"
                        name="branch_id"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-xs text-slate-900 shadow-2xs focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                        <option value="">
                            Todas as filiais
                        </option>

                        @foreach ($preventiveProfile->branches as $profileBranch)
                            <option
                                value="{{ $profileBranch->branch_id }}"
                                @selected((string) ($filters['branch_id'] ?? request('branch_id')) === (string) $profileBranch->branch_id)
                            >
                                {{ $profileBranch->branch->name ?? 'Filial #' . $profileBranch->branch_id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Situação --}}
                <div>
                    <label
                        for="status"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Situação
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-xs text-slate-900 shadow-2xs focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    >
                        <option value="" @selected(!($filters['status'] ?? request('status')))>Todas</option>
                        <option value="configured" @selected(($filters['status'] ?? request('status')) === 'configured')>Configuradas</option>
                        <option value="pending" @selected(($filters['status'] ?? request('status')) === 'pending')>Pendentes</option>
                    </select>
                </div>

            </div>

            {{-- Separador e Ações --}}
            <div class="border-t border-slate-100 pt-4">

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                    <x-buttons.secondary
                        :href="route('configuracoes.perfis-preventivas.regras.index', $preventiveProfile)"
                        class="w-full justify-center sm:w-auto"
                    >
                        Limpar filtros
                    </x-buttons.secondary>

                    <x-buttons.primary
                        type="submit"
                        class="w-full justify-center sm:w-auto"
                    >
                        Filtrar
                    </x-buttons.primary>

                </div>

            </div>

        </div>
    </form>

</x-cards.card>
