<?php

namespace Gillyware\Gatekeeper\Exceptions;

class PermissionAlreadyExistsException extends \Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(string $permissionName)
    {
        parent::__construct("Permission '{$permissionName}' already exists.");
    }
}
