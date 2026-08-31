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

// utils/dom.js
export function show(element) {
    if (element) {
        element.classList.remove('hidden');
    }
}

export function hide(element) {
    if (element) {
        element.classList.add('hidden');
    }
}
