<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace BK2K\Sitepackage\GeneratorBundle\Utility;

/**
 * StringUtility
 */
class StringUtility
{
    /**
     * @param type $string
     * @return string
     */
    public function clean($string)
    {
        $string = str_replace('.', '', $string);
        $string = str_replace(':', '', $string);
        $string = str_replace('-', ' ', $string);
        $string = str_replace('&', '', $string);
        $string = self::toASCII($string);
        $string = trim($string);
        return $string;
    }

    /**
     * @param string $string
     * @return string
     */
    public function toASCII($string)
    {
        return strtr(utf8_decode($string),
            utf8_decode('ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
            'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');
    }

    /**
     * @param string $string
     * @return string
     */
    public static function stringToUpperCamelCase($string)
    {
        $string = self::clean($string);
        $string = preg_replace('/[^a-z0-9]+/i', ' ', $string);
        $string = trim($string);
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
        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', self::clean($string)));
    }

    /**
     * @param string $string
     * @return string
     */
    public static function camelCaseToLowerCaseDashed($string)
    {
        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '-\\1', self::clean($string)));
    }
}
