<?php

namespace FluxEco\IliasUserOrbital\Adapters\Repositories\IliasUser;

use FluxEco\IliasUserOrbital\Core\Ports;
use FluxEco\IliasUserOrbital\Core\Domain;
use FluxIliasRestApiClient\Adapter\Api\IliasRestApiClient;
use FluxEco\IliasUserOrbital\Core\Ports\User\UserDto;

class IliasUserRepository implements Ports\User\UserRepository
{

    private function __construct(
        private IliasRestApiClient $iliasRestApiClient,
    ) {

    }

    public static function new(IliasRestApiClient $iliasRestApiClient) : self
    {
        return new self(
            $iliasRestApiClient
        );
    }

    public function handleMessages(array $messages) : void
    {
        foreach ($messages as $message) {
            match ($message->getName()) {
                Domain\Messages\MessageName::CREATED => $this->create($message),
                Domain\Messages\MessageName::USER_DATA_CHANGED => $this->changeUserData($message),
                Domain\Messages\MessageName::ADDITIONAL_FIELDS_VALUES_CHANGED => $this->changeAdditionalFields($message),
                Domain\Messages\MessageName::ADDITIONAL_FIELD_VALUE_CHANGED, Domain\Messages\MessageName::USER_GROUP_ADDED => []
            };
        }
    }

    private function create(
        Domain\Messages\Created $message,
    ) : void {
        $this->iliasRestApiClient->createUser(
            IliasUserAdapter::fromDomain($message->userId, $message->userData)->toUserDiffDto()
        );
    }

    private function changeUserData(
        Domain\Messages\UserDataChanged $message,
    ) : void {
        $this->iliasRestApiClient->updateUserByImportId(
            $message->userId->id,
            IliasUserAdapter::fromDomain($message->userId, $message->userData)->toUserDiffDto()
        );
    }

    private function subscribeToCourses(
        Domain\ValueObjects\UserId $userId,
        array $courseRefIds
    ) : void {
        /*$courseRefIds  = [];
        foreach ($mediFacultyIds as $facultyId) {
            $refIds =  $this->courseRefIdsPerRoleRepository->getCourseRefIds(MediRole::new(MediFacultyId::from($facultyId), $mediRoleId));
            if($refIds !== null) {
                $courseRefIds = [...$refIds];
            }
        }

        foreach($courseRefIds  as $courseRefId) {
            echo $courseRefId; echo $userId;
            $this->iliasRestApiClient->addCourseMemberByRefIdByUserImportId($courseRefId,$userId,CourseMemberDiffDto::new(true));
        }*/
    }

    private function changeAdditionalFields(
        Domain\Messages\AdditionalFieldsValuesChanged $message,
    ) : void {
        return;
        $this->iliasRestApiClient->updateUserByImportId(
            $message->userId->id,
            IliasUserDefinedFieldAdapter::fromDomain($message->additionalFieldsValues)->toUserDiffDto()
        );
    }

    public function get(Domain\ValueObjects\UserId $userId) : null|UserDto
    {
        $iliasUser = $this->iliasRestApiClient->getUserByImportId(
            $userId->id,
        );
        if ($iliasUser === null) {
            return null;
        }

        $userDefinedFields = [];
        if (count($iliasUser->user_defined_fields) > 0) {
            foreach ($iliasUser->user_defined_fields as $field) {
                $userDefinedFields[] = Domain\ValueObjects\AdditionalField::new($field->name, $field->value);
            }
        }

        return Ports\User\UserDto::new(
            $userId,
            Domain\ValueObjects\UserData::new(
                $iliasUser->email,
                $iliasUser->first_name,
                $iliasUser->last_name,
                $iliasUser->login
            ),
            $userDefinedFields
        );
    }
}