<?php declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Utility;

/**
 * FileUtility.
 */
class FileUtility
{
    /**
     * @param string $dir
     * @param bool   $filesOnly
     *
     * @return array
     */
    public static function listDirectory($dir, $filesOnly = false)
    {
        $result = [];
        $root = scandir($dir);
        foreach ($root as $value) {
            if ('.' === $value || '..' === $value) {
                continue;
            }
            if (is_file("$dir$value")) {
                $result[] = "$dir$value";
                continue;
            }
            if (is_dir("$dir$value") && !($filesOnly)) {
                $result[] = "$dir$value/";
            }
            foreach (self::listDirectory("$dir$value/") as $subValue) {
                $result[] = $subValue;
            }
        }

        return $result;
    }
}
