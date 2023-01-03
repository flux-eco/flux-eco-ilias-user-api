<?php

namespace FluxEco\IliasUserOrbital\Core\Ports;

use FluxEco\IliasUserOrbital\Core\Domain\UserAggregate;
use FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

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
        $aggregate = UserAggregate::new(
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

    public function subscribeUserToCourses(Messages\SubscribeUserToCourses $message, callable $publish) : void
    {
        $iliasUser = $this->outbounds->userRepository->get($message->userId);
        $aggregate = UserAggregate::new(
            $message->userId,
        );
        $aggregate->reconstitue($iliasUser->userData, $iliasUser->additionalFields);
        $aggregate->subscribeUserToCourses($message->courseRoleName, $message->courseIdType, $message->courseIds);
        $this->outbounds->userRepository->handleMessages($aggregate->getAndResetRecordedMessages());
        $this->dispatchMessages($aggregate->getAndResetRecordedMessages(), $publish);
    }

    public function unsubscribeUserFromCourses(Messages\UnsubscribeUserFromCourses $message, callable $publish) : void
    {
        $iliasUser = $this->outbounds->userRepository->get($message->userId);
        $aggregate = UserAggregate::new(
            $message->userId,
        );
        $aggregate->reconstitue($iliasUser->userData, $iliasUser->additionalFields);
        $aggregate->unsubscribeUserFromCourses($message->courseIdType, $message->courseIds);
        $this->outbounds->userRepository->handleMessages($aggregate->getAndResetRecordedMessages());
        $this->dispatchMessages($aggregate->getAndResetRecordedMessages(), $publish);
    }

    public function subscribeUserToCourse(Messages\SubscribeUserToCourse $message, callable $publish) : void
    {
        $iliasUser = $this->outbounds->userRepository->get($message->userId);
        $aggregate = UserAggregate::new(
            $message->userId,
        );
        $aggregate->reconstitue($iliasUser->userData, $iliasUser->additionalFields);
        $aggregate->subscribeUserToCourses($message->courseRoleName, $message->courseId->idType,
            [$message->courseId->id]);
        $this->outbounds->userRepository->handleMessages($aggregate->getAndResetRecordedMessages());
        $this->dispatchMessages($aggregate->getAndResetRecordedMessages(), $publish);
    }

    public function subscribeUserToCourseTree(Messages\SubscribeUserToCourseTree $message, callable $publish) : void
    {
        $iliasUser = $this->outbounds->userRepository->get($message->userId);
        $aggregate = UserAggregate::new(
            $message->userId,
        );
        $aggregate->reconstitue($iliasUser->userData, $iliasUser->additionalFields);

        $courseRefIds = $this->outbounds->courseRepository->getCourseRefIdsOfCategoryTree($message->categoryId);

        $aggregate->subscribeUserToCourses($message->courseRoleName, ValueObjects\IdType::REF_ID, $courseRefIds);
        $this->outbounds->userRepository->handleMessages($aggregate->getAndResetRecordedMessages());
        $this->dispatchMessages($aggregate->getAndResetRecordedMessages(), $publish);
    }

    public function subscribeUserToRoles(Messages\SubscribeUserToRoles $message, callable $publish) : void
    {
        $iliasUser = $this->outbounds->userRepository->get($message->userId);
        $aggregate = UserAggregate::new(
            $message->userId,
        );
        $aggregate->reconstitue($iliasUser->userData, $iliasUser->additionalFields);
        $aggregate->subscribeUserToRoles($message->roleIdType, $message->roleIds);
        $this->outbounds->userRepository->handleMessages($aggregate->getAndResetRecordedMessages());
        $this->dispatchMessages($aggregate->getAndResetRecordedMessages(), $publish);
    }

    private function createUser(
        UserAggregate $aggregate,
        ValueObjects\UserData $userData,
        array $additionalFields
    ) : void {
        $aggregate->create($userData, $additionalFields);
    }

    private function updateUser(
        UserAggregate $aggregate,
        ValueObjects\UserData $userData,
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