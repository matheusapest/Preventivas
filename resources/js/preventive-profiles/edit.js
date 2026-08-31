document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('preventive-profile-form');

    if (!form) {
        console.error(
            '[PreventiveProfile Edit] Formulário não encontrado.'
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
            '[PreventiveProfile Edit] Select preventive_type_id não encontrado.'
        );

        return;
    }

    if (!branchesContainer) {
        console.error(
            '[PreventiveProfile Edit] Container eligible-branches não encontrado.'
        );

        return;
    }

    const eligibleBranchesUrl =
        form.dataset.eligibleBranchesUrl;

    if (!eligibleBranchesUrl) {
        console.error(
            '[PreventiveProfile Edit] URL de filiais elegíveis não encontrada.'
        );

        return;
    }

    if (!eligibleBranchesUrl.includes('__TYPE__')) {
        console.error(
            '[PreventiveProfile Edit] URL não contém o placeholder __TYPE__:',
            eligibleBranchesUrl
        );

        return;
    }

    let selectedBranches = [];

    try {
        selectedBranches = JSON.parse(
            form.dataset.selectedBranches || '[]'
        );

        selectedBranches = selectedBranches.map(
            id => Number(id)
        );
    } catch (error) {
        console.error(
            '[PreventiveProfile Edit] Erro ao ler filiais selecionadas:',
            error
        );
    }

    console.log(
        '[PreventiveProfile Edit] Inicializado.'
    );

    console.log(
        '[PreventiveProfile Edit] Tipo atual:',
        preventiveTypeSelect.value
    );

    console.log(
        '[PreventiveProfile Edit] Filiais atuais:',
        selectedBranches
    );

    console.log(
        '[PreventiveProfile Edit] URL base:',
        eligibleBranchesUrl
    );


    /*
     * Quando o usuário alterar o tipo de preventiva,
     * carregamos novamente as filiais elegíveis.
     */
    preventiveTypeSelect.addEventListener('change', () => {
        const preventiveTypeId =
            preventiveTypeSelect.value;

        console.log(
            '[PreventiveProfile Edit] Novo tipo selecionado:',
            preventiveTypeId
        );

        /*
         * Se o usuário trocar o tipo, as filiais anteriores
         * não devem continuar selecionadas.
         */
        selectedBranches = [];

        clearBranches();

        if (!preventiveTypeId) {
            return;
        }

        loadEligibleBranches(
            preventiveTypeId,
            selectedBranches
        );
    });


    /*
     * Ao abrir a tela de edição, o tipo já está selecionado.
     * Portanto precisamos carregar automaticamente as filiais.
     */
    const initialPreventiveType =
        preventiveTypeSelect.value;

    if (initialPreventiveType) {
        console.log(
            '[PreventiveProfile Edit] Carregando filiais iniciais...'
        );

        loadEligibleBranches(
            initialPreventiveType,
            selectedBranches
        );
    }


    async function loadEligibleBranches(
        preventiveTypeId,
        selectedBranchIds = []
    ) {
        showLoading();

        const url = eligibleBranchesUrl.replace(
            '__TYPE__',
            encodeURIComponent(preventiveTypeId)
        );

        console.log(
            '[PreventiveProfile Edit] Buscando filiais:',
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
                '[PreventiveProfile Edit] HTTP status:',
                response.status
            );

            if (!response.ok) {
                throw new Error(
                    `Erro HTTP ${response.status}`
                );
            }

            const branches = await response.json();

            console.log(
                '[PreventiveProfile Edit] Filiais recebidas:',
                branches
            );

            renderBranches(
                branches,
                selectedBranchIds
            );

        } catch (error) {

            console.error(
                '[PreventiveProfile Edit] Erro ao carregar filiais:',
                error
            );

            showError();

        } finally {

            hideLoading();

        }
    }


    function renderBranches(
        branches,
        selectedBranchIds = []
    ) {
        branchesContainer.innerHTML = '';

        if (
            !Array.isArray(branches) ||
            branches.length === 0
        ) {
            showEmpty();
            return;
        }

        branches.forEach(branch => {

            const branchId = Number(branch.id);

            const wrapper =
                document.createElement('label');

            wrapper.className =
                'flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 bg-white p-4 transition hover:border-blue-400 hover:bg-blue-50';

            const checkbox =
                document.createElement('input');

            checkbox.type = 'checkbox';

            checkbox.name = 'branch_ids[]';

            checkbox.value = branch.id;

            checkbox.className =
                'h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500';

            /*
             * Marca automaticamente as filiais que já pertencem
             * ao perfil.
             */
            checkbox.checked =
                selectedBranchIds.includes(branchId);

            const content =
                document.createElement('div');

            content.className = 'min-w-0';

            const name =
                document.createElement('span');

            name.className =
                'block font-medium text-slate-800';

            name.textContent =
                branch.name;

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

            branchesEmpty.innerHTML = `
                <p class="font-medium text-slate-700">
                    Selecione um tipo de preventiva.
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    As filiais elegíveis serão carregadas após selecionar o tipo de preventiva.
                </p>
            `;
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

            branchesEmpty.innerHTML = `
                <p class="font-medium text-slate-700">
                    Nenhuma filial disponível.
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Não existem filiais disponíveis para este tipo de preventiva.
                </p>
            `;
        }
    }


    function showError() {
        branchesContainer.classList.add('hidden');

        if (branchesEmpty) {
            branchesEmpty.classList.remove('hidden');

            branchesEmpty.innerHTML = `
                <p class="font-medium text-red-700">
                    Não foi possível carregar as filiais.
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Tente novamente ou verifique o tipo de preventiva selecionado.
                </p>
            `;
        }
    }
});
