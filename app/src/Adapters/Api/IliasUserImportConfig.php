<?php

namespace Flux\IliasUserImportApi\Adapters\Api;

class IliasUserImportConfig
{

    private function __construct(public string $excelImportDirectoryPath)
    {

    }

    public static function new(): self
    {
        return new self(getenv(IliasUserApiEnv::EXCEL_IMPORT_DIRECTORY_PATH->value));
    }

}