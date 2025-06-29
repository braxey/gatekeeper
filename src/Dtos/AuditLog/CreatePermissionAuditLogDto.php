<?php

namespace Gillyware\Gatekeeper\Dtos\AuditLog;

use Gillyware\Gatekeeper\Constants\AuditLog\Action;
use Gillyware\Gatekeeper\Models\Permission;

class CreatePermissionAuditLogDto extends AbstractAuditLogDto
{
    public function __construct(Permission $permission)
    {
        parent::__construct();

        $this->metadata = [
            'name' => $permission->name,
        ];
    }

    /**
     * Get the action for the audit log.
     */
    public function getAction(): string
    {
        return Action::PERMISSION_CREATE;
    }
}
