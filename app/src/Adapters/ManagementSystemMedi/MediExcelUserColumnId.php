<?php

namespace Flux\IliasUserImportApi\Adapters\ManagementSystemMedi;

enum MediExcelUserColumnId: int
{
    case ID = 0;
    case LAST_NAME = 1;
    case FIRST_NAME = 2;
    case E_MAIL = 3;
    case BG_FACHTEAM = 4;
    case BG_ADMIN = 5;
    case BG_DOZIERENDE = 6;
    case BG_BERUFSBILDENDE = 7;
    case BG_STUDIERENDE = 8;
    case SCHOOL_CLASS = 9;
    case BEGIN = 10;
    case END = 11;
}