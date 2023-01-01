<?php

namespace FluxEco\IliasUserOrbital\Core\Ports;

use FluxEco\IliasUserOrbital\Core\Domain;

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
    public function createOrUpdateUser(Messages\CreateOrUpdateUser $message, callable $publish) : void
    {
        $iliasUser = $this->outbounds->userRepository->get($message->userId);
        $aggregate = Domain\UserAggregate::new(
            $message->userId,
        );
        if ($iliasUser === null) {
            $this->createUser($aggregate, $message->userData, $message->additionalFields);
        } else {
            $aggregate->reconstitue($iliasUser->userData, $iliasUser->additionalFields);
            $this->updateUser($aggregate, $message->userData, $message->additionalFields);
        }

        $recordedMessages = $aggregate->getAndResetRecordedMessages();

        $this->outbounds->userRepository->handleMessages($recordedMessages);
        $this->dispatchMessages($recordedMessages, $publish);
    }

    /**
     * @param callable $publish
     * @return void
     */
    public function subscribeToCoursesByRefIds(Messages\SubscribeToCoursesByRefIds $message, callable $publish) : void
    {
        $this->dispatchMessages([$message], $publish);
    }

    private function createUser(
        Domain\UserAggregate $aggregate,
        Domain\ValueObjects\UserData $userData,
        array $additionalFields
    ) : void {
        $aggregate->create($userData, $additionalFields);
    }

    private function updateUser(
        Domain\UserAggregate $aggregate,
        Domain\ValueObjects\UserData $userData,
        array $additionalFields
    ) : void {
        $aggregate->changeUserData($userData);
        $aggregate->changeAdditionalFields($additionalFields);
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