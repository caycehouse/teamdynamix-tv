# TeamDynamix TV

The _unnoficial_ TeamDynamix TV web application

## Requirements

Virtualbox, Vagrant, Composer, and PHP 7+.

## Installation

```bash
git clone https://github.com/caycehouse/teamdynamix-tv.git
composer install
php vendor/bin/homestead make

# Generate an SSH keypair if you haven't already.
ssh-keygen

cp .env.example .env
php artisan key:generate

# Edit these lines in .env with your details.
41. TD_USERNAME=
42. TD_PASSWORD=
43.
44. PAPERCUT_AUTH_TOKEN=

# Add schedule:true to Homestead.yaml if you want crons to run.
12. sites:
13.     -
14.         map: homestead.test
15.         to: /home/vagrant/code/Laravel/public
16.         schedule: true

# Add redis port if you want to test live web socketing.
21. ports:
22.     - send: 6001
23.       to: 6001

vagrant up
```

## License

This project is open-sourced software licensed under the [GPLv3 license](https://opensource.org/licenses/GPL-3.0).
