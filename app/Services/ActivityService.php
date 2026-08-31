<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Configuration\Preventive\PreventiveProfileRule;

class ActivityService
{
    /**
     * Inativa uma atividade.
     *
     * Uma atividade não pode ser inativada enquanto
     * estiver vinculada a uma regra de perfil de preventiva.
     *
     * @throws \RuntimeException
     */
    public function deactivate(Activity $activity): void
    {
        $hasPreventiveProfile = PreventiveProfileRule::query()
            ->whereHas('activities', function ($query) use ($activity) {
                $query->where('activity_id', $activity->id);
            })
            ->exists();

        if ($hasPreventiveProfile) {
            throw new \RuntimeException(
                'Não é possível inativar esta atividade porque ela está vinculada a um ou mais perfis de preventiva. ' .
                'Remova a atividade dos perfis vinculados antes de inativá-la.'
            );
        }

        $activity->update([
            'active' => false,
        ]);
    }
}
