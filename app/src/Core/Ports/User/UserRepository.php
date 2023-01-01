<?php

namespace FluxEco\IliasUserOrbital\Core\Ports\User;
use FluxEco\IliasUserOrbital\Core\Domain;

interface UserRepository {

    public function get(Domain\ValueObjects\UserId $userId): null|UserDto;

    /**
     * @param Domain\Messages\OutgoingMessage[] $messages
     */
    public function handleMessages(array $messages): void;



}