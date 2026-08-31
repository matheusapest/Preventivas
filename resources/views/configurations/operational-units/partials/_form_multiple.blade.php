{{--
|--------------------------------------------------------------------------
| Cadastro de Unidades Operacionais em Lote
|--------------------------------------------------------------------------
|
| Este partial é utilizado exclusivamente pelo cadastro múltiplo.
| O formulário é responsável apenas pelos campos do lote.
|
|--------------------------------------------------------------------------

--}}

<div
    id="operational-unit-multiple-container"
    class="space-y-6"
>

    {{-- ================================================================ --}}
    {{-- DADOS BÁSICOS --}}
    {{-- ================================================================ --}}

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">

        <div class="mb-6">

            <h3 class="text-base font-semibold text-gray-900">
                Dados das unidades
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Informe os dados que serão utilizados para todas as unidades
                deste lote.
            </p>

        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            {{-- FILIAL --}}

            <div>

                <x-forms.select
                    name="branch_id"
                    id="multiple_branch_id"
                    label="Filial"
                    :options="$branches"
                    required
                />

            </div>

            {{-- TIPO DE UNIDADE --}}

            <div>

                <x-forms.select
                    name="unit_type_id"
                    id="multiple_unit_type_id"
                    label="Tipo de unidade"
                    required
                    disabled
                />

            </div>

            {{-- PERFIL OPERACIONAL --}}

            <div>

                <x-forms.select
                    name="operational_profile_id"
                    id="multiple_operational_profile_id"
                    label="Perfil operacional"
                    required
                    disabled
                />

            </div>

            {{-- PREFIXO --}}

            <div>

                <x-forms.input
                    name="prefix"
                    id="multiple_prefix"
                    label="Prefixo do lote"
                    placeholder="Ex.: PDV, SELFIE, CANCELA"
                    value="{{ old('prefix') }}"
                    maxlength="30"
                    required
                />

                <p class="mt-1 text-xs text-gray-500">
                    O prefixo será utilizado antes do número da unidade.

                    Ex.: PDV 01, PDV 02, PDV 03.
                </p>

            </div>

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- IDENTIFICAÇÃO --}}
    {{-- ================================================================ --}}

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">

        <div class="mb-6">

            <h3 class="text-base font-semibold text-gray-900">
                Identificação das unidades
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Defina como os identificadores do lote serão gerados.
            </p>

        </div>


        {{-- MODO DE IDENTIFICAÇÃO --}}

        <div class="mb-6">

            <x-forms.select
                name="identifier_mode"
                id="multiple_identifier_mode"
                label="Modo de identificação"
                :options="[
                    (object) [
                        'id' => 'range',
                        'name' => 'Intervalo',
                    ],
                    (object) [
                        'id' => 'list',
                        'name' => 'Lista específica',
                    ],
                ]"
                :value="old('identifier_mode')"
                placeholder="Selecione uma opção"
                required
            />

        </div>


        {{-- INTERVALO --}}

        <div
            id="multiple-range-fields"
            class="hidden"
        >

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- INÍCIO --}}

                <div>

                    <x-forms.input
                        type="number"
                        name="identifier_start"
                        id="multiple_identifier_start"
                        label="Identificador inicial"
                        placeholder="Ex.: 1"
                        min="0"
                        max="9999"
                        value="{{ old('identifier_start') }}"
                    />

                </div>


                {{-- FIM --}}

                <div>

                    <x-forms.input
                        type="number"
                        name="identifier_end"
                        id="multiple_identifier_end"
                        label="Identificador final"
                        placeholder="Ex.: 40"
                        min="0"
                        max="9999"
                        value="{{ old('identifier_end') }}"
                    />

                </div>

            </div>

            <p class="mt-3 text-xs text-gray-500">
                Exemplo: de 1 até 40 criará PDV 01, PDV 02, ..., PDV 40.
            </p>

        </div>


        {{-- LISTA --}}

        <div
            id="multiple-list-fields"
            class="hidden"
        >

            <x-forms.textarea
                name="identifiers"
                id="multiple_identifiers"
                label="Identificadores"
                rows="4"
                placeholder="Ex.: 01,02,03,07,10,12"
            />

            <p class="mt-3 text-xs text-gray-500">
                Informe somente os números desejados, separados por vírgula.

                Ex.: 03,04,05,07,10,12.
            </p>

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- SITUAÇÃO --}}
    {{-- ================================================================ --}}

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">

        <div class="mb-6">

            <h3 class="text-base font-semibold text-gray-900">
                Situação
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Defina a situação inicial das unidades criadas.
            </p>

        </div>

        <x-forms.checkbox
            name="active"
            id="multiple_active"
            value="1"
            :checked="old('active', true)"
            label="Cadastrar unidades como ativas"
        />

    </div>

</div>

@vite('resources/js/operational-unit/operationalMultipleUnit.js')
