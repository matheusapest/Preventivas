<?php

declare(strict_types=1);

namespace App\Http\Controllers\Preventive;

use App\Http\Controllers\Controller;
use App\Http\Requests\Preventive\StorePreventiveActivityResponseRequest;
use App\Http\Requests\Preventive\StorePreventiveManualFinalizeRequest;
use App\Models\Organization\Branch;
use App\Models\Preventive\Preventive;
use App\Models\Preventive\PreventiveCycleUnit;
use App\Models\Configuration\Preventive\PreventiveType;
use App\Models\Preventive\PreventiveActivityResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Services\Preventive\Execution\FinalizePreventiveService;
use App\Services\Preventive\Execution\GetPreventiveActivityExecutionService;
use App\Services\Preventive\Execution\GetPreventiveExecutionDetailsService;
use App\Services\Preventive\Execution\GetPreventiveExecutionService;
use App\Services\Preventive\Execution\SavePreventiveActivityResponseService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventiveExecutionController extends Controller
{
    public function index(
        Request $request,
        GetPreventiveExecutionService $service
    ): View {
        $filters = $request->only([
            'search',
            'status',
        ]);

        $data = $service->execute(
            Auth::id(),
            $filters
        );

        return view(
            'preventive-execution.index',
            $data
        );
    }
    /**
     * Exibe a estrutura de execução da preventiva.
     */
    public function show(
        Preventive $preventive,
        GetPreventiveExecutionDetailsService $service
    ): View {
        $data = $service->execute($preventive);

        return view(
            'preventive-execution.show',
            $data
        );
    }

    private function resolveActivityView(
        \App\Enums\ActivityKind $activityType
    ): string {
        return match ($activityType) {
            \App\Enums\ActivityKind::PHOTO =>
            'preventive-execution.activities.photo',

            \App\Enums\ActivityKind::TEXT =>
            'preventive-execution.activities.text',

            \App\Enums\ActivityKind::NUMBER =>
            'preventive-execution.activities.number',

            \App\Enums\ActivityKind::BOOLEAN =>
            'preventive-execution.activities.boolean',

            \App\Enums\ActivityKind::OPERATIONAL_COMPOSITION =>
            'preventive-execution.activities.operational-composition',
        };
    }

    /**
     * Exibe o formulário de execução de uma atividade.
     */
    public function activity(
        Preventive $preventive,
        PreventiveCycleUnit $cycleUnit,
        int $activity,
        GetPreventiveActivityExecutionService $service
    ): View {
        $data = $service->execute(
            $preventive,
            $cycleUnit,
            $activity
        );

        $view = $this->resolveActivityView(
            $data['activityType']
        );

        return view(
            $view,
            $data
        );
    }
    public function storeActivityResponse(
        StorePreventiveActivityResponseRequest $request,
        Preventive $preventive,
        PreventiveCycleUnit $cycleUnit,
        int $activity,
        SavePreventiveActivityResponseService $service
    ): RedirectResponse {
        $validated = $request->validated();

        /*
    |--------------------------------------------------------------------------
    | Garante que a unidade pertence à preventiva
    |--------------------------------------------------------------------------
    */

        if ($cycleUnit->cycle?->preventive_id !== $preventive->id) {
            abort(404);
        }

        /*
    |--------------------------------------------------------------------------
    | Persiste a resposta
    |--------------------------------------------------------------------------
    |
    | A Controller apenas valida e encaminha os dados.
    | O Service identifica o tipo da atividade e decide
    | como a resposta deve ser persistida.
    |
    */

        $service->execute(
            cycleUnit: $cycleUnit,
            snapshotRuleActivityId: $activity,
            data: $validated,
        );

        /*
    |--------------------------------------------------------------------------
    | Retorna para a execução da preventiva
    |--------------------------------------------------------------------------
    */

        return redirect()
            ->route('preventivas.execucao.show', [
                'preventive' => $preventive->id,
            ])
            ->with(
                'success',
                'Atividade registrada com sucesso.'
            );
    }

    /**
     * Exibe a evidência fotográfica de uma resposta.
     */
    public function responsePhoto(
        Preventive $preventive,
        PreventiveActivityResponse $response
    ): BinaryFileResponse {
        /**
         * Garante que a resposta pertence à preventiva
         * que está sendo visualizada.
         */
        $responseBelongsToPreventive = $response->cycleUnit
            ?->cycle
            ?->preventive_id === $preventive->id;

        if (! $responseBelongsToPreventive) {
            abort(404);
        }

        /**
         * A resposta precisa possuir uma evidência fotográfica.
         */
        $photo = $response->photo;

        if (! $photo) {
            abort(404);
        }

        /**
         * O arquivo permanece no Storage privado.
         */
        $disk = Storage::disk('local');

        if (! $disk->exists($photo->path)) {
            abort(404);
        }

        return response()->file(
            $disk->path($photo->path),
            [
                'Content-Type' =>
                $photo->mime_type,
                'Content-Disposition' =>
                'inline; filename="evidencia.jpg"',
            ]
        );
    }

    public function finalizeWithPending(
        StorePreventiveManualFinalizeRequest $request,
        Preventive $preventive,
        FinalizePreventiveService $service
    ): RedirectResponse {
        $service->executeWithPending(
            preventive: $preventive,
            observation: $request->validated('observation'),
        );

        return redirect()
            ->route(
                'preventivas.execucao.index'
            )
            ->with(
                'success',
                'Preventiva finalizada e encaminhada para aprovação.'
            );
    }
}
