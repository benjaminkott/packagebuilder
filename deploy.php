<?php declare(strict_types=1);

/*
 * This file is part of the package bk2k/packagebuilder.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Deployer;

require 'recipe/symfony.php';
require 'contrib/rsync.php';
require 'contrib/cachetool.php';

// Project name
set('application', 'sitepackagebuilder');
set('writable_mode', 'chmod');
set('rsync_src', __DIR__);
set('cachetool_args', '--web --web-path=./public --web-url=https://{{hostname}}');

set('rsync', [
    'exclude' => [
        '.ddev',
        '.git',
        '.github',
        '.gitattributes',
        '.gitignore',
        'node_modules',
        '.editorconfig',
        '.env.local',
        'deploy.php',
    ],
    'exclude-file' => false,
    'include'      => [],
    'include-file' => false,
    'filter'       => [],
    'filter-file'  => false,
    'filter-perdir'=> false,
    'flags'        => 'rzcE',
    'options'      => ['links', 'delete'],
    'timeout'      => 60,
]);

// Hosts
host(getenv('SSH_HOST'))
    ->setRemoteUser(getenv('SSH_USER'))
    ->set('deploy_path', '~/html/application/{{application}}')
    ->set('rsync_dest', '{{release_path}}')
    ->set('keep_releases', '2');

task('project:cache', function () {
    run('{{bin/console}} cache:clear {{console_options}}');
    run('{{bin/console}} cache:warmup {{console_options}}');
});

task('deploy', [
    'deploy:lock',
    'deploy:release',
    'rsync',
    'deploy:shared',
    'deploy:symlink',
    'cachetool:clear:opcache',
    'cachetool:clear:apcu',
    'project:cache',
    'deploy:unlock',
    'deploy:cleanup',
]);
