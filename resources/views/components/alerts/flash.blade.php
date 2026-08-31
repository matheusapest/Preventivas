@if (session('success'))

    <x-alerts.success
        title="Sucesso"
        class="mb-6"
    >
        {{ session('success') }}
    </x-alerts.success>

@endif

@if (session('error'))

    <x-alerts.error
        title="Erro"
        class="mb-6"
    >
        {{ session('error') }}
    </x-alerts.error>

@endif

@if (session('warning'))

    <x-alerts.warning
        title="Atenção"
        class="mb-6"
    >
        {{ session('warning') }}
    </x-alerts.warning>

@endif

@if (session('info'))

    <x-alerts.info
        title="Informação"
        class="mb-6"
    >
        {{ session('info') }}
    </x-alerts.info>

@endif
