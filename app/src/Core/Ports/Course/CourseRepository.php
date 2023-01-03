<?php

namespace FluxEco\IliasUserOrbital\Core\Ports\Course;
use FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

interface CourseRepository {
    public function getCourseRefIdsOfCategoryTree(ValueObjects\RepositoryObjectId $categoryId): array;
}