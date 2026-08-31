<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Configuration\Operational\OperationalUnit;

class OperationalUnitService
{
    /**
     * Cria unidades operacionais em lote.
     *
     * Cada unidade é tratada individualmente.
     * Caso uma unidade já exista, ela é ignorada e o processamento continua.
     *
     * @return array{
     *     created: int,
     *     skipped: int,
     *     identifiers: array<int, string>
     * }
     */
    public function createBatch(array $data): array
    {
        $identifiers = $this->buildIdentifiers($data);

        $created = 0;
        $skipped = [];

        foreach ($identifiers as $identifier) {
            $exists = OperationalUnit::query()
                ->where('branch_id', $data['branch_id'])
                ->where('identifier', $identifier)
                ->exists();

            if ($exists) {
                $skipped[] = $identifier;

                continue;
            }

            OperationalUnit::create([
                'identifier' => $identifier,
                'branch_id' => $data['branch_id'],
                'unit_type_id' => $data['unit_type_id'],
                'operational_profile_id' => $data['operational_profile_id'],
                'active' => $data['active'] ?? true,
            ]);

            $created++;
        }

        return [
            'created' => $created,
            'skipped' => count($skipped),
            'identifiers' => $skipped,
        ];
    }

    /**
     * Monta os identificadores que serão criados.
     */
    private function buildIdentifiers(array $data): array
    {
        $numbers = [];

        if ($data['identifier_mode'] === 'range') {
            $numbers = range(
                (int) $data['identifier_start'],
                (int) $data['identifier_end']
            );
        }

        if ($data['identifier_mode'] === 'list') {
            $numbers = $data['identifiers'] ?? [];
        }

        if ($numbers === []) {
            return [];
        }

        /*
         * Define a quantidade de casas automaticamente.
         *
         * Exemplo:
         *
         * 3 até 12  -> 03, 04, ..., 12
         * 1 até 40  -> 01, 02, ..., 40
         * 1 até 120 -> 001, 002, ..., 120
         */
        $maxNumber = max($numbers);

        $padding = max(
            2,
            strlen((string) $maxNumber)
        );

        return array_map(
            function ($number) use ($data, $padding): string {
                return trim($data['prefix'])
                    . ' '
                    . str_pad(
                        (string) $number,
                        $padding,
                        '0',
                        STR_PAD_LEFT
                    );
            },
            $numbers
        );
    }
}
