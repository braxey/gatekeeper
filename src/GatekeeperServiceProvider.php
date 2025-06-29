<?php

namespace Gillyware\Gatekeeper;

use Gillyware\Gatekeeper\Services\GatekeeperService;
use Gillyware\Gatekeeper\Services\PermissionService;
use Gillyware\Gatekeeper\Services\RoleService;
use Gillyware\Gatekeeper\Services\TeamService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class GatekeeperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishing();
        $this->registerBladeDirectives();
        $this->registerMiddleware();
        $this->registerCommands();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();

        $this->app->singleton('gatekeeper', function ($app) {
            return new GatekeeperService(
                $app->make(PermissionService::class),
                $app->make(RoleService::class),
                $app->make(TeamService::class),
            );
        });
    }

    /**
     * Setup the configuration for Gatekeeper.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/gatekeeper.php', 'gatekeeper'
        );
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/gatekeeper.php' => config_path('gatekeeper.php'),
        ], 'gatekeeper-config');

        $publishesMigrationsMethod = method_exists($this, 'publishesMigrations')
            ? 'publishesMigrations'
            : 'publishes';

        $this->{$publishesMigrationsMethod}([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'gatekeeper-migrations');
    }

    /**
     * Register the Blade directives for Gatekeeper.
     *
     * @return void
     */
    protected function registerBladeDirectives()
    {
        /**
         * Permissions.
         */
        Blade::if('hasPermission', function (...$args) {
            $user = count($args) === 2 ? $args[0] : auth()->user();
            $permissionName = count($args) === 2 ? $args[1] : $args[0];

            return $user && method_exists($user, 'hasPermission') && $user->hasPermission($permissionName);
        });

        Blade::if('hasAnyPermission', function (...$args) {
            $user = count($args) === 2 ? $args[0] : auth()->user();
            $permissionNames = count($args) === 2 ? $args[1] : $args[0];

            return $user && method_exists($user, 'hasAnyPermission') && $user->hasAnyPermission($permissionNames);
        });

        Blade::if('hasAllPermissions', function (...$args) {
            $user = count($args) === 2 ? $args[0] : auth()->user();
            $permissionNames = count($args) === 2 ? $args[1] : $args[0];

            return $user && method_exists($user, 'hasAllPermissions') && $user->hasAllPermissions($permissionNames);
        });

        /**
         * Roles.
         */
        Blade::if('hasRole', function (...$args) {
            $user = count($args) === 2 ? $args[0] : auth()->user();
            $roleName = count($args) === 2 ? $args[1] : $args[0];

            return $user && method_exists($user, 'hasRole') && $user->hasRole($roleName);
        });

        Blade::if('hasAnyRole', function (...$args) {
            $user = count($args) === 2 ? $args[0] : auth()->user();
            $roleNames = count($args) === 2 ? $args[1] : $args[0];

            return $user && method_exists($user, 'hasAnyRole') && $user->hasAnyRole($roleNames);
        });

        Blade::if('hasAllRoles', function (...$args) {
            $user = count($args) === 2 ? $args[0] : auth()->user();
            $roleNames = count($args) === 2 ? $args[1] : $args[0];

            return $user && method_exists($user, 'hasAllRoles') && $user->hasAllRoles($roleNames);
        });

        /**
         * Teams.
         */
        Blade::if('onTeam', function (...$args) {
            $user = count($args) === 2 ? $args[0] : auth()->user();
            $teamName = count($args) === 2 ? $args[1] : $args[0];

            return $user && method_exists($user, 'onTeam') && $user->onTeam($teamName);
        });

        Blade::if('onAnyTeam', function (...$args) {
            $user = count($args) === 2 ? $args[0] : auth()->user();
            $teamNames = count($args) === 2 ? $args[1] : $args[0];

            return $user && method_exists($user, 'onAnyTeam') && $user->onAnyTeam($teamNames);
        });

        Blade::if('onAllTeams', function (...$args) {
            $user = count($args) === 2 ? $args[0] : auth()->user();
            $teamNames = count($args) === 2 ? $args[1] : $args[0];

            return $user && method_exists($user, 'onAllTeams') && $user->onAllTeams($teamNames);
        });
    }

    /**
     * Register the middleware for Gatekeeper.
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        $router = $this->app->make('router');

        $router->aliasMiddleware('has_permission', \Gillyware\Gatekeeper\Http\Middleware\HasPermission::class);
        $router->aliasMiddleware('has_role', \Gillyware\Gatekeeper\Http\Middleware\HasRole::class);
        $router->aliasMiddleware('on_team', \Gillyware\Gatekeeper\Http\Middleware\OnTeam::class);

        $router->aliasMiddleware('has_any_permission', \Gillyware\Gatekeeper\Http\Middleware\HasAnyPermission::class);
        $router->aliasMiddleware('has_any_role', \Gillyware\Gatekeeper\Http\Middleware\HasAnyRole::class);
        $router->aliasMiddleware('on_any_team', \Gillyware\Gatekeeper\Http\Middleware\OnAnyTeam::class);

        $router->aliasMiddleware('has_all_permissions', \Gillyware\Gatekeeper\Http\Middleware\HasAllPermissions::class);
        $router->aliasMiddleware('has_all_roles', \Gillyware\Gatekeeper\Http\Middleware\HasAllRoles::class);
        $router->aliasMiddleware('on_all_teams', \Gillyware\Gatekeeper\Http\Middleware\OnAllTeams::class);
    }

    /**
     * Register the package's commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            \Gillyware\Gatekeeper\Console\CreatePermissionCommand::class,
            \Gillyware\Gatekeeper\Console\CreateRoleCommand::class,
            \Gillyware\Gatekeeper\Console\CreateTeamCommand::class,
            \Gillyware\Gatekeeper\Console\ListCommand::class,
            \Gillyware\Gatekeeper\Console\RevokeCommand::class,
            \Gillyware\Gatekeeper\Console\AssignCommand::class,
            \Gillyware\Gatekeeper\Console\ClearCacheCommand::class,
        ]);
    }
}
