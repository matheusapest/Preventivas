import { searchEquipment } from './api/equipment-search';

import {
    show,
    hide,
} from './utils/dom';

import {
    renderEquipment,
} from './render/equipment';

document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('equipment-search-form');

    // Se o formulário não existir na página atual, encerra a execução silenciosamente.
    if (!form) {
        return;
    }

    const result = document.getElementById('equipment-result');
    const transferForm = document.getElementById('transfer-form');

    form.addEventListener('submit', async event => {
        event.preventDefault();

        hide(result);
        hide(transferForm);

        try {
            const equipment = await searchEquipment();

            if (!equipment) {
                return;
            }

            // Exibe o card do equipamento
            show(result);

            // Renderiza os dados do equipamento
            renderEquipment(equipment);

            // Se possuir transferência pendente, bloqueia o formulário de envio
            if (equipment.pending_transfer) {
                hide(transferForm);
                return;
            }

            // Equipamento liberado para transferência
            show(transferForm);

        } catch (error) {
            hide(result);
            hide(transferForm);

            alert('Erro ao consultar equipamento.');
        }
    });

});
