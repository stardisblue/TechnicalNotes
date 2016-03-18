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

class Text
{
    /**
     * Default map of accented and special characters to ASCII characters
     *
     * @var array
     */
    protected static $_transliteration = [
        'ä' => 'ae', 'æ' => 'ae', 'ǽ' => 'ae', 'ö' => 'oe', 'œ' => 'oe', 'ü' => 'ue', 'Ä' => 'Ae', 'Ü' => 'Ue', 'Ö' => 'Oe', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Å' => 'A', 'Ǻ' => 'A', 'Ā' => 'A', 'Ă' => 'A', 'Ą' => 'A', 'Ǎ' => 'A', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'å' => 'a', 'ǻ' => 'a', 'ā' => 'a', 'ă' => 'a', 'ą' => 'a', 'ǎ' => 'a', 'ª' => 'a', 'Ç' => 'C', 'Ć' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Č' => 'C', 'ç' => 'c', 'ć' => 'c', 'ĉ' => 'c', 'ċ' => 'c', 'č' => 'c', 'Ð' => 'D', 'Ď' => 'D', 'Đ' => 'D', 'ð' => 'd', 'ď' => 'd', 'đ' => 'd', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ę' => 'E', 'Ě' => 'E', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ē' => 'e', 'ĕ' => 'e', 'ė' => 'e', 'ę' => 'e', 'ě' => 'e', 'Ĝ' => 'G', 'Ğ' => 'G', 'Ġ' => 'G', 'Ģ' => 'G', 'Ґ' => 'G', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ґ' => 'g', 'Ĥ' => 'H', 'Ħ' => 'H', 'ĥ' => 'h', 'ħ' => 'h', 'І' => 'I', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ї' => 'Yi', 'Ï' => 'I', 'Ĩ' => 'I', 'Ī' => 'I', 'Ĭ' => 'I', 'Ǐ' => 'I', 'Į' => 'I', 'İ' => 'I', 'і' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ї' => 'yi', 'ĩ' => 'i', 'ī' => 'i', 'ĭ' => 'i', 'ǐ' => 'i', 'į' => 'i', 'ı' => 'i', 'Ĵ' => 'J', 'ĵ' => 'j', 'Ķ' => 'K', 'ķ' => 'k', 'Ĺ' => 'L', 'Ļ' => 'L', 'Ľ' => 'L', 'Ŀ' => 'L', 'Ł' => 'L', 'ĺ' => 'l', 'ļ' => 'l', 'ľ' => 'l', 'ŀ' => 'l', 'ł' => 'l', 'Ñ' => 'N', 'Ń' => 'N', 'Ņ' => 'N', 'Ň' => 'N', 'ñ' => 'n', 'ń' => 'n', 'ņ' => 'n', 'ň' => 'n', 'ŉ' => 'n', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ō' => 'O', 'Ŏ' => 'O', 'Ǒ' => 'O', 'Ő' => 'O', 'Ơ' => 'O', 'Ø' => 'O', 'Ǿ' => 'O', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ō' => 'o', 'ŏ' => 'o', 'ǒ' => 'o', 'ő' => 'o', 'ơ' => 'o', 'ø' => 'o', 'ǿ' => 'o', 'º' => 'o', 'Ŕ' => 'R', 'Ŗ' => 'R', 'Ř' => 'R', 'ŕ' => 'r', 'ŗ' => 'r', 'ř' => 'r', 'Ś' => 'S', 'Ŝ' => 'S', 'Ş' => 'S', 'Ș' => 'S', 'Š' => 'S', 'ẞ' => 'SS', 'ś' => 's', 'ŝ' => 's', 'ş' => 's', 'ș' => 's', 'š' => 's', 'ſ' => 's', 'Ţ' => 'T', 'Ț' => 'T', 'Ť' => 'T', 'Ŧ' => 'T', 'ţ' => 't', 'ț' => 't', 'ť' => 't', 'ŧ' => 't', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ũ' => 'U', 'Ū' => 'U', 'Ŭ' => 'U', 'Ů' => 'U', 'Ű' => 'U', 'Ų' => 'U', 'Ư' => 'U', 'Ǔ' => 'U', 'Ǖ' => 'U', 'Ǘ' => 'U', 'Ǚ' => 'U', 'Ǜ' => 'U', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ũ' => 'u', 'ū' => 'u', 'ŭ' => 'u', 'ů' => 'u', 'ű' => 'u', 'ų' => 'u', 'ư' => 'u', 'ǔ' => 'u', 'ǖ' => 'u', 'ǘ' => 'u', 'ǚ' => 'u', 'ǜ' => 'u', 'Ý' => 'Y', 'Ÿ' => 'Y', 'Ŷ' => 'Y', 'ý' => 'y', 'ÿ' => 'y', 'ŷ' => 'y', 'Ŵ' => 'W', 'ŵ' => 'w', 'Ź' => 'Z', 'Ż' => 'Z', 'Ž' => 'Z', 'ź' => 'z', 'ż' => 'z', 'ž' => 'z', 'Æ' => 'AE', 'Ǽ' => 'AE', 'ß' => 'ss', 'Ĳ' => 'IJ', 'ĳ' => 'ij', 'Œ' => 'OE', 'ƒ' => 'f', 'Þ' => 'TH', 'þ' => 'th', 'Є' => 'Ye', 'є' => 'ye',
    ];

    public static function hash($data): string
    {
        return hash('sha256', $data);
    }

    public static function clean($data): string
    {
        $trimmedData = trim($data);
        $escapedData = stripslashes($trimmedData);
        $cleanedData = htmlspecialchars($escapedData);

        return $cleanedData;
    }

    public static function isEmail($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Returns a string with all spaces converted to dashes (by default), accented
     * characters converted to non-accented characters, and non word characters removed.
     *
     * @param string $string the string you want to slug
     * @param string $replacement will replace keys in map
     * @return string
     * @link http://book.cakephp.org/3.0/en/core-libraries/inflector.html#creating-url-safe-strings
     */
    public static function slug(string $string, string $replacement = '-'): string
    {
        $quotedReplacement = preg_quote($replacement, '/');

        $map = [
            '/[^\s\p{Zs}\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',
            '/[\s\p{Zs}]+/mu' => $replacement,
            sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',
        ];

        $string = str_replace(
            array_keys(static::$_transliteration),
            array_values(static::$_transliteration),
            $string
        );

        return preg_replace(array_keys($map), array_values($map), $string);
    }
}