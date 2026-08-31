/**
 * Obtém um elemento.
 */
export function element(id) {

    return document.getElementById(id);

}

/**
 * Preenche texto.
 */
export function setText(id, value) {

    const el = element(id);

    if (!el) {
        return;
    }

    el.textContent = value ?? '-';

}

/**
 * Exibe um elemento.
 */
export function show(element) {

    if (element) {
        element.classList.remove('hidden');
    }

}

/**
 * Oculta um elemento.
 */
export function hide(element) {

    if (element) {
        element.classList.add('hidden');
    }

}
