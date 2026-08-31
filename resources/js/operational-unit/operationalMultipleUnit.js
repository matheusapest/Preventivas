document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('operational-unit-multiple-form');

    if (!form) {
        return;
    }

    const branchSelect = document.getElementById('multiple_branch_id');
    const unitTypeSelect = document.getElementById('multiple_unit_type_id');
    const operationalProfileSelect = document.getElementById(
        'multiple_operational_profile_id'
    );

    const identifierModeSelect = document.getElementById(
        'multiple_identifier_mode'
    );

    const rangeFields = document.getElementById(
        'multiple-range-fields'
    );

    const listFields = document.getElementById(
        'multiple-list-fields'
    );

    if (
        !branchSelect ||
        !unitTypeSelect ||
        !operationalProfileSelect
    ) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Dados enviados pelo Controller
    |--------------------------------------------------------------------------
    */

    let unitTypes = [];
    let operationalProfiles = [];

    try {
        unitTypes = JSON.parse(
            form.dataset.unitTypes || '[]'
        );

        operationalProfiles = JSON.parse(
            form.dataset.operationalProfiles || '[]'
        );
    } catch (error) {
        console.error(
            'Erro ao carregar os dados do cadastro em lote:',
            error
        );

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    function normalizeId(value) {
        return String(value ?? '');
    }

    function clearSelect(select, placeholder) {
        select.innerHTML = '';

        const option = document.createElement('option');

        option.value = '';
        option.textContent = placeholder;

        select.appendChild(option);
    }

    function disableSelect(select) {
        select.disabled = true;
    }

    function enableSelect(select) {
        select.disabled = false;
    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se o tipo pertence à filial
    |--------------------------------------------------------------------------
    */

    function unitTypeBelongsToBranch(unitType, branchId) {
        if (!branchId) {
            return false;
        }

        const branchIds = unitType.branch_ids ?? [];

        return branchIds.some(
            id =>
                normalizeId(id) ===
                normalizeId(branchId)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Carrega os tipos de unidade
    |--------------------------------------------------------------------------
    */

    function loadUnitTypes(branchId) {
        clearSelect(
            unitTypeSelect,
            'Selecione o tipo de unidade'
        );

        clearSelect(
            operationalProfileSelect,
            'Selecione o perfil operacional'
        );

        disableSelect(unitTypeSelect);
        disableSelect(operationalProfileSelect);

        if (!branchId) {
            return;
        }

        const availableUnitTypes = unitTypes.filter(
            unitType =>
                unitTypeBelongsToBranch(
                    unitType,
                    branchId
                )
        );

        if (availableUnitTypes.length === 0) {
            return;
        }

        availableUnitTypes.forEach(unitType => {
            const option = document.createElement('option');

            option.value = unitType.id;
            option.textContent = unitType.name;

            unitTypeSelect.appendChild(option);
        });

        enableSelect(unitTypeSelect);
    }

    /*
    |--------------------------------------------------------------------------
    | Carrega os perfis operacionais
    |--------------------------------------------------------------------------
    */

    function loadOperationalProfiles(unitTypeId) {
        clearSelect(
            operationalProfileSelect,
            'Selecione o perfil operacional'
        );

        disableSelect(operationalProfileSelect);

        if (!unitTypeId) {
            return;
        }

        const availableProfiles =
            operationalProfiles.filter(
                profile =>
                    normalizeId(profile.unit_type_id) ===
                    normalizeId(unitTypeId)
            );

        if (availableProfiles.length === 0) {
            return;
        }

        availableProfiles.forEach(profile => {
            const option = document.createElement('option');

            option.value = profile.id;
            option.textContent = profile.name;

            operationalProfileSelect.appendChild(option);
        });

        enableSelect(operationalProfileSelect);
    }

    /*
    |--------------------------------------------------------------------------
    | Evento: alteração da filial
    |--------------------------------------------------------------------------
    */

    branchSelect.addEventListener('change', () => {
        const branchId = branchSelect.value;

        loadUnitTypes(branchId);
    });

    /*
    |--------------------------------------------------------------------------
    | Evento: alteração do tipo de unidade
    |--------------------------------------------------------------------------
    */

    unitTypeSelect.addEventListener('change', () => {
        const unitTypeId = unitTypeSelect.value;

        loadOperationalProfiles(unitTypeId);
    });

    /*
    |--------------------------------------------------------------------------
    | Modo de identificação
    |--------------------------------------------------------------------------
    */

    function updateIdentifierFields() {
        if (!identifierModeSelect) {
            return;
        }

        const mode = identifierModeSelect.value;

        if (rangeFields) {
            rangeFields.classList.toggle(
                'hidden',
                mode !== 'range'
            );
        }

        if (listFields) {
            listFields.classList.toggle(
                'hidden',
                mode !== 'list'
            );
        }
    }

    if (identifierModeSelect) {
        identifierModeSelect.addEventListener(
            'change',
            updateIdentifierFields
        );

        /*
        |--------------------------------------------------------------
        | Inicialização
        |--------------------------------------------------------------
        */

        updateIdentifierFields();
    }

    /*
    |--------------------------------------------------------------------------
    | Inicialização
    |--------------------------------------------------------------------------
    */

    const initialBranchId = branchSelect.value;

    if (initialBranchId) {
        loadUnitTypes(initialBranchId);
    } else {
        disableSelect(unitTypeSelect);
        disableSelect(operationalProfileSelect);
    }
});
