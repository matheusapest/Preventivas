document.addEventListener('DOMContentLoaded', function () {
    /*
     * ============================================================
     * ELEMENTOS PRINCIPAIS
     * ============================================================
     */

    const unitSelect = document.getElementById(
        'continuation-unit-select'
    );

    const activitiesContainer = document.getElementById(
        'continuation-activities-container'
    );

    const activitiesResults = document.getElementById(
        'continuation-activities-results'
    );

    const activitiesLoading = document.getElementById(
        'continuation-activities-loading'
    );

    const activitiesError = document.getElementById(
        'continuation-activities-error'
    );

    const selectedUnitName = document.getElementById(
        'continuation-selected-unit-name'
    );

    const selectedUnitIdentifier = document.getElementById(
        'continuation-selected-unit-identifier'
    );

    const addUnitButton = document.getElementById(
        'add-continuation-unit'
    );

    const selectedUnitsContainer = document.getElementById(
        'continuation-selected-units'
    );

    const selectedCount = document.getElementById(
        'continuation-selected-count'
    );

    const emptyState = document.getElementById(
        'continuation-empty-state'
    );

    const form = document.getElementById(
        'continuation-form'
    );

    const submitButton = document.getElementById(
        'submit-continuation'
    );

    /*
     * Se os elementos essenciais não existirem,
     * não executa o restante do script.
     */
    if (
        !unitSelect ||
        !activitiesContainer ||
        !activitiesResults ||
        !addUnitButton ||
        !selectedUnitsContainer
    ) {
        return;
    }

    /*
     * ============================================================
     * ESTADO
     * ============================================================
     *
     * Cada unidade é armazenada pelo seu
     * operational_unit_id.
     *
     * Estrutura:
     *
     * selectedUnits = Map {
     *     27 => {
     *         operational_unit_id: 27,
     *         name: "PDV 05",
     *         identifier: "PDV 05",
     *         activities: [
     *             {
     *                 id: 25,
     *                 name: "Teste Operacional",
     *                 description: "...",
     *                 type: "..."
     *             }
     *         ]
     *     }
     * }
     */

    const selectedUnits = new Map();

    /*
     * Unidade atualmente carregada no seletor.
     */
    let currentUnit = null;

    /*
     * Atividades retornadas pelo endpoint para a
     * unidade atualmente selecionada.
     */
    let currentActivities = [];

    /*
     * ============================================================
     * UTILITÁRIOS
     * ============================================================
     */

    function escapeHtml(value) {
        const div = document.createElement('div');

        div.textContent = value ?? '';

        return div.innerHTML;
    }

    function getActivitiesUrl(unitId) {
        const template = unitSelect.dataset.activitiesUrl;

        if (!template) {
            throw new Error(
                'A URL das atividades da continuidade não foi configurada.'
            );
        }

        return template.replace(
            '__UNIT_ID__',
            encodeURIComponent(unitId)
        );
    }

    /*
     * ============================================================
     * ERROS / LOADING DAS ATIVIDADES
     * ============================================================
     */

    function showActivitiesError(message) {
        if (!activitiesError) {
            return;
        }

        activitiesError.textContent = message;

        activitiesError.classList.remove('hidden');
    }

    function clearActivitiesError() {
        if (!activitiesError) {
            return;
        }

        activitiesError.textContent = '';

        activitiesError.classList.add('hidden');
    }

    function setActivitiesLoading(loading) {
        if (!activitiesLoading) {
            return;
        }

        if (loading) {
            activitiesLoading.classList.remove('hidden');
        } else {
            activitiesLoading.classList.add('hidden');
        }
    }

    /*
     * ============================================================
     * SELECT DE UNIDADES
     * ============================================================
     *
     * Uma unidade que já foi adicionada ao agregador
     * não fica mais disponível no select.
     */

    function refreshUnitSelect() {
        Array.from(unitSelect.options).forEach(
            function (option) {
                if (!option.value) {
                    return;
                }

                const unitId = Number(option.value);

                option.hidden =
                    selectedUnits.has(unitId);
            }
        );

        unitSelect.value = '';
    }

    /*
     * ============================================================
     * BLOCO DE UNIDADES PENDENTES
     * ============================================================
     *
     * Uma unidade pendente adicionada ao agregador
     * desaparece do bloco de pendências.
     *
     * Se for removida do agregador, volta a aparecer.
     */

    function refreshPendingUnits() {
        const pendingCards =
            document.querySelectorAll(
                '[data-pending-unit]'
            );

        let visibleCount = 0;

        pendingCards.forEach(
            function (card) {
                const unitId = Number(
                    card.dataset.operationalUnitId
                );

                const selected =
                    selectedUnits.has(unitId);

                card.classList.toggle(
                    'hidden',
                    selected
                );

                if (!selected) {
                    visibleCount++;
                }
            }
        );

        const emptyMessage =
            document.getElementById(
                'pending-units-empty'
            );

        if (emptyMessage) {
            emptyMessage.classList.toggle(
                'hidden',
                visibleCount > 0
            );
        }
    }

    /*
     * ============================================================
     * CONTROLE GERAL DA INTERFACE
     * ============================================================
     */

    function refreshSelectionUI() {
        refreshUnitSelect();

        refreshPendingUnits();

        updateSelectedCount();

        updateSubmitState();

        syncFormInputs();
    }

    /*
     * ============================================================
     * CONTADOR DE UNIDADES
     * ============================================================
     */

    function updateSelectedCount() {
        if (!selectedCount) {
            return;
        }

        selectedCount.textContent =
            selectedUnits.size;
    }

    /*
     * ============================================================
     * BOTÃO DE SUBMIT
     * ============================================================
     */

    function updateSubmitState() {
        if (!submitButton) {
            return;
        }

        submitButton.disabled =
            selectedUnits.size === 0;
    }

    /*
     * ============================================================
     * CARREGAR ATIVIDADES DA UNIDADE
     * ============================================================
     */

    async function loadActivities(unit) {
        currentUnit = unit;

        currentActivities = [];

        activitiesContainer.classList.remove(
            'hidden'
        );

        activitiesResults.innerHTML = '';

        clearActivitiesError();

        addUnitButton.classList.add('hidden');

        addUnitButton.disabled = true;

        if (selectedUnitName) {
            selectedUnitName.textContent =
                unit.name;
        }

        if (selectedUnitIdentifier) {
            selectedUnitIdentifier.textContent =
                unit.identifier;
        }

        setActivitiesLoading(true);

        try {
            const response = await fetch(
                getActivitiesUrl(
                    unit.operational_unit_id
                ),
                {
                    method: 'GET',

                    headers: {
                        Accept:
                            'application/json',

                        'X-Requested-With':
                            'XMLHttpRequest',
                    },
                }
            );

            let data = null;

            try {
                data = await response.json();
            } catch {
                throw new Error(
                    'O servidor retornou uma resposta inválida.'
                );
            }

            if (!response.ok) {
                throw new Error(
                    data.message ||
                    'Não foi possível carregar as atividades da unidade.'
                );
            }

            currentActivities =
                Array.isArray(data.activities)
                    ? data.activities
                    : [];

            renderActivities(
                currentActivities
            );

        } catch (error) {
            console.error(
                'Erro ao carregar atividades:',
                error
            );

            showActivitiesError(
                error.message ||
                'Não foi possível carregar as atividades.'
            );

        } finally {
            setActivitiesLoading(false);
        }
    }

    /*
     * ============================================================
     * RENDERIZAÇÃO DAS ATIVIDADES
     * ============================================================
     */

    function renderActivities(activities) {
        activitiesResults.innerHTML = '';

        if (!activities.length) {
            activitiesResults.innerHTML = `
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                    Nenhuma atividade disponível para esta unidade.
                </div>
            `;

            return;
        }

        activities.forEach(
            function (activity) {
                const wrapper =
                    document.createElement('label');

                wrapper.className =
                    'flex cursor-pointer items-start gap-3 rounded-lg border border-slate-200 bg-white p-3 transition hover:bg-slate-50';

                wrapper.innerHTML = `
                    <input
                        type="checkbox"
                        class="js-continuation-activity mt-0.5 h-4 w-4 rounded border-slate-300 text-slate-800 focus:ring-slate-500"
                        value="${escapeHtml(activity.id)}"
                    >

                    <span class="min-w-0">

                        <span class="block text-sm font-medium text-slate-800">
                            ${escapeHtml(
                                activity.name ||
                                'Atividade'
                            )}
                        </span>

                        ${
                            activity.description
                                ? `
                                    <span class="mt-0.5 block text-xs leading-5 text-slate-500">
                                        ${escapeHtml(
                                            activity.description
                                        )}
                                    </span>
                                `
                                : ''
                        }

                    </span>
                `;

                activitiesResults.appendChild(
                    wrapper
                );
            }
        );

        activitiesResults
            .querySelectorAll(
                '.js-continuation-activity'
            )
            .forEach(
                function (checkbox) {
                    checkbox.addEventListener(
                        'change',
                        updateAddButtonState
                    );
                }
            );

        updateAddButtonState();
    }

    /*
     * ============================================================
     * HABILITAR / DESABILITAR "ADICIONAR UNIDADE"
     * ============================================================
     */

    function updateAddButtonState() {
        const checkedActivities =
            activitiesResults.querySelectorAll(
                '.js-continuation-activity:checked'
            );

        const hasActivities =
            checkedActivities.length > 0;

        if (hasActivities) {
            addUnitButton.classList.remove(
                'hidden'
            );

            addUnitButton.disabled = false;

            return;
        }

        addUnitButton.classList.add(
            'hidden'
        );

        addUnitButton.disabled = true;
    }

    /*
     * ============================================================
     * OBTER ATIVIDADES SELECIONADAS
     * ============================================================
     *
     * Aqui usamos currentActivities para recuperar
     * o objeto completo da atividade.
     *
     * Assim o agregador possui:
     *
     * id
     * name
     * description
     * type
     */

    function getSelectedActivities() {
        const checkedActivities =
            activitiesResults.querySelectorAll(
                '.js-continuation-activity:checked'
            );

        return Array.from(
            checkedActivities
        )
            .map(
                function (checkbox) {
                    const activityId =
                        Number(
                            checkbox.value
                        );

                    return currentActivities.find(
                        function (activity) {
                            return Number(
                                activity.id
                            ) === activityId;
                        }
                    );
                }
            )
            .filter(Boolean);
    }

    /*
     * ============================================================
     * ADICIONAR UNIDADE AO AGREGADOR
     * ============================================================
     */

    function addCurrentUnit() {
        if (!currentUnit) {
            return;
        }

        const activities =
            getSelectedActivities();

        if (!activities.length) {
            return;
        }

        const unitId =
            Number(
                currentUnit.operational_unit_id
            );

        /*
         * Proteção contra duplicidade.
         */
        if (selectedUnits.has(unitId)) {
            return;
        }

        selectedUnits.set(
            unitId,
            {
                operational_unit_id:
                    unitId,

                name:
                    currentUnit.name,

                identifier:
                    currentUnit.identifier,

                activities:
                    activities,
            }
        );

        renderSelectedUnits();

        refreshSelectionUI();

        resetActivitySelector();
    }

    /*
     * ============================================================
     * RENDERIZAR AGREGADOR
     * ============================================================
     */

    function renderSelectedUnits() {
        /*
         * Remove somente os cards criados pelo JS.
         */
        selectedUnitsContainer
            .querySelectorAll(
                '[data-selected-unit]'
            )
            .forEach(
                function (element) {
                    element.remove();
                }
            );

        /*
         * Estado vazio.
         */
        if (emptyState) {
            emptyState.classList.toggle(
                'hidden',
                selectedUnits.size > 0
            );
        }

        /*
         * Renderiza cada unidade.
         */
        selectedUnits.forEach(
            function (unit) {
                const card =
                    document.createElement('div');

                card.dataset.selectedUnit =
                    'true';

                card.className =
                    'rounded-lg border border-slate-200 bg-white p-4';

                const activitiesHtml =
                    unit.activities
                        .map(
                            function (activity) {
                                return `
                                    <div class="rounded-lg bg-slate-50 px-3 py-2">

                                        <p class="text-xs font-medium text-slate-800">
                                            ${escapeHtml(
                                                activity.name ||
                                                'Atividade'
                                            )}
                                        </p>

                                        ${
                                            activity.description
                                                ? `
                                                    <p class="mt-0.5 text-[11px] leading-4 text-slate-500">
                                                        ${escapeHtml(
                                                            activity.description
                                                        )}
                                                    </p>
                                                `
                                                : ''
                                        }

                                    </div>
                                `;
                            }
                        )
                        .join('');

                card.innerHTML = `
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

                        <div class="min-w-0 flex-1">

                            <div class="flex flex-wrap items-center gap-2">

                                <p class="text-sm font-semibold text-slate-900">
                                    ${escapeHtml(
                                        unit.name
                                    )}
                                </p>

                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">
                                    ${escapeHtml(
                                        unit.identifier
                                    )}
                                </span>

                            </div>

                            <div class="mt-3 space-y-2">
                                ${activitiesHtml}
                            </div>

                        </div>

                        <button
                            type="button"
                            class="js-remove-continuation-unit shrink-0 text-xs font-semibold text-red-600 transition hover:text-red-800"
                            data-unit-id="${unit.operational_unit_id}"
                        >
                            Remover
                        </button>

                    </div>
                `;

                selectedUnitsContainer.appendChild(
                    card
                );
            }
        );
    }

    /*
     * ============================================================
     * REMOVER UNIDADE
     * ============================================================
     */

    selectedUnitsContainer.addEventListener(
        'click',
        function (event) {
            const button =
                event.target.closest(
                    '.js-remove-continuation-unit'
                );

            if (!button) {
                return;
            }

            const unitId =
                Number(
                    button.dataset.unitId
                );

            selectedUnits.delete(unitId);

            renderSelectedUnits();

            refreshSelectionUI();
        }
    );

    /*
     * ============================================================
     * RESETAR SELETOR DE ATIVIDADES
     * ============================================================
     */

    function resetActivitySelector() {
        currentUnit = null;

        currentActivities = [];

        activitiesResults.innerHTML = '';

        clearActivitiesError();

        activitiesContainer.classList.add(
            'hidden'
        );

        addUnitButton.classList.add(
            'hidden'
        );

        addUnitButton.disabled = true;

        if (selectedUnitName) {
            selectedUnitName.textContent = '';
        }

        if (selectedUnitIdentifier) {
            selectedUnitIdentifier.textContent =
                '';
        }
    }

    /*
     * ============================================================
     * SELECT DE UNIDADE
     * ============================================================
     */

    unitSelect.addEventListener(
        'change',
        function () {
            const selectedOption =
                unitSelect.options[
                    unitSelect.selectedIndex
                ];

            if (
                !selectedOption ||
                !selectedOption.value
            ) {
                resetActivitySelector();

                return;
            }

            const unitId =
                Number(
                    selectedOption.value
                );

            /*
             * Proteção contra uma unidade que já
             * esteja no agregador.
             */
            if (selectedUnits.has(unitId)) {
                unitSelect.value = '';

                return;
            }

            loadActivities({
                operational_unit_id:
                    unitId,

                name:
                    selectedOption.dataset.name ||
                    selectedOption.textContent.trim(),

                identifier:
                    selectedOption.dataset.identifier ||
                    selectedOption.textContent.trim(),
            });
        }
    );

    /*
     * ============================================================
     * BOTÃO "ADICIONAR UNIDADE"
     * ============================================================
     */

    addUnitButton.addEventListener(
        'click',
        function () {
            addCurrentUnit();
        }
    );

    /*
     * ============================================================
     * ATALHOS DAS UNIDADES PENDENTES
     * ============================================================
     *
     * O botão de uma pendência não adiciona diretamente.
     *
     * Ele seleciona a unidade no mesmo <select> utilizado
     * pelo fluxo normal.
     */

    document
        .querySelectorAll(
            '.js-add-pending-unit'
        )
        .forEach(
            function (button) {
                button.addEventListener(
                    'click',
                    function () {
                        const unitId =
                            Number(
                                button.dataset
                                    .operationalUnitId
                            );

                        if (
                            !unitId ||
                            selectedUnits.has(
                                unitId
                            )
                        ) {
                            return;
                        }

                        const option =
                            Array.from(
                                unitSelect.options
                            ).find(
                                function (
                                    option
                                ) {
                                    return Number(
                                        option.value
                                    ) === unitId;
                                }
                            );

                        if (!option) {
                            return;
                        }

                        unitSelect.value =
                            String(unitId);

                        unitSelect.dispatchEvent(
                            new Event(
                                'change'
                            )
                        );
                    }
                );
            }
        );

    /*
     * ============================================================
     * INPUTS HIDDEN DO FORMULÁRIO
     * ============================================================
     *
     * O backend recebe somente:
     *
     * units[index][operational_unit_id]
     * units[index][activities][]
     *
     * Nenhum nome ou descrição é enviado.
     *
     * Esses dados pertencem ao snapshot e o backend
     * deve validar novamente os IDs recebidos.
     */

    function syncFormInputs() {
        if (!form) {
            return;
        }

        form.querySelectorAll(
            '.js-continuation-hidden-input'
        ).forEach(
            function (input) {
                input.remove();
            }
        );

        let unitIndex = 0;

        selectedUnits.forEach(
            function (unit) {
                const unitInput =
                    document.createElement(
                        'input'
                    );

                unitInput.type = 'hidden';

                unitInput.name =
                    `units[${unitIndex}][operational_unit_id]`;

                unitInput.value =
                    unit.operational_unit_id;

                unitInput.classList.add(
                    'js-continuation-hidden-input'
                );

                form.appendChild(
                    unitInput
                );

                unit.activities.forEach(
                    function (activity) {
                        const activityInput =
                            document.createElement(
                                'input'
                            );

                        activityInput.type =
                            'hidden';

                        activityInput.name =
                            `units[${unitIndex}][activities][]`;

                        activityInput.value =
                            activity.id;

                        activityInput.classList.add(
                            'js-continuation-hidden-input'
                        );

                        form.appendChild(
                            activityInput
                        );
                    }
                );

                unitIndex++;
            }
        );
    }

    /*
     * ============================================================
     * ESTADO INICIAL
     * ============================================================
     */

    refreshSelectionUI();
});
