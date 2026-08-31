export function renderTransferStatus(pendingTransfer) {

    const box =
        document.getElementById(
            'transfer-status-box'
        );

    const message =
        document.getElementById(
            'transfer-status-message'
        );

    if (!box || !message) {
        return;
    }

    if (pendingTransfer) {

        const destination =
            pendingTransfer.destination_branch ?? '-';

        const sentAt =
            pendingTransfer.sent_at ?? '-';

        box.className =
            'rounded-lg border border-yellow-200 bg-yellow-50 p-4';

        message.innerHTML = `
            <strong>⚠ Transferência pendente</strong><br>
            Este equipamento já possui uma transferência
            pendente para <strong>${destination}</strong>,
            enviada em <strong>${sentAt}</strong>.
        `;

        return;
    }

    box.className =
        'rounded-lg border border-green-200 bg-green-50 p-4';

    message.innerHTML = `
        <strong>✓ Equipamento disponível</strong><br>
        O equipamento pode ser transferido normalmente.
    `;
}
