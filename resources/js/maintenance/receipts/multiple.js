import {
    searchEquipment,
} from './api/equipment-search.js';


/*
|--------------------------------------------------------------------------
| Estado da tela
|--------------------------------------------------------------------------
*/

const selectedShipments = new Map();


/*
|--------------------------------------------------------------------------
| Elementos da página
|--------------------------------------------------------------------------
*/

const input =
    document.getElementById('equipment_identifier');

const searchButton =
    document.getElementById('btn-search-equipment');

const searchMessage =
    document.getElementById('equipment-search-message');

const selectedList =
    document.getElementById('selected-equipment-list');

const emptySelection =
    document.getElementById('empty-selection');

const selectedCount =
    document.getElementById('selected-equipment-count');

const shipmentInputs =
    document.getElementById('shipment-inputs');

const submitButton =
    document.getElementById('btn-submit-receipt');

const form =
    document.getElementById('multiple-receipt-form');


/*
|--------------------------------------------------------------------------
| Verificação da página
|--------------------------------------------------------------------------
*/

if (
    input &&
    searchButton &&
    selectedList &&
    emptySelection &&
    selectedCount &&
    shipmentInputs &&
    submitButton &&
    form
) {

    /*
    |--------------------------------------------------------------------------
    | Mensagens
    |--------------------------------------------------------------------------
    */

    function showMessage(
        message,
        type = 'error'
    ) {

        searchMessage.textContent =
            message;

        searchMessage.classList.remove(
            'hidden',
            'border-red-200',
            'bg-red-50',
            'text-red-700',
            'border-emerald-200',
            'bg-emerald-50',
            'text-emerald-700'
        );

        if (type === 'success') {

            searchMessage.classList.add(
                'border-emerald-200',
                'bg-emerald-50',
                'text-emerald-700'
            );

        } else {

            searchMessage.classList.add(
                'border-red-200',
                'bg-red-50',
                'text-red-700'
            );
        }
    }


    function hideMessage() {

        searchMessage.classList.add(
            'hidden'
        );

        searchMessage.textContent = '';
    }


    /*
    |--------------------------------------------------------------------------
    | Atualiza contador
    |--------------------------------------------------------------------------
    */

    function updateCount() {

        const count =
            selectedShipments.size;

        selectedCount.textContent =
            count === 1
                ? '1 selecionado'
                : `${count} selecionados`;

        submitButton.disabled =
            count === 0;

        if (count === 0) {

            emptySelection.classList.remove(
                'hidden'
            );

        } else {

            emptySelection.classList.add(
                'hidden'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Atualiza inputs hidden
    |--------------------------------------------------------------------------
    */

    function updateShipmentInputs() {

        shipmentInputs.innerHTML = '';

        selectedShipments.forEach(
            (data, shipmentId) => {

                const input =
                    document.createElement('input');

                input.type =
                    'hidden';

                input.name =
                    'shipment_ids[]';

                input.value =
                    shipmentId;

                shipmentInputs.appendChild(
                    input
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Cria card do equipamento
    |--------------------------------------------------------------------------
    */

    function createEquipmentCard(
        equipment,
        receiving
    ) {

        const shipmentId =
            receiving.shipment_id;


        const card =
            document.createElement('div');

        card.dataset.shipmentId =
            shipmentId;

        card.className =
            'rounded-lg border border-slate-200 bg-white p-4 shadow-sm';


        /*
        |--------------------------------------------------------------------------
        | Cabeçalho
        |--------------------------------------------------------------------------
        */

        const header =
            document.createElement('div');

        header.className =
            'flex items-start justify-between gap-4';


        const information =
            document.createElement('div');

        information.className =
            'min-w-0';


        const name =
            document.createElement('p');

        name.className =
            'text-sm font-semibold text-slate-800';

        name.textContent =
            equipment.name ??
            'Equipamento não informado';


        const model =
            document.createElement('p');

        model.className =
            'mt-0.5 text-xs text-slate-500';

        model.textContent =
            equipment.model ??
            'Modelo não informado';


        information.appendChild(
            name
        );

        information.appendChild(
            model
        );


        /*
        |--------------------------------------------------------------------------
        | Botão remover
        |--------------------------------------------------------------------------
        */

        const removeButton =
            document.createElement('button');

        removeButton.type =
            'button';

        removeButton.className =
            'shrink-0 text-xs font-medium text-red-600 transition hover:text-red-700';

        removeButton.textContent =
            'Remover';

        removeButton.addEventListener(
            'click',
            () => {

                removeEquipment(
                    shipmentId
                );
            }
        );


        header.appendChild(
            information
        );

        header.appendChild(
            removeButton
        );


        /*
        |--------------------------------------------------------------------------
        | Informações do equipamento
        |--------------------------------------------------------------------------
        */

        const details =
            document.createElement('div');

        details.className =
            'mt-3 grid grid-cols-1 gap-2 sm:grid-cols-3';


        details.appendChild(
            createDetail(
                'Patrimônio',
                equipment.asset_number
            )
        );

        details.appendChild(
            createDetail(
                'Número de série',
                equipment.serial_number
            )
        );

        details.appendChild(
            createDetail(
                'Filial de envio',
                receiving.shipment.origin_branch
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        const status =
            document.createElement('div');

        status.className =
            'mt-3 flex flex-wrap items-center gap-2';


        const shipmentBadge =
            document.createElement('span');

        shipmentBadge.className =
            'inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700';

        shipmentBadge.textContent =
            `Envio #${shipmentId}`;


        const statusBadge =
            document.createElement('span');

        statusBadge.className =
            'inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700';

        statusBadge.textContent =
            equipment.operational_status ??
            'Em reparo externo';


        status.appendChild(
            shipmentBadge
        );

        status.appendChild(
            statusBadge
        );


        /*
        |--------------------------------------------------------------------------
        | Montagem do card
        |--------------------------------------------------------------------------
        */

        card.appendChild(
            header
        );

        card.appendChild(
            details
        );

        card.appendChild(
            status
        );


        return card;
    }


    /*
    |--------------------------------------------------------------------------
    | Campo de detalhe
    |--------------------------------------------------------------------------
    */

    function createDetail(
        label,
        value
    ) {

        const container =
            document.createElement('div');


        const title =
            document.createElement('span');

        title.className =
            'block text-xs font-medium text-slate-500';

        title.textContent =
            label;


        const content =
            document.createElement('span');

        content.className =
            'mt-0.5 block text-sm text-slate-700';

        content.textContent =
            value ??
            'Não informado';


        container.appendChild(
            title
        );

        container.appendChild(
            content
        );


        return container;
    }


    /*
    |--------------------------------------------------------------------------
    | Adiciona equipamento ao lote
    |--------------------------------------------------------------------------
    */

    function addEquipment(
        data
    ) {

        const equipment =
            data.equipment;

        const receiving =
            data.receiving;


        /*
        |--------------------------------------------------------------------------
        | Verifica se a API autorizou o recebimento
        |--------------------------------------------------------------------------
        */

        if (
            !receiving ||
            !receiving.can_receive
        ) {

            showMessage(
                receiving?.message ??
                'Este equipamento não está disponível para recebimento.'
            );

            return;
        }


        const shipmentId =
            receiving.shipment_id;


        /*
        |--------------------------------------------------------------------------
        | Proteção contra duplicidade
        |--------------------------------------------------------------------------
        */

        if (
            selectedShipments.has(
                shipmentId
            )
        ) {

            showMessage(
                'Este equipamento já foi adicionado ao lote.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Guarda o equipamento no lote
        |--------------------------------------------------------------------------
        */

        selectedShipments.set(
            shipmentId,
            {
                equipment,
                receiving,
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Cria o card visual
        |--------------------------------------------------------------------------
        */

        const card =
            createEquipmentCard(
                equipment,
                receiving
            );

        selectedList.appendChild(
            card
        );


        /*
        |--------------------------------------------------------------------------
        | Atualiza formulário e contador
        |--------------------------------------------------------------------------
        */

        updateShipmentInputs();

        updateCount();


        /*
        |--------------------------------------------------------------------------
        | Feedback
        |--------------------------------------------------------------------------
        */

        showMessage(
            'Equipamento adicionado ao lote.',
            'success'
        );


        /*
        |--------------------------------------------------------------------------
        | Limpa o campo para próxima consulta
        |--------------------------------------------------------------------------
        */

        input.value = '';

        input.focus();
    }


    /*
    |--------------------------------------------------------------------------
    | Remove equipamento do lote
    |--------------------------------------------------------------------------
    */

    function removeEquipment(
        shipmentId
    ) {

        selectedShipments.delete(
            shipmentId
        );


        const card =
            selectedList.querySelector(
                `[data-shipment-id="${shipmentId}"]`
            );


        if (card) {

            card.remove();
        }


        updateShipmentInputs();

        updateCount();

        hideMessage();

        input.focus();
    }


    /*
    |--------------------------------------------------------------------------
    | Realiza a consulta
    |--------------------------------------------------------------------------
    */

    async function handleSearch() {

        hideMessage();


        if (
            !input.value.trim()
        ) {

            input.focus();

            return;
        }


        try {

            const data =
                await searchEquipment();


            if (!data) {
                return;
            }


            addEquipment(
                data
            );

        } catch (error) {

            console.error(
                'Erro ao consultar equipamento:',
                error
            );

            showMessage(
                'Não foi possível consultar o equipamento. Tente novamente.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Evento do botão de consulta
    |--------------------------------------------------------------------------
    */

    searchButton.addEventListener(
        'click',
        handleSearch
    );


    /*
    |--------------------------------------------------------------------------
    | Enter no campo de pesquisa
    |--------------------------------------------------------------------------
    */

    input.addEventListener(
        'keydown',
        (event) => {

            if (
                event.key === 'Enter'
            ) {

                event.preventDefault();

                handleSearch();
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Envio do formulário
    |--------------------------------------------------------------------------
    */

    form.addEventListener(
        'submit',
        (event) => {

            if (
                selectedShipments.size === 0
            ) {

                event.preventDefault();

                showMessage(
                    'Adicione pelo menos um equipamento ao lote.'
                );

                return;
            }


            submitButton.disabled =
                true;

            submitButton.textContent =
                'Recebendo equipamentos...';
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Estado inicial
    |--------------------------------------------------------------------------
    */

    updateCount();
}
