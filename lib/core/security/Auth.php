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

class Auth
{

    private static function hash(string $data): string
    {
        return hash('sha256', $data);
    }

    public static function login(string $name)
    {
        $_SESSION[$name] = self::hash($_SERVER['REMOTE_ADDR']);
    }

    public static function check(string $name): bool
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name] === self::hash($_SERVER['REMOTE_ADDR']);
        } else {
            return false;
        }
    }

}
