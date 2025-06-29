<?php

namespace Gillyware\Gatekeeper\Dtos\AuditLog;

use Gillyware\Gatekeeper\Constants\AuditLog\Action;
use Gillyware\Gatekeeper\Models\Role;

class CreateRoleAuditLogDto extends AbstractAuditLogDto
{
    public function __construct(Role $role)
    {
        parent::__construct();

        $this->metadata = [
            'name' => $role->name,
        ];
    }

    /**
     * Get the action for the audit log.
     */
    public function getAction(): string
    {
        return Action::ROLE_CREATE;
    }
}
