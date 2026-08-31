<?php

namespace App\Services;

use App\Enums\TransferStatus;
use App\Models\Equipment\Equipment;
use App\Models\Transfer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TransferService
{
    /**
     * Realiza o envio de um equipamento para outra filial.
     */
    public function ship(array $data): Transfer
    {
        return DB::transaction(function () use ($data) {

            $equipment = Equipment::findOrFail(
                $data['equipment_id']
            );

            /*
             * Não permite transferir equipamentos inativos.
             */
            if (! $equipment->active) {
                throw new InvalidArgumentException(
                    'O equipamento está inativo.'
                );
            }

            /*
             * Não permite transferir para a mesma filial.
             */
            if (
                $equipment->branch_id ===
                $data['destination_branch_id']
            ) {
                throw new InvalidArgumentException(
                    'O equipamento já pertence à filial informada.'
                );
            }

            /*
             * Não permite criar outra transferência enquanto
             * existir uma pendente para este equipamento.
             */
            $hasPendingTransfer = Transfer::where(
                'equipment_id',
                $equipment->id
            )
                ->sent()
                ->exists();

            if ($hasPendingTransfer) {
                throw new InvalidArgumentException(
                    'Este equipamento já possui uma transferência pendente.'
                );
            }

            /*
             * Registra o envio.
             */
            return Transfer::create([

                'equipment_id'          => $equipment->id,
                'origin_branch_id'      => $equipment->branch_id,
                'destination_branch_id' => $data['destination_branch_id'],

                'sent_by' => Auth::id(),
                'sent_at' => now(),

                'status' => TransferStatus::SENT,

                'observation' => $data['observation'] ?? null,

            ]);
        });
    }

    /**
     * Confirma o recebimento da transferência.
     */
    public function receive(
        Transfer $transfer,
        ?string $observation = null
    ): void {

        DB::transaction(function () use (
            $transfer,
            $observation
        ) {

            /*
             * A transferência já foi concluída.
             */
            if (
                $transfer->status ===
                TransferStatus::RECEIVED
            ) {
                throw new InvalidArgumentException(
                    'Esta transferência já foi recebida.'
                );
            }

            /*
             * Atualiza a transferência.
             */
            $transfer->update([

                'received_by' => Auth::id(),

                'received_at' => now(),

                'status' => TransferStatus::RECEIVED,

                'observation' => $observation,

            ]);

            /*
             * Atualiza a filial atual do equipamento.
             */
            $transfer->equipment->update([

                'branch_id' => $transfer->destination_branch_id,

            ]);
        });
    }
}
