import { setText } from '../utils/dom';

export function updateTransferSummary() {

    const select =
        document.getElementById(
            'destination_branch_id'
        );

    if (!select) {
        return;
    }

    select.addEventListener(
        'change',
        () => {

            const option =
                select.options[
                    select.selectedIndex
                ];

            if (!option) {
                return;
            }

            setText(
                'summary-destination-branch',
                option.text
            );

        }
    );

}
