<?php

namespace Flux\IliasUserImportApi\Adapters\Ilias;

use Flux\IliasUserImportApi\Core\Domain;
use Flux\IliasUserImportApi\Core\Ports;
use Flux\IliasUserImportApi\Core\Domain\ValueObjects;

use FluxIliasRestApiClient\Adapter\Api\IliasRestApiClient;


class IliasUserQueryRepositoryAdapter implements Ports\Ilias\IliasUserQueryRepository
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

    /**
     * @return Ports\User\UserDto[]
     */
    public function getAll(): array
    {
        $iliasUsers = $this->iliasRestApiClient->getUsers(
            null,
            null,
            null,
            null,
            false,
            false,
            false,
            true
        );
        print_r($iliasUsers);

        $users = [];
        foreach($iliasUsers as $iliasUser) {
            if($iliasUser->import_id === null) {
                continue;
            }

            $userGroups = [];
            $userDefinedFields = [];
            if(count($iliasUser->user_defined_fields) > 0) {
                foreach($iliasUser->user_defined_fields as $field) {
                    $userDefinedFields[] = ValueObjects\AdditionalField::new($field->name, $field->value);
                }
            }

            $users[$iliasUser->import_id] = Ports\User\UserDto::new(
                   ValueObjects\UserData::new(
                       $iliasUser->import_id,
                       $iliasUser->email,
                       $iliasUser->first_name,
                       $iliasUser->last_name,
                       $iliasUser->login
                   ),
                   $userDefinedFields
               );
        }

        return $users;
    }
}