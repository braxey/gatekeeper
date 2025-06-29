<?php

namespace Gillyware\Gatekeeper\Traits;

use Gillyware\Gatekeeper\Exceptions\MissingActingAsModelException;
use Gillyware\Gatekeeper\Exceptions\ModelDoesNotInteractWithPermissionsException;
use Gillyware\Gatekeeper\Exceptions\ModelDoesNotInteractWithRolesException;
use Gillyware\Gatekeeper\Exceptions\ModelDoesNotInteractWithTeamsException;
use Gillyware\Gatekeeper\Exceptions\RolesFeatureDisabledException;
use Gillyware\Gatekeeper\Exceptions\TeamsFeatureDisabledException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

trait EnforcesForGatekeeper
{
    use ActsForGatekeeper;

    /**
     * Enforce that the model interacts with permissions.
     */
    protected function enforcePermissionInteraction(Model $model): void
    {
        if (! in_array(InteractsWithPermissions::class, class_uses_recursive($model))) {
            throw new ModelDoesNotInteractWithPermissionsException($model);
        }
    }

    /**
     * Enforce that the model interacts with roles.
     */
    protected function enforceRoleInteraction(Model $model): void
    {
        if (! in_array(InteractsWithRoles::class, class_uses_recursive($model))) {
            throw new ModelDoesNotInteractWithRolesException($model);
        }
    }

    /**
     * Enforce that the model interacts with teams.
     */
    protected function enforceTeamInteraction(Model $model): void
    {
        if (! in_array(InteractsWithTeams::class, class_uses_recursive($model))) {
            throw new ModelDoesNotInteractWithTeamsException($model);
        }
    }

    /**
     * Enforce that the acting model is set when the audit feature is enabled.
     */
    protected function enforceAuditFeature(): void
    {
        if (Config::get('gatekeeper.features.audit') && (! isset($this->actingAs) || ! $this->actingAs instanceof Model)) {
            throw new MissingActingAsModelException;
        }
    }

    /**
     * Enforce that the roles feature is enabled.
     */
    protected function enforceRolesFeature(): void
    {
        if (! Config::get('gatekeeper.features.roles')) {
            throw new RolesFeatureDisabledException;
        }
    }

    /**
     * Enforce that the teams feature is enabled.
     */
    protected function enforceTeamsFeature(): void
    {
        if (! Config::get('gatekeeper.features.teams')) {
            throw new TeamsFeatureDisabledException;
        }
    }
}
