
## Next Commit 

see Gspreadsheets

No need to add all files when you commit!


User seeder: `users` table will always be seeded with an entry username:'ryan' password 'password'
Allowing us to set authentication in Postman.

-m
Job model.
Scaffolding for JobTest.php



Project consists of many Jobs.
Manager of Project can create a Listing for Jobs which are vacant.



## Postpone for now

Application model






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
