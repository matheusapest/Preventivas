document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('operational-unit-form');

    if (!form) {
        return;
    }

    const branchSelect = document.getElementById('branch_id');
    const unitTypeSelect = document.getElementById('unit_type_id');
    const operationalProfileSelect = document.getElementById(
        'operational_profile_id'
    );

    if (!unitTypeSelect || !operationalProfileSelect) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Dados enviados pelo Blade
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
            'Erro ao carregar os dados da unidade operacional:',
            error
        );

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Valores existentes
    |--------------------------------------------------------------------------
    |
    | Utilizados principalmente no modo de edição.
    |
    */

    const currentUnitTypeId =
        form.dataset.currentUnitType || '';

    const currentOperationalProfileId =
        form.dataset.currentProfile || '';

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

    function loadUnitTypes(
        branchId,
        selectedUnitTypeId = ''
    ) {
        clearSelect(
            unitTypeSelect,
            'Selecione o tipo de unidade'
        );

        clearSelect(
            operationalProfileSelect,
            'Selecione o perfil operacional'
        );

        disableSelect(operationalProfileSelect);

        if (!branchId) {
            disableSelect(unitTypeSelect);
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
            disableSelect(unitTypeSelect);
            return;
        }

        availableUnitTypes.forEach(unitType => {
            const option = document.createElement('option');

            option.value = unitType.id;
            option.textContent = unitType.name;

            if (
                normalizeId(unitType.id) ===
                normalizeId(selectedUnitTypeId)
            ) {
                option.selected = true;
            }

            unitTypeSelect.appendChild(option);
        });

        enableSelect(unitTypeSelect);

        /*
        |--------------------------------------------------------------------------
        | Edição
        |--------------------------------------------------------------------------
        */

        if (selectedUnitTypeId) {
            loadOperationalProfiles(
                selectedUnitTypeId,
                currentOperationalProfileId
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Carrega os perfis operacionais
    |--------------------------------------------------------------------------
    */

    function loadOperationalProfiles(
        unitTypeId,
        selectedOperationalProfileId = ''
    ) {
        clearSelect(
            operationalProfileSelect,
            'Selecione o perfil operacional'
        );

        if (!unitTypeId) {
            disableSelect(operationalProfileSelect);
            return;
        }

        const availableProfiles =
            operationalProfiles.filter(
                profile =>
                    normalizeId(profile.unit_type_id) ===
                    normalizeId(unitTypeId)
            );

        if (availableProfiles.length === 0) {
            disableSelect(operationalProfileSelect);
            return;
        }

        availableProfiles.forEach(profile => {
            const option = document.createElement('option');

            option.value = profile.id;
            option.textContent = profile.name;

            if (
                normalizeId(profile.id) ===
                normalizeId(selectedOperationalProfileId)
            ) {
                option.selected = true;
            }

            operationalProfileSelect.appendChild(option);
        });

        enableSelect(operationalProfileSelect);
    }

    /*
    |--------------------------------------------------------------------------
    | Evento: alteração da filial
    |--------------------------------------------------------------------------
    */

    if (branchSelect) {
        branchSelect.addEventListener('change', () => {
            const branchId = branchSelect.value;

            loadUnitTypes(branchId);
        });
    }

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
    | Inicialização
    |--------------------------------------------------------------------------
    */

    if (branchSelect) {
        const initialBranchId = branchSelect.value;

        if (initialBranchId) {
            loadUnitTypes(
                initialBranchId,
                currentUnitTypeId
            );
        } else {
            disableSelect(unitTypeSelect);
            disableSelect(operationalProfileSelect);
        }

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Edit sem campo de filial
    |--------------------------------------------------------------------------
    */

    if (currentUnitTypeId) {
        loadOperationalProfiles(
            currentUnitTypeId,
            currentOperationalProfileId
        );
    } else {
        disableSelect(unitTypeSelect);
        disableSelect(operationalProfileSelect);
    }
});
