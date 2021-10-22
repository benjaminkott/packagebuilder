<?php declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use App\Kernel;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
