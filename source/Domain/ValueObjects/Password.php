<?php

declare(strict_types=1);

namespace Source\Domain\ValueObjects;

use Source\Domain\Exceptions\ValueObjects\PasswordInvalidDigitsNumber;

final class Password
{
    private string $password;

    public function __construct(string $password)
    {
        if (strlen($password) < CONF_PASSWD_MIN_LEN || strlen($password) > CONF_PASSWD_MAX_LEN) {
            throw new PasswordInvalidDigitsNumber(
                "A senha deve ter entre " . CONF_PASSWD_MIN_LEN . " e " . CONF_PASSWD_MAX_LEN . " dígitos."
            );
        }

        $this->password = passwd($password);
    }

    public function __toString(): string
    {
        return $this->password;
    }
}
