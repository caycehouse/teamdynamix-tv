<?php
namespace Deployer;

require 'recipe/laravel.php';

// Load .env
with(new \Dotenv\Dotenv(__DIR__))->load();

// Project name
set('application', 'Labtechs TV');

// Project repository
set('repository', 'https://github.com/caycehouse/labtechs-tv-laravel.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts

host(getenv('DEP_HOST'))
    ->user(getenv('DEP_USER'))
    ->set('deploy_path', '~/td.sumojoe.com');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

