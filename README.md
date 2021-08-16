
## Set up app on local machine with dummy data

php artisan migrate
php artisan db:seed

## Run the Tests

This project features 3 types of tests:

	1. Unit tests
	2. Feature tests
	3. Integration tests


## Laravel Concepts covered in this project

Seeders & Factories
Autoloading helper functions in composer.json





## Eloquent Relationships

Examples

$user->skills->pluck('title')


