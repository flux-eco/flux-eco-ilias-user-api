<?php

namespace Flux\IliasUserImportApi\Adapters\Medi;

use Flux\IliasUserImportApi\Core\Ports;
use Flux\IliasUserImportApi\Core\Domain\ValueObjects;
use Shuchkin\SimpleXLSX;


class MediExcelUserQueryRepository implements Ports\ManagementSystem\ManagementSystemUserQueryRepository
{
    private function __construct(
        public string $excelImportDirectoryPath
    )
    {

    }

    public static function new(string $excelImportDirectoryPath)
    {
        return new self($excelImportDirectoryPath);
    }

    /**
     * @param string $contextId
     * @return Ports\User\UserDto[]
     */
    public function getUserOfContext(string $contextId): array
    {
        $users = [];
        $xlsx = SimpleXLSX::parse(MediUserContext::from($contextId)->getExcelFilePath($this->excelImportDirectoryPath));
        foreach ($xlsx->rows() as $rowIndex => $row) {
            $additionalFields = [];
            if ($rowIndex === 0) {
                continue;
            }

            $importId = "medi-address_nr-" . $row[MediExcelUserColumnId::ID->value];

            $additionalFields = [
                    ValueObjects\AdditionalField::new(MediKeywords::BG_FACHTEAM->value, $row[MediExcelUserColumnId::BG_FACHTEAM->value]),
                    ValueObjects\AdditionalField::new(MediKeywords::BG_ADMIN->value, $row[MediExcelUserColumnId::BG_ADMIN->value]),
                    ValueObjects\AdditionalField::new(MediKeywords::BG_DOZIERENDE->value, $row[MediExcelUserColumnId::BG_DOZIERENDE->value]),
                    ValueObjects\AdditionalField::new(MediKeywords::BG_BERUFSBILDE->value, $row[MediExcelUserColumnId::BG_BERUFSBILDENDE->value]),
                    ValueObjects\AdditionalField::new(MediKeywords::BG_STUDIERENDE->value, $row[MediExcelUserColumnId::BG_STUDIERENDE->value])
                ];


            $users[] = Ports\User\UserDto::new(
                ValueObjects\UserData::new(
                    $importId,
                    $row[MediExcelUserColumnId::E_MAIL->value],
                    $row[MediExcelUserColumnId::FIRST_NAME->value],
                    $row[MediExcelUserColumnId::LAST_NAME->value],
                    $row[MediExcelUserColumnId::E_MAIL->value],
                ),
                $additionalFields
            );
        }

        return $users;
    }
}