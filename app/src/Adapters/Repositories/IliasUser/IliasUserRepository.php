<?php

namespace FluxEco\IliasUserOrbital\Adapters\Repositories\IliasUser;

use FluxEco\IliasUserOrbital\Core\Ports;
use FluxEco\IliasUserOrbital\Core\Domain;
use FluxIliasRestApiClient\Adapter\Api\IliasRestApiClient;
use FluxEco\IliasUserOrbital\Core\Ports\User\UserDto;
use FluxIliasBaseApi\Adapter\CourseMember\CourseMemberDiffDto;

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
                Domain\Messages\MessageName::USER_SUBSCRIBED_TO_COURSES => $this->subscribeUserToCourses($message),
                Domain\Messages\MessageName::USER_UNSUBSCRIBED_FROM_COURSES => $this->unsubscribeUserFromCourses($message),
                Domain\Messages\MessageName::USER_SUBSCRIBED_TO_ROLES => $this->subscribeUserToRoles($message),
                Domain\Messages\MessageName::ADDITIONAL_FIELD_VALUE_CHANGED, Domain\Messages\MessageName::USER_GROUP_ADDED => [],
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

    private function subscribeUserToCourses(
        Domain\Messages\UserSubscribedToCourses $message,
    ) : void {
        foreach ($message->courseIds as $id) {
            if ($message->courseIdType === Domain\ValueObjects\IdType::REF_ID && $message->userId->idType === Domain\ValueObjects\IdType::IMPORT_ID) {
                match ($message->courseRoleName) {
                    Domain\ValueObjects\CourseRoleName::MEMBER => $this->iliasRestApiClient->addCourseMemberByRefIdByUserImportId(
                        $id,
                        $message->userId->id, CourseMemberDiffDto::new(true)
                    ),
                    Domain\ValueObjects\CourseRoleName::TUTOR => $this->iliasRestApiClient->addCourseMemberByRefIdByUserImportId(
                        $id,
                        $message->userId->id, CourseMemberDiffDto::new(null, true)
                    ),
                    Domain\ValueObjects\CourseRoleName::ADMIN => $this->iliasRestApiClient->addCourseMemberByRefIdByUserImportId(
                        $id,
                        $message->userId->id, CourseMemberDiffDto::new(null, null, true)
                    ),
                };
            }

            if ($message->courseIdType === Domain\ValueObjects\IdType::IMPORT_ID && $message->userId->idType === Domain\ValueObjects\IdType::IMPORT_ID) {
                match ($message->courseRoleName) {
                    Domain\ValueObjects\CourseRoleName::MEMBER => $this->iliasRestApiClient->addCourseMemberByImportIdByUserImportId(
                        str_replace(" ", "_", $id),
                        $message->userId->id, CourseMemberDiffDto::new(true)
                    ),
                    Domain\ValueObjects\CourseRoleName::TUTOR => $this->iliasRestApiClient->addCourseMemberByImportIdByUserImportId(
                        str_replace(" ", "_", $id),
                        $message->userId->id, CourseMemberDiffDto::new(null, true)
                    ),
                    Domain\ValueObjects\CourseRoleName::ADMIN => $this->iliasRestApiClient->addCourseMemberByImportIdByUserImportId(
                        str_replace(" ", "_", $id),
                        $message->userId->id, CourseMemberDiffDto::new(null, null, true)
                    ),
                };
            }
        }
    }

    private function unsubscribeUserFromCourses(
        Domain\Messages\UserUnsubscribedFromCourses $message,
    ) : void {
        foreach ($message->courseIds as $id) {
            if ($message->courseIdType === Domain\ValueObjects\IdType::REF_ID && $message->userId->idType === Domain\ValueObjects\IdType::IMPORT_ID) {
                $this->iliasRestApiClient->removeCourseMemberByRefIdByUserImportId($id, $message->userId->id);
            }

            if ($message->courseIdType === Domain\ValueObjects\IdType::IMPORT_ID && $message->userId->idType === Domain\ValueObjects\IdType::IMPORT_ID) {
                $this->iliasRestApiClient->removeCourseMemberByImportIdByUserImportId($id, $message->userId->id);
            }

            if ($message->courseIdType === Domain\ValueObjects\IdType::OBJ_ID && $message->userId->idType === Domain\ValueObjects\IdType::OBJ_ID) {
                $this->iliasRestApiClient->removeCourseMemberByIdByUserId($id, $message->userId->id);
            }
        }
    }

    private function subscribeUserToRoles(
        Domain\Messages\UserSubscribedToRoles $message,
    ) : void {
        foreach ($message->roleIds as $id) {
            if ($message->roleIdType === Domain\ValueObjects\IdType::OBJ_ID && $message->userId->idType === Domain\ValueObjects\IdType::IMPORT_ID) {
                $this->iliasRestApiClient->addUserRoleByImportIdByRoleId(
                    $message->userId->id,
                    $id
                );
            }

            if ($message->roleIdType === Domain\ValueObjects\IdType::IMPORT_ID && $message->userId->idType === Domain\ValueObjects\IdType::IMPORT_ID) {
                $this->iliasRestApiClient->addUserRoleByImportIdByRoleImportId(
                    $message->userId->id,
                    $id
                );
            }
        }
    }

    private function changeAdditionalFields(
        Domain\Messages\AdditionalFieldsValuesChanged $message,
    ) : void {
        $this->iliasRestApiClient->updateUserByImportId(
            $message->userId->id,
            IliasUserDefinedFieldAdapter::fromDomain($message->additionalFieldsValues)->toUserDiffDto()
        );
    }

    public function get(Domain\ValueObjects\UserId $userId) : null|UserDto
    {
        if($userId->idType === Domain\ValueObjects\IdType::IMPORT_ID) {

            $iliasUser = $this->iliasRestApiClient->getUserByImportId(
                $userId->id,
            );
        }
        if($userId->idType === Domain\ValueObjects\IdType::OBJ_ID) {
            $iliasUser = $this->iliasRestApiClient->getUserById(
                (int)$userId->id,
            );
        }




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