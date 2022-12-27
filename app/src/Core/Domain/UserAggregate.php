<?php

namespace Flux\IliasUserImportApi\Core\Domain;

use Flux\IliasUserImportApi\Core\Domain\Events\AdditionalFieldsChanged;
use Flux\IliasUserImportApi\Core\Ports;

class UserAggregate
{
    /**
     * @var ValueObjects\AdditionalField[]
     */
    public array $additionalFields = [];


    private function __construct(
        private Ports\User\UserEventHandler $eventHandler,
        public ValueObjects\UserData         $userData
    )
    {

    }

    /**
     * @param Ports\User\UserEventHandler $eventHandler
     * @param ValueObjects\UserData $userData
     * @param ValueObjects\AdditionalField[] $additionalFields
     * @return static
     */
    public static function fromExisting(
        Ports\User\UserEventHandler $eventHandler,
        ValueObjects\UserData        $userData,
        array                        $additionalFields
    ): self
    {
        $obj = new self($eventHandler, $userData);
        foreach($additionalFields as $additionalField) {
            $obj->appendAdditionalField($additionalField);
        }
        return $obj;
    }

    private function appendAdditionalField(ValueObjects\AdditionalField $additionalField) {
        $this->additionalFields[$additionalField->fieldName] = $additionalField;
    }

    public static function create(
        Ports\User\UserEventHandler $eventHandler,
        ValueObjects\UserData        $user
    ): self
    {
        $obj = new self($eventHandler, $user, []);
        $obj->applyCreated(Events\Created::new($user));
        return $obj;
    }

    private function applyCreated(Events\Created $created): void
    {
        $this->eventHandler->handle($created);
    }

    public function changeUserData(ValueObjects\UserData $userData): void
    {
        if ($this->userData->isEqual($userData) === false) {
            $this->applyUserDataChanged(Events\UserDataChanged::new($userData));
        }
    }

    private function applyUserDataChanged(Events\UserDataChanged $userDataChanged): void
    {
        $this->userData = $userDataChanged->userData;
        $this->eventHandler->handle($userDataChanged);
    }

    /**
     * @param ValueObjects\AdditionalField[] $additionalFields
     * @return void
     */
    public function changeAdditionalFields(array $additionalFields): void
    {
       $additionalFieldsHasChanged = false;

       $fieldNamesToHandle = [];
       if(count($additionalFields) > 0) {
           foreach($additionalFields as $additionalField) {
               $fieldNamesToHandle[] = $additionalField->fieldName;
               if(array_key_exists($additionalField->fieldName, $this->additionalFields) === false) {
                   $additionalFieldsHasChanged = true;
                   continue;
               }

               $currentField = $this->additionalFields[$additionalField->fieldName];
               if($currentField->isEqual($additionalField) === false) {
                   $additionalFieldsHasChanged = true;
                   continue;
               }
           }

           foreach($this->additionalFields as $currentAdditionalFields) {
               if(in_array($currentAdditionalFields->fieldName, $fieldNamesToHandle) === false) {
                   $additionalFieldsHasChanged = true;
               }
           }
       }

        if(count($additionalFields) === 0 && count($this->additionalFields) > 0) {
            $additionalFieldsHasChanged = true;
        }

        if($additionalFieldsHasChanged === true) {
            $this->applyAdditionalFieldsChanged(AdditionalFieldsChanged::new($this->userData->id, $additionalFields));
        }

    }

    private function applyAdditionalFieldsChanged(
        AdditionalFieldsChanged $additionalFieldsChanged
    ) {
        $this->additionalFields = [];

        if(count($additionalFieldsChanged->additionalFields) > 0) {
            foreach($additionalFieldsChanged->additionalFields as $additionalField) {
                $this->appendAdditionalField($additionalField);
            }
        }

        $this->eventHandler->handle($additionalFieldsChanged);
    }

}