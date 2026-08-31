<div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">

    {{-- Cabeçalho --}}
    <div class="border-b border-slate-200 pb-3 sm:pb-4">
        <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
            Informações do Equipamento
        </h2>
        <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
            Dados obtidos a partir do patrimônio ou número de série informado.
        </p>
    </div>

    <div class="mt-4 space-y-5 sm:mt-6 sm:space-y-6">

        {{-- Dados do Equipamento --}}
        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 sm:gap-5">

            {{-- Patrimônio --}}
            <div>
                <dt class="text-xs font-medium text-slate-500 sm:text-sm">
                    Patrimônio
                </dt>
                <dd
                    id="equipment-asset-number"
                    class="mt-0.5 text-sm font-bold text-slate-800 sm:text-base"
                >
                    —
                </dd>
            </div>

            {{-- Nome --}}
            <div>
                <dt class="text-xs font-medium text-slate-500 sm:text-sm">
                    Nome
                </dt>
                <dd
                    id="equipment-name"
                    class="mt-0.5 text-sm font-semibold text-slate-800 sm:text-base"
                >
                    —
                </dd>
            </div>

            {{-- Categoria --}}
            <div>
                <dt class="text-xs font-medium text-slate-500 sm:text-sm">
                    Categoria
                </dt>
                <dd
                    id="equipment-category"
                    class="mt-0.5 text-sm text-slate-700 sm:text-base"
                >
                    —
                </dd>
            </div>

            {{-- Fabricante --}}
            <div>
                <dt class="text-xs font-medium text-slate-500 sm:text-sm">
                    Fabricante
                </dt>
                <dd
                    id="equipment-manufacturer"
                    class="mt-0.5 text-sm text-slate-700 sm:text-base"
                >
                    —
                </dd>
            </div>

            {{-- Modelo --}}
            <div>
                <dt class="text-xs font-medium text-slate-500 sm:text-sm">
                    Modelo
                </dt>
                <dd
                    id="equipment-model"
                    class="mt-0.5 text-sm text-slate-700 sm:text-base"
                >
                    —
                </dd>
            </div>

            {{-- Número de Série --}}
            <div>
                <dt class="text-xs font-medium text-slate-500 sm:text-sm">
                    Número de Série
                </dt>
                <dd
                    id="equipment-serial-number"
                    class="mt-0.5 font-mono text-sm text-slate-700 sm:text-base"
                >
                    —
                </dd>
            </div>

            {{-- Filial Atual --}}
            <div>
                <dt class="text-xs font-medium text-slate-500 sm:text-sm">
                    Filial Atual
                </dt>
                <dd
                    id="equipment-branch"
                    class="mt-0.5 text-sm font-medium text-slate-800 sm:text-base"
                >
                    —
                </dd>
            </div>

            {{-- Status Operacional --}}
            <div>
                <dt class="text-xs font-medium text-slate-500 sm:text-sm">
                    Status Operacional
                </dt>
                <dd
                    id="equipment-operational-status"
                    class="mt-0.5 text-sm font-medium text-slate-800 sm:text-base"
                >
                    —
                </dd>
            </div>

            {{-- Status do Cadastro --}}
            <div>
                <dt class="text-xs font-medium text-slate-500 sm:text-sm">
                    Status do Cadastro
                </dt>
                <dd
                    id="equipment-status"
                    class="mt-0.5 flex items-center"
                >
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                        —
                    </span>
                </dd>
            </div>

        </dl>

        {{-- Situação do Equipamento --}}
        <div
            id="maintenance-equipment-status-box"
            class="rounded-lg border border-blue-200 bg-blue-50/80 p-3.5 transition-all sm:p-4"
        >
            <div class="flex items-start gap-3">
                <span class="shrink-0 text-lg leading-none" aria-hidden="true">
                    ℹ️
                </span>

                <div class="space-y-0.5">
                    <h4 class="text-xs font-bold text-blue-900 sm:text-sm">
                        Situação
                    </h4>
                    <p
                        id="maintenance-equipment-status-message"
                        class="text-xs leading-relaxed text-blue-800 sm:text-sm"
                    >
                        Informe um patrimônio ou número de série para consultar o equipamento.
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>
