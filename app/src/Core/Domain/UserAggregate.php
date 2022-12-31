<?php

namespace FluxEco\IliasUserApi\Core\Domain;

use FluxEco\IliasUserApi\Core\Domain\Messages\AdditionalFieldsValuesChanged;

class UserAggregate
{
    private array $recordedMessages = [];
    public ?ValueObjects\UserData $userData = null;
    /**
     * @var ValueObjects\AdditionalField[]
     */
    public array $additionalFields = [];

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

    private function recordMessage(Messages\Message $message): void  {
        $this->recordedMessages[] = $message;
    }

    public function getAndResetRecordedMessages(): array {
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
        if(count($additionalFields) > 0) {
            $this->applyAdditionalFieldsChanged(Messages\AdditionalFieldsValuesChanged::new($this->userId, $additionalFields));
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

        $fieldNamesToHandle = [];
        if (count($additionalFields) > 0) {
            foreach ($additionalFields as $newAdditionalFieldValue) {
                if (array_key_exists($newAdditionalFieldValue->fieldName,$this->additionalFields) === false || $this->additionalFields[$newAdditionalFieldValue->fieldName]->fieldValue === "" && $newAdditionalFieldValue->fieldValue !== "") {
                    $additionalFieldsHasChanged = true;
                    $this->recordMessage(Messages\AdditionalFieldValueAdded::new(
                        $this->userId,
                        $newAdditionalFieldValue
                    ));
                    continue;
                }

                if (array_key_exists($newAdditionalFieldValue->fieldName,$this->additionalFields) === true && $this->additionalFields[$newAdditionalFieldValue->fieldName]->fieldValue !== "" && $newAdditionalFieldValue->fieldValue === "") {
                    $additionalFieldsHasChanged = true;
                    $this->recordMessage(Messages\AdditionalFieldValueRemoved::new(
                        $this->userId,
                        $newAdditionalFieldValue
                    ));
                    continue;
                }

                $currentAdditionalFieldValue = $this->additionalFields[$newAdditionalFieldValue->fieldName];
                if ($currentAdditionalFieldValue->isEqual($newAdditionalFieldValue) === false) {
                    $additionalFieldsHasChanged = true;
                    $this->recordMessage(Messages\AdditionalFieldValueChanged::new(
                        $this->userId,
                        $newAdditionalFieldValue,
                        $currentAdditionalFieldValue
                    ));
                }


            }
        }

        /*if(count($additionalFields) === 0 && count($this->additionalFields) > 0) {
            $additionalFieldsHasChanged = true;
        }*/

        if ($additionalFieldsHasChanged === true) {
            $message = AdditionalFieldsValuesChanged::new($this->userId, $additionalFields);
            $this->applyAdditionalFieldsChanged($message);
            $this->recordMessage($message);
        }

    }

    private function applyAdditionalFieldsChanged(
        AdditionalFieldsValuesChanged $message
    ) {
        $this->additionalFields = [];
        if (count($message->additionalFieldsValues) > 0) {
            foreach ($message->additionalFieldsValues as $additionalField) {
                $this->appendAdditionalField($additionalField);
            }
        }
    }
}