<?php

namespace techweb\lib\core\security;


class Password
{
    const COST = 12;

    public static function hash(string $data): string
    {
        return password_hash($data, PASSWORD_BCRYPT, ['cost' => self::COST]);
    }

    public static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}