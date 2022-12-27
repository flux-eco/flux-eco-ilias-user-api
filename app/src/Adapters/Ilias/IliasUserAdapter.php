<?php

namespace Flux\IliasUserImportApi\Adapters\Ilias;
use Flux\IliasUserImportApi\Core\Domain\ValueObjects;
use FluxIliasRestApiClient\Libs\FluxIliasBaseApi\Adapter\User\UserDiffDto;
use stdClass;

class IliasUserAdapter
{

    private function __construct(
        public object $userObject
    )
    {

    }

    public static function fromDomain(
        ValueObjects\UserData $user,
    )
    {
        $userObject = new stdClass();
        $userObject->import_id = $user->id;
        $userObject->login = $user->login;
        $userObject->active = true;
        $userObject->first_name = $user->firstName;
        $userObject->last_name = $user->lastName;
        $userObject->email = $user->email;
        $userObject->language = 'german';
        return new self($userObject);
    }

    public function toUserDiffDto(): UserDiffDto
    {
        return UserDiffDto::newFromObject(
            $this->userObject
        );
    }
}