import { setText } from '../../../shared/utils/dom';

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

    setText(
        'equipment-operational-status',
        equipment.operational_status
    );

    /*
     * Status do equipamento.
     */
    renderEquipmentStatus(
        equipment.active
    );

    /*
     * Situação da ordem de serviço.
     */
    renderMaintenanceOrderStatus(
        equipment.maintenance_order
    );

    /*
     * ID interno utilizado pelo formulário de envio.
     */
    setEquipmentId(
        equipment.id
    );
}


/**
 * Preenche o ID do equipamento no formulário de envio.
 */
function setEquipmentId(equipmentId) {

    const input =
        document.getElementById(
            'shipment-equipment-id'
        );

    if (!input) {
        return;
    }

    input.value = equipmentId;
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
                class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700"
            >
                Ativo
            </span>
        `;

        return;
    }

    element.innerHTML = `
        <span
            class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-700"
        >
            Inativo
        </span>
    `;
}


/**
 * Renderiza a situação da ordem de serviço.
 *
 * Quando não existe uma OS aberta, informa que o equipamento
 * está disponível para iniciar um novo reparo.
 *
 * Quando existe uma OS aberta, informa o motivo do bloqueio.
 */
function renderMaintenanceOrderStatus(maintenanceOrder) {

    const box =
        document.getElementById(
            'maintenance-equipment-status-box'
        );

    const message =
        document.getElementById(
            'maintenance-equipment-status-message'
        );

    if (!box || !message) {
        return;
    }

    /*
     * Nenhuma OS aberta.
     */
    if (!maintenanceOrder) {

        box.className =
            'rounded-lg border border-green-200 bg-green-50/80 p-3.5 transition-all sm:p-4';

        message.className =
            'text-xs leading-relaxed text-green-800 sm:text-sm';

        message.textContent =
            'O equipamento está disponível para iniciar um novo reparo externo.';

        return;
    }

    /*
     * OS em reparo.
     */
    if (
        maintenanceOrder.status === 'in_repair'
    ) {

        box.className =
            'rounded-lg border border-amber-200 bg-amber-50/80 p-3.5 transition-all sm:p-4';

        message.className =
            'text-xs leading-relaxed text-amber-800 sm:text-sm';

        message.textContent =
            `Este equipamento já possui uma ordem de serviço em andamento (${maintenanceOrder.status_label}). Um novo envio inicial não pode ser realizado.`;

        return;
    }

    /*
     * OS aguardando validação.
     */
    if (
        maintenanceOrder.status === 'in_validation'
    ) {

        box.className =
            'rounded-lg border border-blue-200 bg-blue-50/80 p-3.5 transition-all sm:p-4';

        message.className =
            'text-xs leading-relaxed text-blue-800 sm:text-sm';

        message.textContent =
            'Este equipamento possui uma ordem de serviço aguardando validação. Um novo envio inicial não pode ser realizado.';

        return;
    }

    /*
     * Fallback para qualquer estado não previsto.
     */
    box.className =
        'rounded-lg border border-slate-200 bg-slate-50 p-3.5 transition-all sm:p-4';

    message.className =
        'text-xs leading-relaxed text-slate-700 sm:text-sm';

    message.textContent =
        'O equipamento possui uma ordem de serviço associada.';
}
