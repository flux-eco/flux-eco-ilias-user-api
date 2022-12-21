<?php

namespace Flux\IliasUserImportApi\Adapters\ManagementSystemMedi;

enum MediUserContext: string
{
    case BMA = "bma";
    case RS = "rs";
    case OT = "ot";
    case AT = "at";
    case AMB = "amb";
    case DH = "dh";

    public function getExcelFilePath(string $excelImportDirectory): string
    {
        return $excelImportDirectory . "/Benutzerexport-SSO-" . strtoupper($this->value) . ".xlsx";
    }
}