<?php

namespace Flux\IliasUserImportApi\Core\Domain\Types;


enum TaskType: string
{
    case IMPORT_USERS = "import-users";
}