<?php

namespace Gillyware\Gatekeeper\Dtos\AuditLog;

use Gillyware\Gatekeeper\Constants\AuditLog\Action;
use Gillyware\Gatekeeper\Facades\Gatekeeper;
use Gillyware\Gatekeeper\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class RevokePermissionAuditLogDto extends AbstractAuditLogDto
{
    public function __construct(Model $actionToModel, Permission $permission)
    {
        parent::__construct();

        $this->actionToModel = $actionToModel;

        $this->metadata = [
            'name' => $permission->name,
            'lifecycle_id' => Gatekeeper::getLifecycleId(),
        ];
    }

    /**
     * Get the action for the audit log.
     */
    public function getAction(): string
    {
        return Action::PERMISSION_REVOKE;
    }
}
