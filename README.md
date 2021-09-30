# Matchme API

API do projeto Matchme me

## Installation

Use the package manager [composer](https://getcomposer.org/) to install all dependencies.

```bash
composer install
```

## Usage

```php

#start server
php artisan serve

#create database
php artisan migrate

INSERT INTO `group` (`id`, `name`, `visible`, `created_at`, `updated_at`)
VALUES (NULL, 'Admins', '1', '2021-09-21 16:52:55', '2021-09-21 16:52:55')

# register a user 
comment lines 73, 109,110 and 111 on app/Http/Controllers/AuthController.php

# api documentation
http://localhist:{PORT}/docs```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)