<?php

namespace App\Providers;

use App\Models\AuditLog;
use App\Models\Backup;
use App\Models\SystemSetting;
use App\Models\User;
use App\Policies\AuditLogPolicy;
use App\Policies\BackupPolicy;
use App\Policies\RolePolicy;
use App\Policies\SystemSettingPolicy;
use App\Policies\UserPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    // Paksa HTTPS jika aplikasi berjalan di server (Production)
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
        
        Paginator::useBootstrap();

        // Register policies
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(AuditLog::class, AuditLogPolicy::class);
        Gate::policy(SystemSetting::class, SystemSettingPolicy::class);
        Gate::policy(Backup::class, BackupPolicy::class);

        // Gate for session management (no model)
        Gate::define('viewAny-session', fn (User $user) => $user->can('sessions.view'));
        Gate::define('view-session', fn (User $user) => $user->can('sessions.view'));
        Gate::define('delete-session', fn (User $user) => $user->can('sessions.delete'));

        // Super admin bypass - grant all permissions
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
        });
    }
}
