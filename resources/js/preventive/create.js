document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('preventive-form');

    if (!form) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | ELEMENTOS
    |--------------------------------------------------------------------------
    */

    const branchSelect =
        document.getElementById('branch_id');

    const typeSelect =
        document.getElementById('preventive_type_id');

    const profileSelect =
        document.getElementById('preventive_profile_id');

    const configuration =
        document.getElementById('profile-configuration');

    const configurationDescription =
        document.getElementById('configuration-description');

    const configurationType =
        document.getElementById('configuration-type');

    const unitsList =
        document.getElementById('units-list');

    const unitsEmpty =
        document.getElementById('units-empty');

    const unitsCount =
        document.getElementById('units-count');

    const rulesList =
        document.getElementById('rules-list');

    const rulesEmpty =
        document.getElementById('rules-empty');

    const submitButton =
        document.getElementById('submit-button');

    const startDateInput =
        document.getElementById('start_date');

    const startDateError =
        document.getElementById('start-date-error');

    const startDateHelp =
        document.getElementById('start-date-help');

    /*
    |--------------------------------------------------------------------------
    | ROTAS
    |--------------------------------------------------------------------------
    */

    const routes = {
        types: (branchId) =>
            `/preventivas/dados/tipos/${branchId}`,

        profiles: (branchId, typeId) =>
            `/preventivas/dados/perfis/${branchId}/${typeId}`,

        configuration: (branchId, profileId) =>
            `/preventivas/dados/configuracao/${branchId}/${profileId}`,
    };

    /*
    |--------------------------------------------------------------------------
    | VALIDAÇÃO DA DATA DE INÍCIO
    |--------------------------------------------------------------------------
    |
    | Regras:
    |
    | 1. Data obrigatória.
    | 2. Não pode ser anterior a hoje.
    | 3. Domingo não é permitido.
    | 4. Segunda a sábado são permitidos.
    |
    */

    function validateStartDate() {
        if (!startDateInput) {
            return false;
        }

        const value =
            startDateInput.value;

        clearStartDateError();

        /*
        |--------------------------------------------------------------------------
        | DATA NÃO INFORMADA
        |--------------------------------------------------------------------------
        */

        if (!value) {
            showStartDateError(
                'Informe a data de início da preventiva.'
            );

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | CONVERSÃO SEGURA DA DATA
        |--------------------------------------------------------------------------
        |
        | Evitamos new Date('YYYY-MM-DD'), pois isso pode
        | gerar problemas de timezone.
        |
        */

        const [year, month, day] =
            value.split('-').map(Number);

        const selectedDate =
            new Date(
                year,
                month - 1,
                day
            );

        const today =
            new Date();

        today.setHours(
            0,
            0,
            0,
            0
        );

        selectedDate.setHours(
            0,
            0,
            0,
            0
        );

        /*
        |--------------------------------------------------------------------------
        | DATA INVÁLIDA
        |--------------------------------------------------------------------------
        */

        if (
            selectedDate.getFullYear() !== year ||
            selectedDate.getMonth() !== month - 1 ||
            selectedDate.getDate() !== day
        ) {
            showStartDateError(
                'Informe uma data de início válida.'
            );

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | NÃO PERMITIR DATA NO PASSADO
        |--------------------------------------------------------------------------
        */

        if (selectedDate < today) {
            showStartDateError(
                'A data de início não pode ser anterior à data atual.'
            );

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | NÃO PERMITIR DOMINGO
        |--------------------------------------------------------------------------
        |
        | getDay():
        |
        | 0 = domingo
        | 1 = segunda
        | 2 = terça
        | 3 = quarta
        | 4 = quinta
        | 5 = sexta
        | 6 = sábado
        |
        */

        if (selectedDate.getDay() === 0) {
            showStartDateError(
                'A data de início não pode ser em um domingo.'
            );

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | DATA VÁLIDA
        |--------------------------------------------------------------------------
        */

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | EXIBIR ERRO DA DATA
    |--------------------------------------------------------------------------
    */

    function showStartDateError(message) {
        if (startDateError) {
            startDateError.textContent =
                message;

            startDateError.classList.remove(
                'hidden'
            );
        }

        if (startDateInput) {
            startDateInput.classList.add(
                'border-red-500',
                'focus:border-red-500',
                'focus:ring-red-500'
            );

            startDateInput.setAttribute(
                'aria-invalid',
                'true'
            );
        }

        if (startDateHelp) {
            startDateHelp.classList.add(
                'hidden'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | LIMPAR ERRO DA DATA
    |--------------------------------------------------------------------------
    */

    function clearStartDateError() {
        if (startDateError) {
            startDateError.textContent = '';

            startDateError.classList.add(
                'hidden'
            );
        }

        if (startDateInput) {
            startDateInput.classList.remove(
                'border-red-500',
                'focus:border-red-500',
                'focus:ring-red-500'
            );

            startDateInput.removeAttribute(
                'aria-invalid'
            );
        }

        if (startDateHelp) {
            startDateHelp.classList.remove(
                'hidden'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR BOTÃO DE ENVIO
    |--------------------------------------------------------------------------
    */

    function updateSubmitButton() {
        if (!submitButton) {
            return;
        }

        const branchSelected =
            Boolean(
                branchSelect?.value
            );

        const typeSelected =
            Boolean(
                typeSelect?.value
            );

        const profileSelected =
            Boolean(
                profileSelect?.value
            );

        const configurationVisible =
            configuration &&
            !configuration.classList.contains(
                'hidden'
            );

        const validStartDate =
            validateStartDate();

        submitButton.disabled = !(
            branchSelected &&
            typeSelected &&
            profileSelected &&
            configurationVisible &&
            validStartDate
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EVENTO - DATA DE INÍCIO
    |--------------------------------------------------------------------------
    */

    if (startDateInput) {
        startDateInput.addEventListener(
            'change',
            () => {
                validateStartDate();
                updateSubmitButton();
            }
        );

        startDateInput.addEventListener(
            'input',
            () => {
                validateStartDate();
                updateSubmitButton();
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SUBMIT DO FORMULÁRIO
    |--------------------------------------------------------------------------
    |
    | Mesmo que o botão esteja habilitado por algum motivo,
    | fazemos uma última validação antes de enviar.
    |
    */

    form.addEventListener(
        'submit',
        (event) => {
            const validStartDate =
                validateStartDate();

            if (!validStartDate) {
                event.preventDefault();

                updateSubmitButton();

                if (startDateInput) {
                    startDateInput.focus();
                }

                return;
            }
        }
    );

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    function resetSelect(
        select,
        placeholder
    ) {
        if (!select) {
            return;
        }

        select.innerHTML = '';

        const option =
            document.createElement('option');

        option.value = '';
        option.textContent =
            placeholder;

        select.appendChild(option);

        select.disabled = true;
    }

    function setLoading(
        select,
        message
    ) {
        if (!select) {
            return;
        }

        select.innerHTML = '';

        const option =
            document.createElement('option');

        option.value = '';
        option.textContent =
            message;

        select.appendChild(option);

        select.disabled = true;
    }

    async function fetchJson(url) {
        const response =
            await fetch(url, {
                headers: {
                    Accept:
                        'application/json',

                    'X-Requested-With':
                        'XMLHttpRequest',
                },
            });

        if (!response.ok) {
            throw new Error(
                `Erro HTTP ${response.status}`
            );
        }

        return response.json();
    }

    /*
    |--------------------------------------------------------------------------
    | RESET DA CONFIGURAÇÃO
    |--------------------------------------------------------------------------
    */

    function resetConfiguration() {
        if (configuration) {
            configuration.classList.add(
                'hidden'
            );
        }

        if (configurationDescription) {
            configurationDescription.textContent =
                'Configuração carregada do perfil selecionado.';
        }

        if (configurationType) {
            configurationType.textContent = '';

            configurationType.classList.add(
                'hidden'
            );
        }

        if (unitsList) {
            unitsList.innerHTML = '';

            unitsList.classList.add(
                'hidden'
            );
        }

        if (unitsEmpty) {
            unitsEmpty.classList.remove(
                'hidden'
            );
        }

        if (unitsCount) {
            unitsCount.textContent =
                '0 unidades';
        }

        if (rulesList) {
            rulesList.innerHTML = '';

            rulesList.classList.add(
                'hidden'
            );
        }

        if (rulesEmpty) {
            rulesEmpty.classList.remove(
                'hidden'
            );
        }

        if (submitButton) {
            submitButton.disabled = true;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | TIPOS DE PREVENTIVA
    |--------------------------------------------------------------------------
    */

    function populateTypes(types) {
        typeSelect.innerHTML = '';

        const placeholder =
            document.createElement('option');

        placeholder.value = '';

        placeholder.textContent =
            types.length
                ? 'Selecione o tipo'
                : 'Nenhum tipo disponível para esta filial';

        typeSelect.appendChild(
            placeholder
        );

        types.forEach((type) => {
            const option =
                document.createElement('option');

            option.value =
                type.id;

            option.textContent =
                type.name;

            typeSelect.appendChild(
                option
            );
        });

        typeSelect.disabled =
            types.length === 0;
    }

    /*
    |--------------------------------------------------------------------------
    | PERFIS
    |--------------------------------------------------------------------------
    */

    function populateProfiles(profiles) {
        profileSelect.innerHTML = '';

        const placeholder =
            document.createElement('option');

        placeholder.value = '';

        placeholder.textContent =
            profiles.length
                ? 'Selecione o perfil'
                : 'Nenhum perfil disponível para esta combinação';

        profileSelect.appendChild(
            placeholder
        );

        profiles.forEach((profile) => {
            const option =
                document.createElement('option');

            option.value =
                profile.id;

            option.textContent =
                profile.name;

            if (profile.description) {
                option.title =
                    profile.description;
            }

            profileSelect.appendChild(
                option
            );
        });

        profileSelect.disabled =
            profiles.length === 0;
    }

    /*
    |--------------------------------------------------------------------------
    | UNIDADES PARTICIPANTES
    |--------------------------------------------------------------------------
    */

    function renderUnits(units) {
        unitsList.innerHTML = '';

        if (
            !units ||
            units.length === 0
        ) {
            unitsList.classList.add(
                'hidden'
            );

            unitsEmpty.classList.remove(
                'hidden'
            );

            unitsCount.textContent =
                '0 unidades';

            return;
        }

        unitsEmpty.classList.add(
            'hidden'
        );

        unitsList.classList.remove(
            'hidden'
        );

        unitsCount.textContent =
            `${units.length} ${
                units.length === 1
                    ? 'unidade'
                    : 'unidades'
            }`;

        units.forEach((unit) => {
            const card =
                document.createElement('div');

            card.className =
                'rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5';

            const identifier =
                document.createElement('div');

            identifier.className =
                'text-sm font-medium text-slate-900';

            identifier.textContent =
                unit.identifier;

            const rule =
                document.createElement('div');

            rule.className =
                'mt-0.5 text-xs text-slate-500';

            rule.textContent =
                unit.rule_type === 'specific'
                    ? 'Regra específica'
                    : 'Regra geral';

            card.appendChild(
                identifier
            );

            card.appendChild(
                rule
            );

            unitsList.appendChild(
                card
            );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | REGRAS
    |--------------------------------------------------------------------------
    */

    function renderRules(
        rules,
        resolvedUnits = []
    ) {
        rulesList.innerHTML = '';

        if (
            !rules ||
            rules.length === 0
        ) {
            rulesList.classList.add(
                'hidden'
            );

            rulesEmpty.classList.remove(
                'hidden'
            );

            return;
        }

        rulesEmpty.classList.add(
            'hidden'
        );

        rulesList.classList.remove(
            'hidden'
        );

        /*
        |--------------------------------------------------------------------------
        | MAPA DAS UNIDADES
        |--------------------------------------------------------------------------
        |
        | Exemplo:
        |
        | 23 -> PDV 01
        | 28 -> PDV 06
        |
        */

        const unitsById =
            new Map();

        resolvedUnits.forEach(
            (unit) => {
                unitsById.set(
                    Number(unit.id),
                    unit
                );
            }
        );

        rules.forEach((rule) => {
            const wrapper =
                document.createElement('div');

            wrapper.className =
                'rounded-lg border border-slate-200 bg-white p-4';

            /*
            |--------------------------------------------------------------------------
            | CABEÇALHO
            |--------------------------------------------------------------------------
            */

            const header =
                document.createElement('div');

            header.className =
                'flex items-center justify-between gap-3';

            const title =
                document.createElement('h5');

            title.className =
                'text-sm font-semibold text-slate-900';

            title.textContent =
                rule.rule_type === 'specific'
                    ? 'Regra específica'
                    : 'Regra geral';

            const badge =
                document.createElement('span');

            badge.className =
                'rounded-full bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600';

            badge.textContent =
                rule.rule_type === 'specific'
                    ? 'Specific'
                    : 'All';

            header.appendChild(
                title
            );

            header.appendChild(
                badge
            );

            wrapper.appendChild(
                header
            );

            /*
            |--------------------------------------------------------------------------
            | UNIDADES DA REGRA SPECIFIC
            |--------------------------------------------------------------------------
            */

            if (
                rule.rule_type === 'specific' &&
                Array.isArray(rule.units) &&
                rule.units.length > 0
            ) {
                const units =
                    document.createElement('p');

                units.className =
                    'mt-2 text-xs text-slate-500';

                const identifiers =
                    rule.units.map(
                        (ruleUnit) => {
                            const operationalUnit =
                                unitsById.get(
                                    Number(
                                        ruleUnit.operational_unit_id
                                    )
                                );

                            if (
                                operationalUnit
                            ) {
                                return operationalUnit.identifier;
                            }

                            return `Unidade ${ruleUnit.operational_unit_id}`;
                        }
                    );

                units.textContent =
                    `Unidades: ${identifiers.join(', ')}`;

                wrapper.appendChild(
                    units
                );
            }

            /*
            |--------------------------------------------------------------------------
            | ATIVIDADES
            |--------------------------------------------------------------------------
            */

            const activitiesTitle =
                document.createElement('div');

            activitiesTitle.className =
                'mt-4 text-xs font-medium uppercase tracking-wide text-slate-500';

            activitiesTitle.textContent =
                'Atividades';

            wrapper.appendChild(
                activitiesTitle
            );

            const activities =
                document.createElement('div');

            activities.className =
                'mt-2 space-y-2';

            if (
                !Array.isArray(
                    rule.activities
                ) ||
                rule.activities.length === 0
            ) {
                const empty =
                    document.createElement('p');

                empty.className =
                    'text-xs text-slate-400';

                empty.textContent =
                    'Nenhuma atividade configurada.';

                activities.appendChild(
                    empty
                );
            } else {
                rule.activities.forEach(
                    (ruleActivity) => {
                        const activity =
                            ruleActivity.activity;

                        if (!activity) {
                            return;
                        }

                        const item =
                            document.createElement('div');

                        item.className =
                            'rounded-md bg-slate-50 px-3 py-2';

                        const name =
                            document.createElement('div');

                        name.className =
                            'text-sm font-medium text-slate-800';

                        name.textContent =
                            activity.name;

                        item.appendChild(
                            name
                        );

                        if (
                            activity.description
                        ) {
                            const description =
                                document.createElement('div');

                            description.className =
                                'mt-0.5 text-xs text-slate-500';

                            description.textContent =
                                activity.description;

                            item.appendChild(
                                description
                            );
                        }

                        activities.appendChild(
                            item
                        );
                    }
                );
            }

            wrapper.appendChild(
                activities
            );

            rulesList.appendChild(
                wrapper
            );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CONFIGURAÇÃO COMPLETA
    |--------------------------------------------------------------------------
    */

    function renderConfiguration(data) {
        configuration.classList.remove(
            'hidden'
        );

        configurationDescription.textContent =
            data.profile?.description ||
            'Configuração carregada do perfil selecionado.';

        if (
            data.preventive_type?.name
        ) {
            configurationType.textContent =
                data.preventive_type.name;

            configurationType.classList.remove(
                'hidden'
            );
        } else {
            configurationType.textContent =
                '';

            configurationType.classList.add(
                'hidden'
            );
        }

        renderUnits(
            data.units || []
        );

        renderRules(
            data.rules || [],
            data.units || []
        );

        updateSubmitButton();
    }

    /*
    |--------------------------------------------------------------------------
    | CARREGAR TIPOS
    |--------------------------------------------------------------------------
    */

    async function loadTypes() {
        const branchId =
            branchSelect.value;

        resetConfiguration();

        resetSelect(
            typeSelect,
            'Selecione a filial primeiro'
        );

        resetSelect(
            profileSelect,
            'Selecione o tipo primeiro'
        );

        if (!branchId) {
            updateSubmitButton();
            return;
        }

        setLoading(
            typeSelect,
            'Carregando tipos...'
        );

        try {
            const types =
                await fetchJson(
                    routes.types(
                        branchId
                    )
                );

            populateTypes(
                types
            );
        } catch (error) {
            console.error(
                'Erro ao carregar tipos:',
                error
            );

            resetSelect(
                typeSelect,
                'Erro ao carregar tipos'
            );
        }

        updateSubmitButton();
    }

    /*
    |--------------------------------------------------------------------------
    | CARREGAR PERFIS
    |--------------------------------------------------------------------------
    */

    async function loadProfiles() {
        const branchId =
            branchSelect.value;

        const typeId =
            typeSelect.value;

        resetConfiguration();

        resetSelect(
            profileSelect,
            'Selecione o tipo primeiro'
        );

        if (
            !branchId ||
            !typeId
        ) {
            updateSubmitButton();
            return;
        }

        setLoading(
            profileSelect,
            'Carregando perfis...'
        );

        try {
            const profiles =
                await fetchJson(
                    routes.profiles(
                        branchId,
                        typeId
                    )
                );

            populateProfiles(
                profiles
            );
        } catch (error) {
            console.error(
                'Erro ao carregar perfis:',
                error
            );

            resetSelect(
                profileSelect,
                'Erro ao carregar perfis'
            );
        }

        updateSubmitButton();
    }

    /*
    |--------------------------------------------------------------------------
    | CARREGAR CONFIGURAÇÃO
    |--------------------------------------------------------------------------
    */

    async function loadConfiguration() {
        const branchId =
            branchSelect.value;

        const profileId =
            profileSelect.value;

        resetConfiguration();

        if (
            !branchId ||
            !profileId
        ) {
            updateSubmitButton();
            return;
        }

        try {
            const data =
                await fetchJson(
                    routes.configuration(
                        branchId,
                        profileId
                    )
                );

            console.log(
                'Configuração preventiva:',
                data
            );

            renderConfiguration(
                data
            );
        } catch (error) {
            console.error(
                'Erro ao carregar configuração:',
                error
            );

            configuration.classList.add(
                'hidden'
            );

            updateSubmitButton();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EVENTO - FILIAL
    |--------------------------------------------------------------------------
    */

    branchSelect.addEventListener(
        'change',
        () => {
            loadTypes();
        }
    );

    /*
    |--------------------------------------------------------------------------
    | EVENTO - TIPO
    |--------------------------------------------------------------------------
    */

    typeSelect.addEventListener(
        'change',
        () => {
            loadProfiles();
        }
    );

    /*
    |--------------------------------------------------------------------------
    | EVENTO - PERFIL
    |--------------------------------------------------------------------------
    */

    profileSelect.addEventListener(
        'change',
        () => {
            loadConfiguration();
        }
    );

    /*
    |--------------------------------------------------------------------------
    | INICIALIZAÇÃO
    |--------------------------------------------------------------------------
    */

    if (branchSelect.value) {
        loadTypes();
    } else {
        resetSelect(
            typeSelect,
            'Selecione a filial primeiro'
        );

        resetSelect(
            profileSelect,
            'Selecione o tipo primeiro'
        );

        resetConfiguration();
    }

    /*
    |--------------------------------------------------------------------------
    | ESTADO INICIAL DO BOTÃO
    |--------------------------------------------------------------------------
    */

    updateSubmitButton();
});
