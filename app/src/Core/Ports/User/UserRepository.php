<?php

namespace FluxEco\IliasUserApi\Core\Ports\User;
use FluxEco\IliasUserApi\Core\Domain;

interface UserRepository {

    public function get(Domain\ValueObjects\UserId $userId): null|UserDto;

    /**
     * @param Domain\Messages\Message[] $messages
     */
    public function handleMessages(array $messages): void;



}