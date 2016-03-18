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

class Out
{

    /**
     * Set session value
     *
     * @param string $name
     * @param $value
     */
    public static function session(string $name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Serialize session
     *
     * @param string $name
     * @param $value
     * @see In::uSession()
     */
    public static function sSession(string $name, $value)
    {
        $_SESSION[$name] = serialize($value);
    }

    /**
     * Destroy session
     */
    public static function sessionDestroy()
    {
        session_unset();
        session_destroy();
    }

    /**
     * Unset session value
     *
     * @param string $name
     */
    public static function unsetSession(string $name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }

    /**
     * Create a cookie
     *
     * @param string $name
     * @param string $value
     * @param int $expire
     */
    public static function cookie(string $name, string $value, int $expire)
    {
        setcookie($name, $value, time() + $expire, null, null, false, true);
    }

    /**
     * unset a cookie
     *
     * @param string $name
     */
    public static function unsetCookie(string $name)
    {
        setcookie($name, null, 0, null, null, false, false);
    }

}
