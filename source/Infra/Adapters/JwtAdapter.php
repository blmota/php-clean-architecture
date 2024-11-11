<?php

declare(strict_types=1);

namespace Source\Infra\Adapters;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Source\App\Contracts\AuthToken;

final class JwtAdapter implements AuthToken
{
    public function tokenGenerate(int $userId): ?array
    {
        if ($userId) {
            $expires_at = strtotime(current_date_tz(null, JWT_DURATION));

            $payload = array(
                "iat" => time(),
                "sub" => $userId,
                "exp" => $expires_at
            );

            return [
                "token" => JWT::encode($payload, JWT_SECRET_KEY, "HS256"),
                "expires_at" => $expires_at
            ];
        }

        return null;
    }

    public function tokenValidate(string $token): array
    {
        try {
            $jwt = JWT::decode($token, new Key(JWT_SECRET_KEY, "HS256"));
            $jwt->status = true;
            return (array) $jwt;
        } catch (\Exception $err) {
            return ["status" => false, "error" => $err->getMessage()];
        }
    }

    public function refreshTokenGenerate(int $userId): ?array
    {
        $current_date = current_date_tz(null, REFRESH_TOKEN_DURATION);
        $expires_at = strtotime($current_date);

        $payload = [
            "user_id" => $userId,
            "expires_at" => $expires_at,
            "secret" => hash("SHA512", REFRESH_TOKEN_SECRET)
        ];

        return [
            "refreshtoken" => base64_encode(json_encode($payload)),
            "expires_at" => $expires_at
        ];
    }

    public function refreshTokenValidate(string $token): bool
    {
        if (
            $token["expires_at"] > strtotime(current_date_tz()) &&
            $token["secret"] == hash("SHA512", REFRESH_TOKEN_SECRET)
        ) {
            return true;
        }

        return false;
    }
}
