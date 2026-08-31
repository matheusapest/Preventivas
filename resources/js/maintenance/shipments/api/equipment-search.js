export async function searchEquipment() {

    const input =
        document.getElementById('equipment_identifier');

    const button =
        document.getElementById('btn-search-equipment');

    if (!input) {
        throw new Error(
            'Campo de identificação do equipamento não encontrado.'
        );
    }

    if (!button) {
        throw new Error(
            'Botão de consulta do equipamento não encontrado.'
        );
    }

    const identifier =
        input.value.trim();

    if (!identifier) {

        input.focus();

        return null;
    }

    button.disabled = true;

    button.textContent =
        'Consultando...';

    try {

        const response =
            await fetch(
                `/equipamentos/buscar?identifier=${encodeURIComponent(identifier)}`,
                {
                    headers: {
                        Accept: 'application/json',
                    },
                }
            );

        const data =
            await response.json();

        if (
            !response.ok ||
            !data.success
        ) {

            alert(
                data.message ??
                'Equipamento não encontrado.'
            );

            return null;
        }

        return data.equipment;

    } finally {

        button.disabled = false;

        button.textContent =
            'Consultar';
    }
}


/**
 * Reconstrói a consulta pelo ID interno do equipamento.
 *
 * Utilizado quando o Laravel retorna para a tela
 * após uma falha de validação ou regra de negócio.
 */
export async function searchEquipmentById(
    equipmentId
) {

    if (!equipmentId) {
        return null;
    }

    const response =
        await fetch(
            `/equipamentos/buscar?id=${encodeURIComponent(equipmentId)}`,
            {
                headers: {
                    Accept: 'application/json',
                },
            }
        );

    const data =
        await response.json();

    if (
        !response.ok ||
        !data.success
    ) {
        return null;
    }

    return data.equipment;
}
