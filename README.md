
## Next Commit Message

-m
Project Model, Migration, Controller, Routing & Tests



Don't add all files to commit






-m
ListingController and routing

-m




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

Note that running `vendor\bin\phpunit` instead of
`php artisan test` gives you syntax highlighting.

#### Testing a Single Class
$ vendor\bin\phpunit --filter 'ListingTest'

#### Testing a Single Method
$ vendor\bin\phpunit --filter 'Tests\\Feature\\ListingTest::test_users_can_create_listing'










## Eloquent Relationships

Examples

$user->skills->pluck('title')





## Developer's Journal : What I Learnt While Working On This Project

#### Laravel
-- Seeders & Factories
-- Autoloading helper functions in composer.json
-- php artisan make:controller ListingController -r --api

#### Tech
-- Laragon
-- HeidiSQL
-- MySQL Shell	
-- TDD				Writing up tests before implementing features
-- API				Building a Laravel API
-- Postman			Creating and exporting a Postman Collection

-- Webscraping			Webscraping nomadlist.com; see ws-nomadlist.py

#### Non-Tech
-- how to work with a 4-week deadline
-- less coding, more researching
