<?php

namespace FluxEco\IliasUserOrbital\Adapters\Repositories\IliasCourse;

use FluxEco\IliasUserOrbital\Core\Ports;
use FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;
use FluxIliasRestApiClient\Adapter\Api\IliasRestApiClient;
use FluxIliasRestApiClient\Libs\FluxIliasBaseApi\Adapter\Object\DefaultObjectType;

class IliasCourseRepository implements Ports\Course\CourseRepository
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

    public function getCourseRefIdsOfCategoryTree(ValueObjects\RepositoryObjectId $categoryId) : array
    {
        return match ($categoryId->idType) {
            ValueObjects\IdType::IMPORT_ID => array_map(function ($course) { return $course->ref_id; }, $this->iliasRestApiClient->getChildrenByImportId(str_replace(" ", "_", $categoryId->id), DefaultObjectType::COURSE))
        };
    }
}