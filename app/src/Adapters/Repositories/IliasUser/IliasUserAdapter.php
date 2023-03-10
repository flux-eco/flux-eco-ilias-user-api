<?php

namespace FluxEco\IliasUserOrbital\Adapters\Repositories\IliasUser;
use FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;
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
        ValueObjects\UserId $userId,
        ValueObjects\UserData $userData,
    )
    {
        $userObject = new stdClass();
        $userObject->import_id = $userId->id;
        $userObject->login = $userData->login;
        $userObject->active = true;
        $userObject->first_name = $userData->firstName;
        $userObject->last_name = $userData->lastName;
        $userObject->email = $userData->email;
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