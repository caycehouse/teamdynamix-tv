# TeamDynamix TV

The _unnoficial_ TeamDynamix TV web application

## Requirements

Virtualbox, Vagrant, Composer, Redis, MySQL and PHP 7+.

## Installation

```bash
git clone https://github.com/caycehouse/teamdynamix-tv.git
composer install

# Generate an SSH keypair if you haven't already.
ssh-keygen

cp .env.example .env
php artisan key:generate

# Edit these lines in .env with your details.
41. TD_USERNAME=
42. TD_PASSWORD=
43.
44. PAPERCUT_AUTH_TOKEN=
```

## License

This project is open-sourced software licensed under the [GPLv3 license](https://opensource.org/licenses/GPL-3.0).
