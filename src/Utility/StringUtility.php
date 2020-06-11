<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Utility;

/**
 * StringUtility
 */
class StringUtility
{
    /**
     * @param string $string
     * @return string
     */
    public static function clean($string)
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

    /**
     * @param string $string
     * @return string
     */
    public static function convertUmlauts($string)
    {
        $search = ['Ä', 'Ö', 'Ü', 'ä', 'ö', 'ü', 'ß'];
        $replace = ['Ae', 'Oe', 'Ue', 'ae', 'oe', 'ue', 'ss'];
        return str_replace($search, $replace, $string);
    }

    /**
     * @param string $string
     * @return string
     */
    public static function toASCII($string)
    {
        return strtr(
            utf8_decode($string),
            utf8_decode('ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
            'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy'
        );
    }

    /**
     * @param string $string
     * @return string
     */
    public static function stringToUpperCamelCase($string)
    {
        $string = self::clean($string);
        $string = preg_replace('/[^a-z0-9]+/i', ' ', $string);
        $string = preg_replace('/[A-Z]+/', ' $0', $string);
        $string = trim($string);
        $string = strtolower($string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);
        return $string;
    }

    /**
     * @param string $string
     * @return string
     */
    public static function camelCaseToLowerCaseUnderscored($string)
    {
        preg_match_all(
            '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!',
            self::clean($string),
            $matches
        );
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    /**
     * @param string $string
     * @return string
     */
    public static function camelCaseToLowerCaseDashed($string)
    {
        preg_match_all(
            '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!',
            self::clean($string),
            $matches
        );
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('-', $ret);
    }
}
