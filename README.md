
## Next Commit Message

-m
Test

-m
Organizes test files into subfolders.


## Installation

$ php artisan migrate
$ php artisan db:seed



## Testing

$ touch .env.testing
$ touch test.sqlite
$ php artisan key:generate --env=testing
$ php artisan migrate --env=testing
$ php artisan test

Read notes: Tech\Laravel\testing.txt









## Eloquent Relationships

Examples

$user->skills->pluck('title')


## Developer's Journal
#### What I learnt
-- deadline:	Working with a 4-week deadline.
-- TDD:			This is my first attempt at a large TDD project
-- API:			Building a Laravel API
#### Laravel Concepts covered in this project
-- Seeders & Factories
-- Autoloading helper functions in composer.json
