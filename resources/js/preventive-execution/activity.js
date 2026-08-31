document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById(
        'activity-execution-form'
    );

    const statusSelect = document.getElementById(
        'operational_status'
    );

    const failedSection = document.getElementById(
        'failed-components-section'
    );

    const finalStatusSection = document.getElementById(
        'final-status-section'
    );

    const observation = document.getElementById(
        'observation'
    );

    const observationHelp = document.getElementById(
        'observation-help'
    );

    if (
        !form ||
        !statusSelect ||
        !failedSection ||
        !finalStatusSection ||
        !observation
    ) {
        console.error(
            'activity.js: elementos principais não encontrados.'
        );

        return;
    }

    /**
     * Atualiza a seção de componentes e
     * situação final conforme a resposta principal.
     */
    function updateExecutionState() {
        const isNotOperational =
            statusSelect.value === 'no';

        if (isNotOperational) {
            /*
            |--------------------------------------------------------------------------
            | Não operacional
            |--------------------------------------------------------------------------
            */

            failedSection.classList.remove('hidden');

            finalStatusSection.classList.remove('hidden');

            observationHelp?.classList.remove('hidden');

            observation.required = true;

        } else {
            /*
            |--------------------------------------------------------------------------
            | Operacional
            |--------------------------------------------------------------------------
            */

            failedSection.classList.add('hidden');

            finalStatusSection.classList.add('hidden');

            observationHelp?.classList.add('hidden');

            observation.required = false;

            /*
            |--------------------------------------------------------------------------
            | Limpa componentes selecionados
            |--------------------------------------------------------------------------
            */

            const checkboxes =
                failedSection.querySelectorAll(
                    '.js-failed-component'
                );

            checkboxes.forEach(function (checkbox) {
                checkbox.checked = false;
            });

            /*
            |--------------------------------------------------------------------------
            | Limpa situação final
            |--------------------------------------------------------------------------
            */

            const finalStatusInputs =
                finalStatusSection.querySelectorAll(
                    '.js-final-status'
                );

            finalStatusInputs.forEach(function (input) {
                input.checked = false;
            });
        }
    }

    /**
     * Evento da pergunta principal.
     */
    statusSelect.addEventListener(
        'change',
        updateExecutionState
    );

    /**
     * Estado inicial.
     */
    updateExecutionState();

    /**
     * Submit.
     */
    form.addEventListener('submit', function (event) {
        const status = statusSelect.value;

        console.log(
            'activity.js carregado - submit',
            status
        );

        /*
        |--------------------------------------------------------------------------
        | Validação da pergunta principal
        |--------------------------------------------------------------------------
        */

        if (!status) {
            event.preventDefault();

            alert(
                'Selecione se o conjunto está operacional.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Remove campos gerados anteriormente
        |--------------------------------------------------------------------------
        */

        form.querySelectorAll(
            '.js-generated-failed-component'
        ).forEach(function (input) {
            input.remove();
        });

        /*
        |--------------------------------------------------------------------------
        | Se SIM, não existem componentes com falha
        |--------------------------------------------------------------------------
        */

        if (status === 'yes') {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Validação da situação final
        |--------------------------------------------------------------------------
        */

        const selectedFinalStatus =
            finalStatusSection.querySelector(
                '.js-final-status:checked'
            );

        if (!selectedFinalStatus) {
            event.preventDefault();

            alert(
                'Informe se a não conformidade foi resolvida ou permanece pendente.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Validação da observação
        |--------------------------------------------------------------------------
        */

        if (!observation.value.trim()) {
            event.preventDefault();

            alert(
                'Descreva como a não conformidade foi resolvida ou por que permanece pendente.'
            );

            observation.focus();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Localiza os componentes selecionados
        |--------------------------------------------------------------------------
        */

        const checkedComponents =
            failedSection.querySelectorAll(
                '.js-failed-component:checked'
            );

        console.log(
            'Checkboxes encontrados:',
            failedSection.querySelectorAll(
                '.js-failed-component'
            ).length
        );

        console.log(
            'Checkboxes selecionados:',
            checkedComponents.length
        );

        /*
        |--------------------------------------------------------------------------
        | É obrigatório informar pelo menos
        | um componente com problema.
        |--------------------------------------------------------------------------
        */

        if (checkedComponents.length === 0) {
            event.preventDefault();

            alert(
                'Marque pelo menos um item que não está operacional ou apresenta defeito.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Monta os componentes selecionados
        |--------------------------------------------------------------------------
        */

        checkedComponents.forEach(function (
            checkbox,
            index
        ) {
            const categoryId =
                checkbox.dataset.categoryId;

            const quantityIndex =
                checkbox.dataset.quantityIndex;

            const quantity =
                checkbox.dataset.quantity;

            const component = {
                category_id:
                    categoryId
                        ? Number(categoryId)
                        : null,

                category_name:
                    checkbox.dataset.categoryName || null,

                component_name:
                    checkbox.dataset.componentName || null,

                quantity_index:
                    quantityIndex
                        ? Number(quantityIndex)
                        : 1,

                quantity:
                    quantity
                        ? Number(quantity)
                        : 1,

                status: 'failed',
            };

            const input =
                document.createElement('input');

            input.type = 'hidden';

            input.name =
                `failed_components[${index}]`;

            input.value =
                JSON.stringify(component);

            input.classList.add(
                'js-generated-failed-component'
            );

            form.appendChild(input);

            console.log(
                'Componente enviado:',
                component
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Debug final
        |--------------------------------------------------------------------------
        */

        console.log(
            'Situação final:',
            selectedFinalStatus.value
        );

        console.log(
            'Observação:',
            observation.value
        );

        console.log(
            'Campos failed_components:',
            form.querySelectorAll(
                '.js-generated-failed-component'
            )
        );
    });
});
