document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | EXECUÇÃO DA UNIDADE
    |--------------------------------------------------------------------------
    */

    const unitSelect =
        document.getElementById('execution-unit');

    const startButton =
        document.getElementById('start-unit-execution');

    const modal =
        document.getElementById(
            'activity-selection-modal'
        );


    /*
    |--------------------------------------------------------------------------
    | MODAL DE ATIVIDADE
    |--------------------------------------------------------------------------
    */

    if (unitSelect && startButton && modal) {

        const activitySelect =
            modal.querySelector(
                '.js-activity-select'
            );

        const unitIdentifier =
            modal.querySelector(
                '.js-unit-identifier'
            );

        const noActivities =
            modal.querySelector(
                '.js-no-activities'
            );

        const confirmButton =
            modal.querySelector(
                '.js-confirm-activity'
            );

        const closeButtons =
            modal.querySelectorAll(
                '.js-close-activity-modal'
            );


        /*
        |--------------------------------------------------------------------------
        | ID DA PREVENTIVA
        |--------------------------------------------------------------------------
        */

        const preventiveId =
            unitSelect.dataset.preventiveId;


        /*
        |--------------------------------------------------------------------------
        | ABRIR MODAL DE ATIVIDADE
        |--------------------------------------------------------------------------
        */

        function openActivityModal() {

            modal.classList.remove('hidden');

            document.body.classList.add(
                'overflow-hidden'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FECHAR MODAL DE ATIVIDADE
        |--------------------------------------------------------------------------
        */

        function closeActivityModal() {

            modal.classList.add('hidden');

            document.body.classList.remove(
                'overflow-hidden'
            );

            modal.dataset.cycleUnitId = '';

            if (activitySelect) {

                activitySelect.innerHTML = `
                    <option value="">
                        Selecione uma atividade
                    </option>
                `;

                activitySelect.value = '';

                activitySelect.classList.remove(
                    'hidden'
                );
            }

            if (confirmButton) {

                confirmButton.disabled = true;
            }

            if (noActivities) {

                noActivities.classList.add(
                    'hidden'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CARREGA ATIVIDADES PENDENTES
        |--------------------------------------------------------------------------
        */

        function loadActivities(option) {

            if (!activitySelect) {
                return;
            }

            activitySelect.innerHTML = `
                <option value="">
                    Selecione uma atividade
                </option>
            `;

            activitySelect.value = '';

            activitySelect.classList.remove(
                'hidden'
            );


            if (noActivities) {

                noActivities.classList.add(
                    'hidden'
                );
            }


            if (confirmButton) {

                confirmButton.disabled = true;
            }


            if (!option) {
                return;
            }


            const activitiesJson =
                option.dataset.activities || '[]';

            let activities = [];


            try {

                activities =
                    JSON.parse(
                        activitiesJson
                    );

            } catch (error) {

                console.error(
                    'show.js: erro ao interpretar data-activities.',
                    error
                );

                activitySelect.classList.add(
                    'hidden'
                );

                if (noActivities) {

                    noActivities.classList.remove(
                        'hidden'
                    );
                }

                return;
            }


            if (!Array.isArray(activities)) {

                console.error(
                    'show.js: data-activities não é um array.',
                    activities
                );

                activitySelect.classList.add(
                    'hidden'
                );

                if (noActivities) {

                    noActivities.classList.remove(
                        'hidden'
                    );
                }

                return;
            }


            if (activities.length === 0) {

                activitySelect.classList.add(
                    'hidden'
                );

                if (noActivities) {

                    noActivities.classList.remove(
                        'hidden'
                    );
                }

                return;
            }


            activities.forEach(function (activity) {

                if (!activity || !activity.id) {
                    return;
                }

                const optionElement =
                    document.createElement(
                        'option'
                    );

                optionElement.value =
                    activity.id;

                const activityName =
                    activity.name ??
                    activity.activity_name ??
                    activity.label ??
                    'Atividade';

                optionElement.textContent =
                    activityName;

                optionElement.dataset.type =
                    activity.type || '';

                activitySelect.appendChild(
                    optionElement
                );
            });


            console.log(
                'Atividades pendentes carregadas:',
                activities
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SELEÇÃO DE ATIVIDADE
        |--------------------------------------------------------------------------
        */

        if (activitySelect) {

            activitySelect.addEventListener(
                'change',
                function () {

                    if (!confirmButton) {
                        return;
                    }

                    confirmButton.disabled =
                        !activitySelect.value;
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SELEÇÃO DA UNIDADE
        |--------------------------------------------------------------------------
        */

        unitSelect.addEventListener(
            'change',
            function () {

                const option =
                    unitSelect.options[
                    unitSelect.selectedIndex
                    ];

                startButton.disabled =
                    !option?.value;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | INICIAR EXECUÇÃO
        |--------------------------------------------------------------------------
        */

        startButton.addEventListener(
            'click',
            function () {

                const option =
                    unitSelect.options[
                    unitSelect.selectedIndex
                    ];


                if (!option || !option.value) {

                    alert(
                        'Selecione uma unidade operacional.'
                    );

                    return;
                }


                const cycleUnitId =
                    option.value;


                const identifier =
                    option.dataset.identifier || '';


                modal.dataset.cycleUnitId =
                    cycleUnitId;


                if (unitIdentifier) {

                    unitIdentifier.textContent =
                        identifier;
                }


                loadActivities(option);

                openActivityModal();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | CONFIRMAR ATIVIDADE
        |--------------------------------------------------------------------------
        */

        if (confirmButton) {

            confirmButton.addEventListener(
                'click',
                function () {

                    const cycleUnitId =
                        modal.dataset.cycleUnitId;

                    const activityId =
                        activitySelect?.value;


                    if (!cycleUnitId) {

                        alert(
                            'Não foi possível identificar a unidade selecionada.'
                        );

                        return;
                    }


                    if (!activityId) {

                        alert(
                            'Selecione uma atividade para continuar.'
                        );

                        return;
                    }


                    const url =
                        `/preventivas/${preventiveId}` +
                        `/execucao/unidade/${cycleUnitId}` +
                        `/atividade/${activityId}`;


                    console.log(
                        'Iniciando atividade:',
                        {
                            preventiveId,
                            cycleUnitId,
                            activityId,
                            url
                        }
                    );


                    window.location.assign(url);
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FECHAR MODAL DE ATIVIDADE
        |--------------------------------------------------------------------------
        */

        closeButtons.forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    closeActivityModal
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | BACKDROP DO MODAL DE ATIVIDADE
        |--------------------------------------------------------------------------
        */

        modal.addEventListener(
            'click',
            function (event) {

                if (event.target === modal) {

                    closeActivityModal();
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | ESC — MODAL DE ATIVIDADE
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape' &&
                    !modal.classList.contains('hidden')
                ) {

                    closeActivityModal();
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | ESTADO INICIAL
        |--------------------------------------------------------------------------
        */

        startButton.disabled =
            !unitSelect.value;
    }


    /*
|--------------------------------------------------------------------------
| FINALIZAÇÃO COM PENDÊNCIAS
|--------------------------------------------------------------------------
|
| O mesmo modal pode ser aberto por mais de um botão.
| Por isso utilizamos data-finalize-pending-open em vez
| de depender de um único ID.
|
*/

    const finalizeOpenButtons =
        document.querySelectorAll(
            '[data-finalize-pending-open]'
        );

    const finalizeModal =
        document.getElementById(
            'finalize-pending-modal'
        );


    /*
    |--------------------------------------------------------------------------
    | SE NÃO EXISTIR MODAL DE FINALIZAÇÃO
    |--------------------------------------------------------------------------
    */

    if (finalizeModal) {

        /*
        |--------------------------------------------------------------------------
        | ELEMENTOS
        |--------------------------------------------------------------------------
        */

        const finalizeStepConfirm =
            document.getElementById(
                'finalize-pending-step-confirm'
            );

        const finalizeStepObservation =
            document.getElementById(
                'finalize-pending-step-observation'
            );

        const confirmFinalizeButton =
            document.getElementById(
                'confirm-finalize-pending'
            );

        const backFinalizeButton =
            document.getElementById(
                'back-to-finalize-confirm'
            );

        const finalizeForm =
            document.getElementById(
                'finalize-pending-form'
            );

        const observationInput =
            document.getElementById(
                'finalize-pending-observation'
            );

        const observationError =
            document.getElementById(
                'finalize-pending-observation-error'
            );

        const closeFinalizeButtons =
            finalizeModal.querySelectorAll(
                '.js-close-finalize-modal'
            );


        /*
        |--------------------------------------------------------------------------
        | ABRIR MODAL
        |--------------------------------------------------------------------------
        */

        function openFinalizeModal() {

            finalizeStepConfirm?.classList.remove(
                'hidden'
            );

            finalizeStepObservation?.classList.add(
                'hidden'
            );

            if (observationError) {

                observationError.textContent = '';

                observationError.classList.add(
                    'hidden'
                );
            }

            finalizeModal.classList.remove(
                'hidden'
            );

            finalizeModal.setAttribute(
                'aria-hidden',
                'false'
            );

            document.body.classList.add(
                'overflow-hidden'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FECHAR MODAL
        |--------------------------------------------------------------------------
        */

        function closeFinalizeModal() {

            finalizeModal.classList.add(
                'hidden'
            );

            finalizeModal.setAttribute(
                'aria-hidden',
                'true'
            );

            document.body.classList.remove(
                'overflow-hidden'
            );

            finalizeStepConfirm?.classList.remove(
                'hidden'
            );

            finalizeStepObservation?.classList.add(
                'hidden'
            );

            if (observationError) {

                observationError.textContent = '';

                observationError.classList.add(
                    'hidden'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | BOTÕES QUE ABREM O MODAL
        |--------------------------------------------------------------------------
        */

        finalizeOpenButtons.forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        openFinalizeModal();
                    }
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | BOTÕES DE FECHAMENTO
        |--------------------------------------------------------------------------
        */

        closeFinalizeButtons.forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        closeFinalizeModal();
                    }
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | CONFIRMAR PRIMEIRA ETAPA
        |--------------------------------------------------------------------------
        */

        if (confirmFinalizeButton) {

            confirmFinalizeButton.addEventListener(
                'click',
                function () {

                    finalizeStepConfirm?.classList.add(
                        'hidden'
                    );

                    finalizeStepObservation?.classList.remove(
                        'hidden'
                    );

                    setTimeout(
                        function () {

                            observationInput?.focus();

                        },
                        100
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VOLTAR PARA CONFIRMAÇÃO
        |--------------------------------------------------------------------------
        */

        if (backFinalizeButton) {

            backFinalizeButton.addEventListener(
                'click',
                function () {

                    finalizeStepObservation?.classList.add(
                        'hidden'
                    );

                    finalizeStepConfirm?.classList.remove(
                        'hidden'
                    );

                    if (observationError) {

                        observationError.textContent = '';

                        observationError.classList.add(
                            'hidden'
                        );
                    }
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO DA JUSTIFICATIVA
        |--------------------------------------------------------------------------
        */

        if (finalizeForm) {

            finalizeForm.addEventListener(
                'submit',
                function (event) {

                    const observation =
                        observationInput?.value.trim() || '';


                    if (observation.length < 5) {

                        event.preventDefault();

                        if (observationError) {

                            observationError.textContent =
                                'Informe o motivo da finalização com pelo menos 5 caracteres.';

                            observationError.classList.remove(
                                'hidden'
                            );
                        }

                        observationInput?.focus();

                        return;
                    }


                    if (observationError) {

                        observationError.textContent = '';

                        observationError.classList.add(
                            'hidden'
                        );
                    }
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | BACKDROP
        |--------------------------------------------------------------------------
        */

        finalizeModal.addEventListener(
            'click',
            function (event) {

                if (
                    event.target.hasAttribute(
                        'data-finalize-modal-backdrop'
                    )
                ) {

                    closeFinalizeModal();
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | ESC — FINALIZAÇÃO
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape' &&
                    !finalizeModal.classList.contains(
                        'hidden'
                    )
                ) {

                    closeFinalizeModal();
                }
            }
        );

    }

});
