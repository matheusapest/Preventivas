{{-- ========================================================= --}}
{{-- AÇÕES DA PÁGINA                                           --}}
{{-- ========================================================= --}}

<div class="flex items-center justify-end gap-3">

    <x-buttons.secondary
        :href="route(
            'configuracoes.perfis-preventivas.regras.index',
            $preventiveProfile
        )"
    >
        Cancelar
    </x-buttons.secondary>


    <x-buttons.primary
        type="submit"
        form="all-rule-form"
    >
        Salvar alterações
    </x-buttons.primary>

</div>
