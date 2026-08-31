export async function searchEquipment() {

    const input =
        document.getElementById('consult-equipment-identifier');

    const button =
        document.getElementById('btn-search-equipment');

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

        button.textContent = 'Buscar';

    }

}
