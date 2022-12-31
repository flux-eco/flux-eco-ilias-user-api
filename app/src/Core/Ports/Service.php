<?php

namespace FluxEco\IliasUserApi\Core\Ports;

use FluxEco\IliasUserApi\Core\Domain;

class Service
{

    private function __construct(
        private Outbounds $outbounds,
    ) {

    }

    public static function new(Outbounds $outbounds)
    {
        return new self(
            $outbounds
        );
    }

    /**
     * @param callable $publish
     * @return void
     */
    public function createOrUpdateUser(User\UserDto $userDto, callable $publish) : void
    {
        $iliasUser = $this->outbounds->userRepository->get($userDto->userId);
        $aggregate = Domain\UserAggregate::new(
            $userDto->userId,
        );
        if ($iliasUser === null) {
            $this->createUser($aggregate, $userDto);
        } else {
            $aggregate->reconstitue($iliasUser->userData, $iliasUser->additionalFields);
            $this->updateUser($aggregate, $userDto);
        }

        $recordedMessages = $aggregate->getAndResetRecordedMessages();

        $this->outbounds->userRepository->handleMessages($recordedMessages);
        $this->dispatchMessages($recordedMessages,  $publish);
    }

    private function createUser(Domain\UserAggregate $aggregate, User\UserDto $userDto) : void
    {
        $aggregate->create($userDto->userData, $userDto->additionalFields);
    }

    private function updateUser(Domain\UserAggregate $aggregate, User\UserDto $userDataToUpdate) : void
    {
        $aggregate->changeUserData($userDataToUpdate->userData);
        $aggregate->changeAdditionalFields($userDataToUpdate->additionalFields);
    }

    private function dispatchMessages(array $recordedMessages, callable $publish)
    {
        $handledMessages = [];
        if (count($recordedMessages) > 0) {
            foreach ($recordedMessages as $message) {
                $this->outbounds->userMessageDispatcher->dispatch($message);
                $handledMessages[] = $message;
            }
        }
        print_r($handledMessages);
        $publish(json_encode($handledMessages, JSON_PRETTY_PRINT));
    }
}