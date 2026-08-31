import { setText } from '../utils/dom';

import { renderLastTransfer } from './last-transfer';
import { renderTransferStatus } from './transfer-status';

import { fillTransferForm } from '../form/transfer-form';

export function renderEquipment(equipment) {

    /*
     * Dados principais do equipamento.
     */
    setText(
        'equipment-asset-number',
        equipment.asset_number
    );

    setText(
        'equipment-name',
        equipment.name
    );

    setText(
        'equipment-category',
        equipment.category
    );

    setText(
        'equipment-manufacturer',
        equipment.manufacturer
    );

    setText(
        'equipment-model',
        equipment.model
    );

    setText(
        'equipment-serial-number',
        equipment.serial_number
    );

    setText(
        'equipment-branch',
        equipment.branch
    );

    setText('equipment-operational-status',
        equipment.operational_status
    )

    /*
     * Status do equipamento.
     */
    renderEquipmentStatus(
        equipment.active
    );

    /*
     * Última transferência.
     */
    renderLastTransfer(
        equipment.last_transfer
    );

    /*
     * Situação atual da transferência.
     */
    renderTransferStatus(
        equipment.pending_transfer
    );

    /*
     * Preenche os dados do formulário.
     */
    fillTransferForm(
        equipment
    );
}

/**
 * Renderiza o status do equipamento.
 */
function renderEquipmentStatus(active) {

    const element =
        document.getElementById(
            'equipment-status'
        );

    if (!element) {
        return;
    }

    if (active) {

        element.innerHTML = `
            <span
                class="rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700"
            >
                Ativo
            </span>
        `;

        return;
    }

    element.innerHTML = `
        <span
            class="rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-700"
        >
            Inativo
        </span>
    `;
}
