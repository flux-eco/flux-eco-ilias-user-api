<?php

namespace Flux\IliasUserImportApi\Adapters\Ilias;

use  Flux\IliasUserImportApi\Core\Ports;

class IliasUserRepositoryAdapter implements Ports\Ilias\IliasUserRepository
{

    private function __construct()
    {

    }

    public static function new(): self
    {
        return new self();
    }

    public function createOrUpdateUser()
    {
        // TODO: Implement createOrUpdateUser() method.
    }

    public function setIs()
    {
        // TODO: Implement setIs() method.
    }
}