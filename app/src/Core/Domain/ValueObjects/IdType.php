<?php

namespace FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

enum IdType: string
{
    case IMPORT_ID = "import-id";
    case REF_ID = "ref-id";
    case OBJ_ID = "obj-id";
}