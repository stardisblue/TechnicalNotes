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

namespace techweb\lib\core\io;

class In
{
    public static function get(string $get)
    {
        return filter_input(INPUT_GET, $get, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
    }

    public static function post(string $post)
    {
        return filter_input(INPUT_POST, $post, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
    }

    public static function cookie(string $cookie)
    {
        return filter_input(INPUT_COOKIE, $cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
    }

    public static function session(string $session)
    {
        return isset($_SESSION[$session]) ? $_SESSION[$session] : null;
    }

    public static function uSession(string $session)
    {
        return isset($_SESSION[$session]) ? unserialize($_SESSION[$session]) : null;
    }

    public static function isSetPost(string ...$post): bool
    {
        foreach ($post as $data) {
            if (isset($_POST[$data]) === false) {
                return false;
            }
        }

        return true;
    }

    public static function isSetSession(string ...$session): bool
    {
        foreach ($session as $data) {
            if (isset($_SESSION[$data]) === false) {
                return false;
            }
        }

        return true;
    }

}
