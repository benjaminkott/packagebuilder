<?php declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Utility;

class StringUtility
{
    public static function clean(string $string): string
    {
        $string = str_replace('.', '', $string);
        $string = str_replace(':', '', $string);
        $string = str_replace('-', ' ', $string);
        $string = str_replace('&', '', $string);
        $string = self::convertUmlauts($string);
        $string = self::toASCII($string);
        $string = trim($string);

        return $string;
    }

    public static function convertUmlauts(string $string): string
    {
        $search = ['Ä', 'Ö', 'Ü', 'ä', 'ö', 'ü', 'ß'];
        $replace = ['Ae', 'Oe', 'Ue', 'ae', 'oe', 'ue', 'ss'];

        return str_replace($search, $replace, $string);
    }

    public static function toASCII(string $string): string
    {
        return strtr(
            mb_convert_encoding($string, 'ISO-8859-1'),
            mb_convert_encoding('ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ', 'ISO-8859-1'),
            'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy'
        );
    }

    public static function stringToUpperCamelCase(string $string): string
    {
        $string = self::clean($string);
        $string = preg_replace('/[^a-z0-9]+/i', ' ', $string);
        $string = trim((string) $string);
        $string = strtolower($string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);

        return $string;
    }

    public static function camelCaseToLowerCaseUnderscored(string $string): string
    {
        preg_match_all(
            '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!',
            self::clean($string),
            $matches
        );
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper((string) $match) ? strtolower((string) $match) : lcfirst((string) $match);
        }

        return implode('_', $ret);
    }

    public static function camelCaseToLowerCaseDashed(string $string): string
    {
        preg_match_all(
            '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!',
            self::clean($string),
            $matches
        );
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper((string) $match) ? strtolower((string) $match) : lcfirst((string) $match);
        }

        return implode('-', $ret);
    }
}
