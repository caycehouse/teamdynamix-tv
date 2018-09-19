# labtechs-tv
The Labtechs TV web application

## Requirements
Virtualbox, Vagrant, Composer, and PHP 7+.

## Installation
```
git clone https://github.com/caycehouse/labtechs-tv.git
composer install
php vendor/bin/homestead make

# Generate an SSH keypair if you haven't already.
ssh-keygen

cp .env.example .env
php artisan key:generate

# Add schedule:true to Homestead.yaml if you want crons to run.
12. sites:
13.     -
14.         map: homestead.test
15.         to: /home/vagrant/code/Laravel/public
16.         schedule: true

vagrant up
```

## License

This project is open-sourced software licensed under the [GPLv3 license](https://opensource.org/licenses/GPL-3.0).
