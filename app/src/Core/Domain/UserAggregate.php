<?php

namespace FluxEco\IliasUserOrbital\Core\Domain;

use FluxEco\IliasUserOrbital\Core\Domain\Messages;

class UserAggregate
{
    private array $recordedMessages = [];
    public ?ValueObjects\UserData $userData = null;
    /**
     * @var ValueObjects\AdditionalField[]
     */
    public array $additionalFields = [];
    public array $courseSubscriptionIds = [];

    private function __construct(
        public readonly ValueObjects\UserId $userId
    ) {

    }

    public static function new(
        ValueObjects\UserId $userId
    ) : self {
        return new self(...get_defined_vars());
    }

    private function appendAdditionalField(ValueObjects\AdditionalField $additionalField)
    {
        $this->additionalFields[$additionalField->fieldName] = $additionalField;
    }

    private function recordMessage(Messages\OutgoingMessage $message) : void
    {
        $this->recordedMessages[] = $message;
    }

    public function getAndResetRecordedMessages() : array
    {
        $messages = $this->recordedMessages;
        $this->recordedMessages = [];
        return $messages;
    }

    /**
     * @param ValueObjects\AdditionalField[] $additionalFields
     */
    public function reconstitue(
        ValueObjects\UserData $userData,
        array $additionalFields
    ) : void {
        $this->applyCreated(Messages\Created::new($this->userId, $userData));
        if (count($additionalFields) > 0) {
            $this->applyAdditionalFieldsChanged(Messages\AdditionalFieldsValuesChanged::new($this->userId,
                $additionalFields));
        }
    }

    public function create(
        ValueObjects\UserData $userData,
        array $additionalFields
    ) : void {
        $message = Messages\Created::new($this->userId, $userData);
        $this->applyCreated($message);
        $this->recordMessage($message);

        if (count($additionalFields) > 0) {
            $this->changeAdditionalFields($additionalFields);
        }
    }

    private function applyCreated(Messages\Created $message) : void
    {
        $this->userData = $message->userData;
    }

    public function changeUserData(ValueObjects\UserData $userData) : void
    {
        if ($this->userData->isEqual($userData) === false) {
            $message = Messages\UserDataChanged::new($this->userId, $userData);
            $this->applyUserDataChanged($message);
            $this->recordMessage($message);
        }
    }

    private function applyUserDataChanged(Messages\UserDataChanged $message) : void
    {
        $this->userData = $message->userData;
    }

    /**
     * @param ValueObjects\AdditionalField[] $additionalFields
     * @return void
     */
    public function changeAdditionalFields(array $additionalFields) : void
    {
        $additionalFieldsHasChanged = false;
        if (count($additionalFields) > 0) {
            foreach ($additionalFields as $newAdditionalField) {
                if (array_key_exists($newAdditionalField->fieldName, $this->additionalFields) === false) {
                    $additionalFieldsHasChanged = true;
                    $this->recordMessage(Messages\AdditionalFieldValueChanged::new(
                        $this->userId,
                        $newAdditionalField->fieldName,
                        $newAdditionalField->fieldValue,
                        null
                    ));
                    continue;
                }

                $currentField = $this->additionalFields[$newAdditionalField->fieldName];
                if ($currentField->isEqual($newAdditionalField) === false) {
                    $additionalFieldsHasChanged = true;
                    $this->recordMessage(Messages\AdditionalFieldValueChanged::new(
                        $this->userId,
                        $newAdditionalField->fieldName,
                        $newAdditionalField->fieldValue,
                        $currentField->fieldValue
                    ));
                }
            }
        }

        if ($additionalFieldsHasChanged === true) {
            $message = Messages\AdditionalFieldsValuesChanged::new($this->userId, $additionalFields);
            $this->applyAdditionalFieldsChanged($message);
            $this->recordMessage($message);
        }
    }

    private function applyAdditionalFieldsChanged(
        Messages\AdditionalFieldsValuesChanged $message
    ): void {
        $this->additionalFields = [];
        if (count($message->additionalFieldsValues) > 0) {
            foreach ($message->additionalFieldsValues as $additionalField) {
                $this->appendAdditionalField($additionalField);
            }
        }
    }

    public function subscribeToCourses(ValueObjects\CourseRoleName $courseRoleName, ValueObjects\IdType $courseIdType, array $courseIds): void
    {
        $message = Messages\UserSubscribedToCourses::new($this->userId,$courseRoleName, $courseIdType, $courseIds);
        $this->recordMessage($message);
    }

    public function unsubscribeFromCourses(ValueObjects\IdType $courseIdType, array $courseIds): void
    {
        $message = Messages\UserUnsubscribedFromCourses::new($this->userId, $courseIdType, $courseIds);
        $this->recordMessage($message);
    }

    public function subscribeToRoles(ValueObjects\IdType $roleIdType, array $roleIdIds): void
    {
        $message = Messages\UserSubscribedToRoles::new($this->userId, $roleIdType, $roleIdIds);
        $this->recordMessage($message);
    }
}