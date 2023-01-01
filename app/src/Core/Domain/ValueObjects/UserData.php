<?php

namespace FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

class UserData {
    private function __construct(
        public string $email,
        public string $firstName,
        public string $lastName,
        public string $login,
        public string $authMode,
        public string $externalId,
    ) {

    }

    /**
     * @return static
     */
    public static function new(
        string $email,
        string $firstName,
        string $lastName,
        string $login,
        string $authMode = "default",
        string $externalId = "",
    ): self
    {
        return new self(
            ...get_defined_vars()
        );
    }

    public function isEqual(UserData $userData): bool {
       return (serialize($this) === serialize($userData));
    }
}