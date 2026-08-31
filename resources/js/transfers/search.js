document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('consult-equipment-search-form');

    // Esta página não possui o formulário de consulta.
    // Portanto, o search.js não deve fazer nada.
    if (!form) {
        return;
    }

    const input = document.getElementById('consult-equipment-identifier');
    const button = document.getElementById('consult-btn-search-equipment');
    const result = document.getElementById('consult-equipment-result');

    form.addEventListener('submit', event => {
        event.preventDefault();
        searchEquipment();
    });

    async function searchEquipment() {
        const identifier = input.value.trim();

        if (!identifier) {
            input.focus();
            return;
        }

        button.disabled = true;
        button.textContent = 'Consultando...';

        try {
            const url = `/equipamentos/buscar?identifier=${encodeURIComponent(identifier)}`;

            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                alert(data.message ?? 'Equipamento não encontrado.');
                result.classList.add('hidden');
                return;
            }

            renderEquipment(data.equipment);
            result.classList.remove('hidden');

        } catch (error) {
            alert('Erro ao consultar o equipamento.');
        } finally {
            button.disabled = false;
            button.textContent = 'Buscar';
        }
    }

    function renderEquipment(equipment) {
        setText('equipment-asset-number', equipment.asset_number);
        setText('equipment-name', equipment.name);
        setText('equipment-category', equipment.category);
        setText('equipment-manufacturer', equipment.manufacturer);
        setText('equipment-model', equipment.model);
        setText('equipment-serial-number', equipment.serial_number);
        setText('equipment-branch', equipment.branch);

        renderStatus(equipment.active);
        setText('equipment-operational-status', equipment.operational_status);
        renderLastTransfer(equipment.last_transfer);
        renderTransferSituation(equipment.pending_transfer);
    }

    function renderStatus(active) {
        const status = document.getElementById('equipment-status');

        if (!status) {
            return;
        }

        if (active) {
            status.innerHTML = `
                <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-700">
                    Ativo
                </span>
            `;
            return;
        }

        status.innerHTML = `
            <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-700">
                Inativo
            </span>
        `;
    }

    function renderLastTransfer(lastTransfer) {
        if (!lastTransfer) {
            setText('last-origin-branch', 'Nenhuma');
            setText('last-destination-branch', 'Nenhuma');
            setText('last-transfer-date', 'Nenhuma transferência registrada.');
            return;
        }

        setText('last-origin-branch', lastTransfer.origin_branch);
        setText('last-destination-branch', lastTransfer.destination_branch);
        setText('last-transfer-date', lastTransfer.sent_at);
    }

    function renderTransferSituation(pendingTransfer) {
        const box = document.getElementById('transfer-status-box');
        const message = document.getElementById('transfer-status-message');

        if (!box || !message) {
            return;
        }

        if (pendingTransfer) {
            box.className = 'rounded-xl border border-amber-200 bg-amber-50 p-4';
            message.innerHTML = `
                <strong class="text-amber-800">
                    Transferência pendente
                </strong>
                <p class="mt-1 text-sm text-amber-700">
                    Este equipamento possui uma transferência aguardando recebimento.
                </p>
            `;
            return;
        }

        box.className = 'rounded-xl border border-emerald-200 bg-emerald-50 p-4';
        message.innerHTML = `
            <strong class="text-emerald-800">
                Equipamento disponível
            </strong>
            <p class="mt-1 text-sm text-emerald-700">
                O equipamento pode ser transferido normalmente.
            </p>
        `;
    }

    function setText(id, value) {
        const element = document.getElementById(id);

        if (!element) {
            return;
        }

        element.textContent = value ?? '-';
    }

});
