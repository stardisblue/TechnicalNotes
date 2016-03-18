<?php
/**
 * TechnicalNotes <https://www.github.com/stardisblue/TechnicalNotes>
 * Copyright (C) 2016  TechnicalNotes Team
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace techweb\lib\core\security;


class Password
{
    const COST = 12;

    /**
     * Hashes the data
     *
     * @param string $data
     * @return string
     *
     * @see password_hash
     */
    public static function hash(string $data): string
    {
        return password_hash($data, PASSWORD_DEFAULT, ['cost' => self::COST]);
    }

    /**
     * Check if the password and the hash are the same
     *
     * @param string $password
     * @param string $hash
     * @return bool
     *
     * @see password_verify()
     */
    public static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}