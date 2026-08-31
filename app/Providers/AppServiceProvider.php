<?php

namespace App\Providers;

use App\Models\Preventive\Preventive;
use App\Models\Configuration\Preventive\PreventiveProfile;
use App\Models\Configuration\Preventive\PreventiveType;
use App\Models\Equipment\Transfer;
use App\Policies\Preventive\PreventivePolicy;
use App\Policies\Configuration\Preventive\PreventiveProfilePolicy;
use App\Policies\Configuration\Preventive\PreventiveTypePolicy;
use App\Policies\Transfer\TransferPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Preventive::class, PreventivePolicy::class);
        Gate::policy(PreventiveProfile::class, PreventiveProfilePolicy::class);
        Gate::policy(PreventiveType::class, PreventiveTypePolicy::class);
        Gate::policy(Transfer::class, TransferPolicy::class);
    }
}
