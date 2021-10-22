<?php declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

if (file_exists(dirname(__DIR__) . '/var/cache/prod/App_KernelProdContainer.preload.php')) {
    require dirname(__DIR__) . '/var/cache/prod/App_KernelProdContainer.preload.php';
}
