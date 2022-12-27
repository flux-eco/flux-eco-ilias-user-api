<?php

namespace Flux\IliasUserImportApi\Adapters\Medi;

use Flux\IliasUserImportApi\Adapters\Ilias\IliasUserAdapter;
use Flux\IliasUserImportApi\Adapters\Ilias\IliasUserDefinedFieldAdapter;
use Flux\IliasUserImportApi\Core\Domain;
use Flux\IliasUserImportApi\Core\Ports;

use FluxIliasRestApiClient\Adapter\Api\IliasRestApiClient;

class MediUserEventHandler implements Ports\User\UserEventHandler
{

    private function __construct(
        private IliasRestApiClient $iliasRestApiClient
    )
    {

    }

    public static function new(IliasRestApiClient $iliasRestApiClient): self
    {
        return new self(
            $iliasRestApiClient
        );
    }

    public function handle(Domain\Events\Event $event)
    {
        match($event->getName()) {
            Domain\Events\EventName::CREATED->value => $this->onCreated($event),
            Domain\Events\EventName::USER_DATA_CHANGED->value => $this->onUserDataChanged($event),
            Domain\Events\EventName::ADDITIONAL_FIELDS_CHANGED->value => $this->onAdditionalFieldsChanged($event)
        };
    }

    private function onCreated(
        Domain\Events\Created|Domain\Events\Event $event
    ) {
        $this->iliasRestApiClient->createUser(
            IliasUserAdapter::fromDomain($event->userData)->toUserDiffDto()
        );
    }

    private function onUserDataChanged(
        Domain\Events\UserDataChanged|Domain\Events\Event $event
    ) {
        $this->iliasRestApiClient->updateUserByImportId(
            $event->userData->id,
            IliasUserAdapter::fromDomain($event->userData)->toUserDiffDto()
        );
    }

    private function onAdditionalFieldsChanged(
        Domain\Events\AdditionalFieldsChanged|Domain\Events\Event $event
    ) {
        $this->iliasRestApiClient->updateUserByImportId(
            $event->userId,
            IliasUserDefinedFieldAdapter::fromDomain($event->additionalFields)->toUserDiffDto()
        );
    }
}