


User seeder: `users` table will always be seeded with an entry username:'ryan' password 'password'
Allowing us to set authentication in Postman.


# Terminology/Taxonomy: Models

Project
	Every Project consists of a minimum of 2 jobs and 2 team members.
	A Project should tell the public:

Job
	When a Job is unoccupied, the project manager can post a listing for the vacancy by creating a JobVacancy.

JobVacancy
	A listing for a job which is unoccupied.
	Throughout the project, we typically assign a JobVacancy instance to a variable '$listing' rather than '$jobVacancy' e.g.:
	$listing = JobVacancy::find($id)


# Installation

$ php artisan migrate
$ php artisan db:seed



# Testing

$ touch .env.testing
$ touch test.sqlite
$ php artisan key:generate --env=testing
$ php artisan migrate --env=testing
$ php artisan test

Read notes: Tech\Laravel\testing.txt

Note that running `vendor\bin\phpunit` instead of
`php artisan test` gives you syntax highlighting.

## Testing a Single Class
$ vendor\bin\phpunit --filter 'ListingTest'

## Testing a Single Method
$ vendor\bin\phpunit --filter 'Tests\\Feature\\ListingTest::test_users_can_create_listing'










## Eloquent Relationships



`$user->skills->pluck('title')`

**JSON Nested Object**

`User::find($id)->with('profile')->with('skills')->get() `





# Developer's Journal
What I Learnt While Working On This Project

## Tech

### Laravel

#### Seeders & Factories

#### Autoloading
Autoloading helper functions in composer.json

#### API Resource Controller
php artisan make:controller ListingController -r --api

#### Passport

#### Artisan Commands
Generating custom commands.

#### Creating Files & Directories
See App/Console/Commands/JsonCrafts.php for example

### Laragon

### HeidiSQL

### MySQL Shell

### TDD

Writing up tests before implementing features

### Databases
How to build a hierarchical category table. `crafts` table.

### API

Building a Laravel API

APIs typically use tokens to authenticate users and do not maintain session state between requests.

### Postman

Creating and exporting a Postman Collection

### Webscraping

Webscraping nomadlist.com; see ws-nomadlist.py

## Non-Tech

### Working With a Deadline
How to work with a 4-week deadline

### Research > Coding
Less coding, more researching
