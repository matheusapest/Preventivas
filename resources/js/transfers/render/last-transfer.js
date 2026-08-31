import { setText } from '../utils/dom';

export function renderLastTransfer(lastTransfer) {

    if (!lastTransfer) {

        setText(
            'last-origin-branch',
            'Nenhuma'
        );

        setText(
            'last-destination-branch',
            'Nenhuma'
        );

        setText(
            'last-transfer-date',
            'Nenhuma transferência registrada.'
        );

        return;

    }

    setText(
        'last-origin-branch',
        lastTransfer.origin_branch
    );

    setText(
        'last-destination-branch',
        lastTransfer.destination_branch
    );

    setText(
        'last-transfer-date',
        lastTransfer.sent_at
    );

}
