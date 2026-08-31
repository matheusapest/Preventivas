document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('specific-rule-modal');

    if (!modal) {
        return;
    }

    const openButtons = document.querySelectorAll(
        '[data-specific-rule-open]'
    );

    const editButtons = document.querySelectorAll(
        '[data-specific-rule-edit]'
    );

    const closeButtons = document.querySelectorAll(
        '[data-specific-rule-close]'
    );

    const backdrop = modal.querySelector(
        '[data-specific-rule-backdrop]'
    );

    const form = modal.querySelector('form');

    const modalTitle = document.getElementById(
        'specific-rule-modal-title'
    );

    const modalDescription = document.getElementById(
        'specific-rule-modal-description'
    );

    const submitButton = modal.querySelector(
        '[data-specific-rule-submit]'
    );

    const submitButtonText = modal.querySelector(
        '[data-specific-rule-submit-text]'
    );

    const operationalUnitSelect = document.getElementById(
        'specific-operational-unit'
    );

    const editOperationalUnit = document.getElementById(
        'specific-edit-operational-unit'
    );

    const editOperationalUnitLabel = document.getElementById(
        'specific-edit-operational-unit-label'
    );

    const activityCheckboxes = modal.querySelectorAll(
        'input[name="activity_ids[]"]'
    );

    const methodInput = form?.querySelector(
        'input[name="_method"]'
    );

    const specificRuleIdInput = form?.querySelector(
        'input[name="specific_rule_id"]'
    );

    /*
     * ==========================================================================
     * Estado
     * ==========================================================================
     *
     * false = criação
     * true  = edição
     */
    let editing = false;

    /*
     * ==========================================================================
     * Input hidden da unidade
     * ==========================================================================
     *
     * Durante a edição o select fica disabled.
     *
     * Campos disabled NÃO são enviados pelo navegador.
     *
     * Por isso mantemos um hidden com o mesmo name:
     *
     * operational_unit_id
     *
     * Assim o backend continua recebendo o ID da unidade.
     */

    let hiddenOperationalUnitInput =
        form?.querySelector(
            'input[type="hidden"][name="operational_unit_id"]'
        );

    const ensureHiddenOperationalUnitInput = () => {
        if (!form) {
            return null;
        }

        if (!hiddenOperationalUnitInput) {
            hiddenOperationalUnitInput =
                document.createElement('input');

            hiddenOperationalUnitInput.type = 'hidden';
            hiddenOperationalUnitInput.name =
                'operational_unit_id';

            form.appendChild(
                hiddenOperationalUnitInput
            );
        }

        return hiddenOperationalUnitInput;
    };

    /*
     * ==========================================================================
     * Abre o modal
     * ==========================================================================
     */

    const openModal = () => {
        modal.classList.remove('hidden');

        document.body.classList.add('overflow-hidden');

        window.setTimeout(() => {
            if (editing) {
                return;
            }

            if (operationalUnitSelect) {
                operationalUnitSelect.focus();
            }
        }, 50);
    };

    /*
     * ==========================================================================
     * Fecha o modal
     * ==========================================================================
     */

    const closeModal = () => {
        modal.classList.add('hidden');

        document.body.classList.remove('overflow-hidden');
    };

    /*
     * ==========================================================================
     * Configura CRIAÇÃO
     * ==========================================================================
     */

    const resetCreateForm = () => {
        editing = false;

        /*
         * Título
         */
        if (modalTitle) {
            modalTitle.textContent =
                'Nova regra específica';
        }

        /*
         * Descrição
         */
        if (modalDescription) {
            modalDescription.textContent =
                'Configure atividades diferentes da regra Todos para uma unidade específica.';
        }

        /*
         * Botão
         */
        if (submitButtonText) {
            submitButtonText.textContent =
                'Criar regra';
        }

        /*
         * Método
         */
        if (methodInput) {
            methodInput.value = 'POST';
        }

        /*
         * ID da regra específica
         */
        if (specificRuleIdInput) {
            specificRuleIdInput.value = '';
        }

        /*
         * Unidade:
         *
         * Na criação o select fica disponível.
         */
        if (operationalUnitSelect) {
            operationalUnitSelect.classList.remove(
                'hidden'
            );

            operationalUnitSelect.disabled = false;

            /*
             * Não forçamos value = '' aqui.
             *
             * Isso é importante quando estamos voltando
             * após erro de validação e o Blade restaurou
             * o old('operational_unit_id').
             */
        }

        /*
         * Remove o hidden usado na edição.
         *
         * Porém não removemos o elemento do DOM.
         * Apenas limpamos seu valor.
         */
        const hiddenInput =
            ensureHiddenOperationalUnitInput();

        if (hiddenInput) {
            hiddenInput.value = '';
            hiddenInput.disabled = true;
        }

        /*
         * Esconde unidade somente leitura.
         */
        if (editOperationalUnit) {
            editOperationalUnit.classList.add(
                'hidden'
            );
        }

        if (editOperationalUnitLabel) {
            editOperationalUnitLabel.textContent = '';
        }

        /*
         * Atividades.
         *
         * Também não limpamos aqui para preservar
         * valores restaurados pelo old() em caso de erro.
         */
        const hasOldActivities =
            Array.from(activityCheckboxes).some(
                (checkbox) => checkbox.checked
            );

        if (!hasOldActivities) {
            activityCheckboxes.forEach((checkbox) => {
                checkbox.checked = false;
            });
        }

        /*
         * URL de criação.
         */
        const createUrl =
            form?.dataset.createUrl;

        if (form && createUrl) {
            form.action = createUrl;
        }
    };

    /*
     * ==========================================================================
     * Configura EDIÇÃO
     * ==========================================================================
     */

    const prepareEditForm = (button) => {
        editing = true;

        const specificRuleId =
            button.dataset.specificRuleId || '';

        const operationalUnitId =
            button.dataset.operationalUnitId || '';

        const operationalUnitLabel =
            button.dataset.operationalUnitLabel ||
            'Unidade';

        let activityIds = [];

        /*
         * ----------------------------------------------------------------------
         * Atividades
         * ----------------------------------------------------------------------
         */

        try {
            activityIds = JSON.parse(
                button.dataset.activityIds || '[]'
            );
        } catch (error) {
            console.error(
                'Não foi possível interpretar os IDs das atividades.',
                error
            );

            activityIds = [];
        }

        /*
         * ----------------------------------------------------------------------
         * Título
         * ----------------------------------------------------------------------
         */

        if (modalTitle) {
            modalTitle.textContent =
                'Editar regra específica';
        }

        /*
         * ----------------------------------------------------------------------
         * Descrição
         * ----------------------------------------------------------------------
         */

        if (modalDescription) {
            modalDescription.textContent =
                'Altere as atividades configuradas para esta regra específica.';
        }

        /*
         * ----------------------------------------------------------------------
         * Botão
         * ----------------------------------------------------------------------
         */

        if (submitButtonText) {
            submitButtonText.textContent =
                'Salvar alterações';
        }

        /*
         * ----------------------------------------------------------------------
         * Método HTTP
         * ----------------------------------------------------------------------
         */

        if (methodInput) {
            methodInput.value = 'PUT';
        }

        /*
         * ----------------------------------------------------------------------
         * ID da regra específica
         * ----------------------------------------------------------------------
         */

        if (specificRuleIdInput) {
            specificRuleIdInput.value =
                specificRuleId;
        }

        /*
         * ----------------------------------------------------------------------
         * Unidade operacional
         * ----------------------------------------------------------------------
         *
         * A unidade NÃO pode ser alterada durante a edição.
         *
         * Visualmente:
         * - select fica oculto
         * - unidade somente leitura aparece
         *
         * No envio:
         * - hidden envia operational_unit_id
         */

        if (operationalUnitSelect) {
            operationalUnitSelect.classList.add(
                'hidden'
            );

            operationalUnitSelect.disabled = true;

            operationalUnitSelect.value =
                operationalUnitId;
        }

        /*
         * Hidden que realmente será enviado.
         */
        const hiddenInput =
            ensureHiddenOperationalUnitInput();

        if (hiddenInput) {
            hiddenInput.value =
                operationalUnitId;

            hiddenInput.disabled = false;
        }

        /*
         * Unidade somente leitura.
         */
        if (editOperationalUnit) {
            editOperationalUnit.classList.remove(
                'hidden'
            );
        }

        if (editOperationalUnitLabel) {
            editOperationalUnitLabel.textContent =
                operationalUnitLabel;
        }

        /*
         * ----------------------------------------------------------------------
         * Atividades
         * ----------------------------------------------------------------------
         */

        activityCheckboxes.forEach((checkbox) => {
            const activityId =
                String(checkbox.value);

            checkbox.checked =
                activityIds
                    .map(String)
                    .includes(activityId);
        });

        /*
         * ----------------------------------------------------------------------
         * URL de atualização
         * ----------------------------------------------------------------------
         */

        const updateUrl =
            button.dataset.updateUrl;

        if (form) {
            if (updateUrl) {
                form.action = updateUrl;
            } else {
                console.error(
                    'URL de atualização da regra específica não encontrada.'
                );
            }
        }

        /*
         * Abre o modal.
         */
        openModal();
    };

    /*
     * ==========================================================================
     * Botão "Adicionar regra específica"
     * ==========================================================================
     */

    openButtons.forEach((button) => {
        button.addEventListener('click', () => {
            resetCreateForm();

            openModal();
        });
    });

    /*
     * ==========================================================================
     * Botões "Editar"
     * ==========================================================================
     */

    editButtons.forEach((button) => {
        button.addEventListener('click', () => {
            prepareEditForm(button);
        });
    });

    /*
     * ==========================================================================
     * Fechar modal
     * ==========================================================================
     */

    closeButtons.forEach((button) => {
        button.addEventListener(
            'click',
            closeModal
        );
    });

    /*
     * ==========================================================================
     * Fechar pelo backdrop
     * ==========================================================================
     */

    if (backdrop) {
        backdrop.addEventListener(
            'click',
            closeModal
        );
    }

    /*
     * ==========================================================================
     * Fechar com ESC
     * ==========================================================================
     */

    document.addEventListener('keydown', (event) => {
        if (
            event.key === 'Escape' &&
            !modal.classList.contains('hidden')
        ) {
            closeModal();
        }
    });

    /*
     * ==========================================================================
     * REABERTURA APÓS ERRO
     * ==========================================================================
     *
     * Só executamos essa parte quando o Blade informou
     * explicitamente que o erro pertence ao formulário
     * da regra específica.
     */

    const specificModalError =
        modal.querySelector(
            '[data-specific-modal-error="true"]'
        );

    if (specificModalError) {

        /*
         * Se existe ID da regra específica,
         * estamos editando.
         */
        const oldSpecificRuleId =
            specificRuleIdInput?.value || '';

        if (oldSpecificRuleId) {

            const editButton =
                document.querySelector(
                    `[data-specific-rule-edit][data-specific-rule-id="${oldSpecificRuleId}"]`
                );

            if (editButton) {
                /*
                 * Reconstitui exatamente o estado
                 * de edição.
                 */
                prepareEditForm(editButton);
            } else {
                /*
                 * Não encontramos o botão.
                 *
                 * Mesmo assim NÃO devemos transformar
                 * o modal em criação.
                 */
                editing = true;

                const hiddenInput =
                    ensureHiddenOperationalUnitInput();

                if (hiddenInput) {
                    hiddenInput.value =
                        oldSpecificRuleId;
                }

                openModal();
            }

        } else {

            /*
             * Erro durante criação.
             *
             * Mantemos os valores restaurados pelo old()
             * presentes no HTML.
             */
            resetCreateForm();

            openModal();
        }
    }
});
