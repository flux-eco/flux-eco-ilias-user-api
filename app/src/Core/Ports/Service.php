<?php

namespace Flux\IliasUserImportApi\Core\Ports;

use  Flux\IliasUserImportApi\Core\Domain;
use Flux\IliasUserImportApi\Core\Ports\User\UserEventHandler;

class Service
{

    private function __construct(
        private Outbounds $outbounds
    )
    {

    }

    public static function new(Outbounds $outbounds)
    {
        return new self($outbounds);
    }

    public function importUsers(string $contextId, callable $publish)
    {
        $eventHandler = new class([], $this->outbounds->userEventHandler) implements UserEventHandler {

            public function __construct(public array $handledEvents, public UserEventHandler $eventHandler)
            {

            }

            public function handle(Domain\Events\Event $event)
            {
                $this->handledEvents[] = $event;
                $this->eventHandler->handle($event);
            }
        };

        $usersToHandle = $this->outbounds->managementSystemUserRepository->getUserOfContext($contextId);


        $existingIliasUsers = $this->outbounds->iliasUserRepository->getAll();

        foreach ($usersToHandle as $user) {
            if (array_key_exists($user->userData->id, $existingIliasUsers) === false) {
                Domain\UserAggregate::create($eventHandler, $user->userData);
                continue;
            }

            $existingIliasUser = $existingIliasUsers[$user->userData->id];

            $userAggregate = Domain\UserAggregate::fromExisting($eventHandler, $existingIliasUser->userData, $existingIliasUser->additionalFields);
            $userAggregate->changeUserData($user->userData);

            $userAggregate->changeAdditionalFields($user->additionalFields);

        }


        $publish('userImported: ' . print_r($eventHandler->handledEvents, true));
    }


}