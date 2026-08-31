import { setText } from '../utils/dom';

import { disableOriginBranch } from './destination';
import { updateTransferSummary } from './summary';

export function fillTransferForm(equipment) {

    const equipmentId =
        document.getElementById('equipment_id');

    if (equipmentId) {

        equipmentId.value = equipment.id;

    }

    setText(
        'origin-branch',
        equipment.branch
    );

    setText(
        'origin-asset-number',
        equipment.asset_number
    );

    setText(
        'summary-origin-branch',
        equipment.branch
    );

    disableOriginBranch(
        equipment.branch_id
    );

    updateTransferSummary();

}
