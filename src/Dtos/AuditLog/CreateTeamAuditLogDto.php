<?php

namespace Gillyware\Gatekeeper\Dtos\AuditLog;

use Gillyware\Gatekeeper\Constants\AuditLog\Action;
use Gillyware\Gatekeeper\Models\Team;

class CreateTeamAuditLogDto extends AbstractAuditLogDto
{
    public function __construct(Team $team)
    {
        parent::__construct();

        $this->metadata = [
            'name' => $team->name,
        ];
    }

    /**
     * Get the action for the audit log.
     */
    public function getAction(): string
    {
        return Action::TEAM_CREATE;
    }
}
