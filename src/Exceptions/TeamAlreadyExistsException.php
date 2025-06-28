<?php

namespace Braxey\Gatekeeper\Exceptions;

class TeamAlreadyExistsException extends \Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(string $teamName)
    {
        parent::__construct("Team '{$teamName}' already exists.");
    }
}
