<?php

namespace App\Providers;

use App\Policies\ActivityPolicy;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Filament\Tables\Table;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    protected array $policies = [
        Activity::class => ActivityPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('sheet-sync', fn () => Limit::perMinute(max(1, (int) config('santri_sheet.requests_per_minute')))->by('santri-sheet-integration'));

        $this->configurePolicies();

        $this->configureDB();

        $this->configureModels();

        $this->configureFilament();
    }

    private function configurePolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    private function configureDB(): void
    {
        DB::prohibitDestructiveCommands($this->app->environment('production'));
    }

    private function configureModels(): void
    {
        Model::preventAccessingMissingAttributes();

        Model::unguard();
    }

    private function configureFilament(): void
    {
        FilamentShield::prohibitDestructiveCommands($this->app->isProduction());

        Table::configureUsing(fn (Table $table) => $table->paginationPageOptions([10, 25, 50]));
    }
}
