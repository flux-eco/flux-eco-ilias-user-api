<?php

namespace FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

enum CourseRoleName: string
{
    case MEMBER = "member";
    case TUTOR = "tutor";
    case ADMIN = "admin";
}