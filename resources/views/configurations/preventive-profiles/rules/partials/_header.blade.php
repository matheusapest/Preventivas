<x-layout.page-header title="Regras do Perfil de Preventiva" :description="'Configure as regras utilizadas pelo perfil ' . $preventiveProfile->name . '.'">

    <x-slot:breadcrumb>
        Dashboard / Configurações / Perfis de Preventiva / {{ $preventiveProfile->name }} / Regras
    </x-slot:breadcrumb>

    <x-slot:actions>

        <div class="grid grid-cols-2 gap-2 sm:flex sm:items-center">

            <x-buttons.secondary :href="route('configuracoes.perfis-preventivas.index')" class="w-full justify-center sm:w-auto">
                Voltar
            </x-buttons.secondary>

            @can('update', $preventiveProfile)

                @if ($pendingBranches > 0)
                    <x-buttons.primary :href="route('configuracoes.perfis-preventivas.regras.create', $preventiveProfile)">
                        Nova Regra
                    </x-buttons.primary>
                @endif
            @endcan

        </div>

    </x-slot:actions>

</x-layout.page-header>
