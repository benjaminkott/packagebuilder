<?php
namespace BK2K\Sitepackage\GeneratorBundle\Utility;

/*
 *  The MIT License (MIT)
 *
 *  Copyright (c) 2016 Benjamin Kott, http://www.bk2k.info
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in
 *  all copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

class StringUtility
{
    /**
     * @param type $string
     */
    function clean($string) {
        $string = str_replace(".", "", $string);
        $string = str_replace(":", "", $string);
        $string = str_replace("-", " ", $string);
        $string = str_replace("&", "", $string);
        $string = self::toASCII($string);
        $string = trim($string);
        return $string;
    }

    /**
     * @param string $string
     * @return string
     */
    function toASCII($string)
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
        $string = str_replace(" ", "", $string);
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
