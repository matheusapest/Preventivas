document.addEventListener('DOMContentLoaded', () => {
    const validationStatus =
        document.getElementById('validation_status');

    const closeWithoutResendContainer =
        document.getElementById(
            'close-without-resend-container'
        );

    const closeWithoutResend =
        document.getElementById(
            'close_without_resend'
        );

    if (
        !validationStatus ||
        !closeWithoutResendContainer ||
        !closeWithoutResend
    ) {
        return;
    }

    function updateCloseWithoutResend() {

        const isRejected =
            validationStatus.value === 'rejected';

        if (isRejected) {
            closeWithoutResendContainer.classList.remove('hidden');
            return;
        }

        closeWithoutResendContainer.classList.add('hidden');

        closeWithoutResend.checked = false;
    }

    validationStatus.addEventListener(
        'change',
        updateCloseWithoutResend
    );

    updateCloseWithoutResend();
});
