document.addEventListener('DOMContentLoaded', () => {
    const modeCheckbox = document.getElementById(
        'multiple-mode'
    );

    const singleContainer = document.getElementById(
        'single-form-container'
    );

    const multipleContainer = document.getElementById(
        'multiple-form-container'
    );

    if (
        !modeCheckbox ||
        !singleContainer ||
        !multipleContainer
    ) {
        return;
    }

    modeCheckbox.addEventListener('change', () => {
        const multipleMode = modeCheckbox.checked;

        singleContainer.classList.toggle(
            'hidden',
            multipleMode
        );

        multipleContainer.classList.toggle(
            'hidden',
            !multipleMode
        );
    });
});
