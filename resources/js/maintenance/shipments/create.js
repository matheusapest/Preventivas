import {
    searchEquipment,
    searchEquipmentById,
} from './api/equipment-search';

import {
    show,
    hide,
} from '../../shared/utils/dom';

import {
    renderEquipment,
} from './render/equipment';


document.addEventListener('DOMContentLoaded', async () => {

    const form =
        document.getElementById('equipment-search-form');

    if (!form) {
        return;
    }

    const result =
        document.getElementById('equipment-result');

    const shipmentForm =
        document.getElementById('shipment-form');

    /*
     * ID do equipamento preservado pelo Laravel
     * após um erro de validação.
     */
    const oldEquipmentId =
        result?.dataset.oldEquipmentId;


    /*
     * Busca normal realizada pelo usuário.
     */
    form.addEventListener('submit', async event => {

        event.preventDefault();

        hide(result);
        hide(shipmentForm);

        try {

            const equipment =
                await searchEquipment();

            if (!equipment) {
                return;
            }

            show(result);

            renderEquipment(equipment);

            /*
             * O equipamento possui uma OS em andamento.
             *
             * Nesse caso o formulário não deve ser exibido.
             */
            if (
                equipment.maintenance_order &&
                equipment.maintenance_order.status !== 'completed'
            ) {

                return;
            }

            show(shipmentForm);

        } catch (error) {

            console.error(
                'Erro ao consultar equipamento:',
                error
            );

            hide(result);
            hide(shipmentForm);

            alert(
                'Erro ao consultar equipamento.'
            );
        }

    });


    /*
     * Reconstrói o equipamento quando o Laravel
     * retornou para esta página após uma validação
     * ou erro de regra de negócio.
     */
    if (oldEquipmentId) {

        try {

            const equipment =
                await searchEquipmentById(
                    oldEquipmentId
                );

            if (!equipment) {
                console.error(
                    'Não foi possível reconstruir o equipamento:',
                    oldEquipmentId
                );

                return;
            }

            /*
             * Exibe novamente os dados do equipamento.
             */
            show(result);

            renderEquipment(equipment);

            /*
             * Como estamos voltando de um erro,
             * o formulário precisa permanecer aberto.
             */
            show(shipmentForm);

        } catch (error) {

            console.error(
                'Erro ao reconstruir equipamento:',
                error
            );

        }

    }

});
