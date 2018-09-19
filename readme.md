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

vagrant up
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
