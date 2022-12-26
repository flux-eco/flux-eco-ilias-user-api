<?php

namespace Flux\IliasUserImportApi\Adapters\ManagementSystemMedi;

use Flux\IliasUserImportApi\Core\Ports;
use Flux\IliasUserImportApi\Core\Domain\ValueObjects;
use Shuchkin\SimpleXLSX;


class MediExcelUserRepository implements Ports\ManagementSystem\ManagementSystemUserRepository
{
    private function __construct(
        public string $excelImportDirectoryPath
    ) {

    }

    public static function new(string $excelImportDirectoryPath) {
        return new self($excelImportDirectoryPath);
    }

    public function getUserOfContext(string $contextId)
    {
        $users = [];
        $xlsx = SimpleXLSX::parse(MediUserContext::from($contextId)->getExcelFilePath($this->excelImportDirectoryPath));
        foreach ($xlsx->rows() as $rowIndex => $row) {
            if($rowIndex === 0) {
                continue;
            }
            $users[] = ValueObjects\User::new(
                $row[MediExcelUserColumnId::ID->value],
                $row[MediExcelUserColumnId::E_MAIL->value],
                $row[MediExcelUserColumnId::FIRST_NAME->value],
                $row[MediExcelUserColumnId::LAST_NAME->value],
                ValueObjects\Account::new(
                    strtolower($row[MediExcelUserColumnId::E_MAIL->value]),
                    "person/address-nr/".$row[MediExcelUserColumnId::ID->value]
                ),
                []
            );
        }

        return $users;
    }
}