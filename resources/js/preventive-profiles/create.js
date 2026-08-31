document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('preventive-profile-form');

    if (!form) {
        console.error(
            '[PreventiveProfile] Formulário não encontrado.'
        );

        return;
    }

    const preventiveTypeSelect =
        document.getElementById('preventive_type_id');

    const branchesContainer =
        document.getElementById('eligible-branches');

    const branchesLoading =
        document.getElementById('branches-loading');

    const branchesEmpty =
        document.getElementById('branches-empty');

    if (!preventiveTypeSelect) {
        console.error(
            '[PreventiveProfile] Select preventive_type_id não encontrado.'
        );

        return;
    }

    if (!branchesContainer) {
        console.error(
            '[PreventiveProfile] Container eligible-branches não encontrado.'
        );

        return;
    }

    const eligibleBranchesUrl =
        form.dataset.eligibleBranchesUrl;

    if (!eligibleBranchesUrl) {
        console.error(
            '[PreventiveProfile] URL de filiais elegíveis não encontrada.'
        );

        return;
    }

    if (!eligibleBranchesUrl.includes('__TYPE__')) {
        console.error(
            '[PreventiveProfile] URL não contém o placeholder __TYPE__:',
            eligibleBranchesUrl
        );

        return;
    }

    console.log('[PreventiveProfile] Inicializado.');
    console.log(
        '[PreventiveProfile] URL base:',
        eligibleBranchesUrl
    );

    preventiveTypeSelect.addEventListener('change', () => {
        const preventiveTypeId = preventiveTypeSelect.value;

        console.log(
            '[PreventiveProfile] Tipo selecionado:',
            preventiveTypeId
        );

        clearBranches();

        if (!preventiveTypeId) {
            return;
        }

        loadEligibleBranches(preventiveTypeId);
    });

    async function loadEligibleBranches(preventiveTypeId) {
        showLoading();

        const url = eligibleBranchesUrl.replace(
            '__TYPE__',
            encodeURIComponent(preventiveTypeId)
        );

        console.log(
            '[PreventiveProfile] Buscando filiais:',
            url
        );

        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            console.log(
                '[PreventiveProfile] HTTP status:',
                response.status
            );

            if (!response.ok) {
                throw new Error(
                    `Erro HTTP ${response.status}`
                );
            }

            const branches = await response.json();

            console.log(
                '[PreventiveProfile] Filiais recebidas:',
                branches
            );

            renderBranches(branches);
        } catch (error) {
            console.error(
                '[PreventiveProfile] Erro ao carregar filiais:',
                error
            );

            showError();
        } finally {
            hideLoading();
        }
    }

    function renderBranches(branches) {
        branchesContainer.innerHTML = '';

        if (!Array.isArray(branches) || branches.length === 0) {
            showEmpty();
            return;
        }

        branches.forEach((branch) => {
            const wrapper = document.createElement('label');

            wrapper.className =
                'flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 bg-white p-4 transition hover:border-blue-400 hover:bg-blue-50';

            const checkbox = document.createElement('input');

            checkbox.type = 'checkbox';
            checkbox.name = 'branch_ids[]';
            checkbox.value = branch.id;

            checkbox.className =
                'h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500';

            const content = document.createElement('div');

            content.className = 'min-w-0';

            const name = document.createElement('span');

            name.className =
                'block font-medium text-slate-800';

            name.textContent = branch.name;

            content.appendChild(name);

            wrapper.appendChild(checkbox);
            wrapper.appendChild(content);

            branchesContainer.appendChild(wrapper);
        });

        branchesContainer.classList.remove('hidden');

        if (branchesEmpty) {
            branchesEmpty.classList.add('hidden');
        }
    }

    function clearBranches() {
        branchesContainer.innerHTML = '';
        branchesContainer.classList.add('hidden');

        if (branchesEmpty) {
            branchesEmpty.classList.remove('hidden');

            branchesEmpty.textContent =
                'Selecione um tipo de preventiva para carregar as filiais elegíveis.';
        }

        if (branchesLoading) {
            branchesLoading.classList.add('hidden');
        }
    }

    function showLoading() {
        branchesContainer.classList.add('hidden');

        if (branchesEmpty) {
            branchesEmpty.classList.add('hidden');
        }

        if (branchesLoading) {
            branchesLoading.classList.remove('hidden');
        }
    }

    function hideLoading() {
        if (branchesLoading) {
            branchesLoading.classList.add('hidden');
        }
    }

    function showEmpty() {
        branchesContainer.classList.add('hidden');

        if (branchesEmpty) {
            branchesEmpty.classList.remove('hidden');

            branchesEmpty.textContent =
                'Nenhuma filial disponível para este tipo de preventiva.';
        }
    }

    function showError() {
        branchesContainer.classList.add('hidden');

        if (branchesEmpty) {
            branchesEmpty.classList.remove('hidden');

            branchesEmpty.textContent =
                'Não foi possível carregar as filiais. Tente novamente.';
        }
    }
});
