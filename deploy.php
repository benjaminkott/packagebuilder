<?php

/*
 * This file is part of the bk2k/packagebuilder.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Deployer;

require 'recipe/common.php';

// Configuration
set('application', getenv('DEPLOYER_APPLICATION'));
set('repository', 'https://github.com/benjaminkott/packagebuilder.git');
set('ssh_type', 'native');
set('keep_releases', '3');
set('allow_anonymous_stats', false);

// Shared files/dirs between deploys
add('shared_files', ['.env']);
add('shared_dirs', []);

// Writable dirs by web server
set('allow_anonymous_stats', false);

// Hosts
host(getenv('DEPLOYER_HOST'))
    ->user(getenv('DEPLOYER_USER'))
    ->port('22')
    ->set('bin/php', getenv('DEPLOYER_PHP'))
    ->set('bin/composer', getenv('DEPLOYER_COMPOSER'))
    ->set('deploy_path', '~/html/application/{{application}}');

// Tasks
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
])->desc('Deploy your project');
after('deploy', 'success');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
