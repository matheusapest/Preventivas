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
                `/reparos-externos/recebimentos/buscar?identifier=${encodeURIComponent(identifier)}`,
                {
                    headers: {
                        Accept: 'application/json',
                    },
                }
            );

        const data =
            await response.json();

        if (!response.ok) {

            alert(
                data.message ??
                'Não foi possível consultar o equipamento.'
            );

            return null;
        }

        if (!data.success) {

            alert(
                data.message ??
                'Equipamento não encontrado.'
            );

            return null;
        }

        /*
         * O equipamento foi encontrado.
         *
         * A API também informa se ele está apto
         * para ser adicionado ao lote.
         */
        return data;

    } finally {

        button.disabled = false;

        button.textContent =
            'Consultar';
    }
}
