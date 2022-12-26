<?php

namespace Flux\IliasUserImportApi\Adapters\Ilias;

use Flux\IliasUserImportApi\Core\Ports;
use FluxIliasRestApiClient\Adapter\Api\IliasRestApiClient;

class IliasUserRepositoryAdapter implements Ports\Ilias\IliasUserRepository
{

    private function __construct(
        private IliasRestApiClient $iliasRestApiClient
    )
    {

    }

    public static function new(IliasRestApiClient $iliasRestApiClient): self
    {
        return new self(
            $iliasRestApiClient
        );
    }

    public function createUserGroupFieldsIfNotExists(
        array $groupNames
    ) {
       //todo
    }

    public function storeEvents() {

    }

    public function createOrUpdateUser()
    {
        // TODO: Implement createOrUpdateUser() method.
    }

    public function setIs()
    {
        // TODO: Implement setIs() method.
    }

    public function getAll()
    {
        $users = $this->iliasRestApiClient->getUsers(
            null,
            null,
            null,
            null,
            false,
            false,
            false,
            true
        );
        print_r($users);
    }
}