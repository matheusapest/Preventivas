<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ReceiveTransferRequest;
use App\Http\Requests\StoreTransferRequest;
use App\Models\Branch;
use App\Models\Transfer;
use App\Services\TransferService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransferController extends Controller
{
    /**
     * Lista as transferências.
     */
    /**
 * Painel de transferências.
 */
public function index(): View
{
    $this->authorize('viewAny', Transfer::class);

    $transfers = Transfer::with([
        'equipment',
        'originBranch',
        'destinationBranch',
        'sentBy',
        'receivedBy',
    ])
        ->latest('sent_at')
        ->paginate(15);

    $pendingTransfers = Transfer::sent()->count();

    $sentToday = Transfer::whereDate(
        'sent_at',
        today()
    )->count();

    $receivedToday = Transfer::whereDate(
        'received_at',
        today()
    )->count();

    return view(
        'transfers.index',
        compact(
            'transfers',
            'pendingTransfers',
            'sentToday',
            'receivedToday'
        )
    );
}


    /**
     * Tela de consulta de equipamentos.
     */
    public function search(): View
    {
        $this->authorize('viewAny', Transfer::class);

        return view('transfers.search');
    }

    /**
     * Formulário de envio de equipamentos.
     */
    public function create(): View
    {
        $this->authorize('create', Transfer::class);

        $branches = Branch::active()
            ->orderBy('name')
            ->get();

        return view(
            'transfers.create',
            compact('branches')
        );
    }

    /**
     * Registra uma nova transferência.
     */
    public function store(
        StoreTransferRequest $request,
        TransferService $service
    ): RedirectResponse {

        $this->authorize('create', Transfer::class);

        $service->ship(
            $request->validated()
        );

        return redirect()
            ->route('transferencias.index')
            ->with(
                'success',
                'Transferência enviada com sucesso.'
            );
    }

    /**
     * Lista as transferências pendentes de recebimento.
     */
    public function receiveIndex(): View
    {
        $this->authorize('viewAny', Transfer::class);

        $transfers = Transfer::with([
            'equipment',
            'originBranch',
            'destinationBranch',
            'sentBy',
        ])
            ->sent()
            ->orderBy('sent_at')
            ->paginate(15);

        return view(
            'transfers.receive',
            compact('transfers')
        );
    }

    /**
     * Confirma o recebimento de uma transferência.
     */
    public function receive(
        Transfer $transfer,
        ReceiveTransferRequest $request,
        TransferService $service
    ): RedirectResponse {

        $this->authorize(
            'receive',
            $transfer
        );

        $service->receive(
            $transfer,
            $request->validated()['observation'] ?? null
        );

        return redirect()
            ->route('transferencias.receive.index')
            ->with(
                'success',
                'Transferência recebida com sucesso.'
            );
    }
}
