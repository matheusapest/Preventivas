export function disableOriginBranch(originBranchId) {

    const select =
        document.getElementById(
            'destination_branch_id'
        );

    if (!select) {
        return;
    }

    Array.from(select.options).forEach(option => {

        option.disabled = false;

        if (
            Number(option.value) === Number(originBranchId)
        ) {

            option.disabled = true;

        }

    });

    select.value = '';

}
