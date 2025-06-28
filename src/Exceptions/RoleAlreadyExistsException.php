<?php

namespace Braxey\Gatekeeper\Exceptions;

class RoleAlreadyExistsException extends \Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(string $roleName)
    {
        parent::__construct("Role '{$roleName}' already exists.");
    }
}
