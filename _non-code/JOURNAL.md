# Developer's Journal
What I Learnt While Working On This Project


# Laravel

## Seeders & Factories

## Autoloading
Autoloading helper functions in composer.json

## API Resource Controller
php artisan make:controller ListingController -r --api

## Sanctum
API token authentication. Note that we went against the official docs recommendation to use Sanctum's built-in SPA authentication features.

## Artisan Commands
Generating custom commands.

## Creating Files & Directories
See App/Console/Commands/JsonCrafts.php for example

## Pagination

## Gates & Policies
Laravel provides two primary ways of authorizing actions: gates and policies.

## Form Request Validation

## Soft Deleting
https://laravel.com/docs/8.x/eloquent#soft-deleting

## Custom Validation Rules
https://laravel.com/docs/8.x/validation#custom-validation-rules

## Cruddy by Design

## Pivot Tables
Setting up a M:M relationshipm specifying a custom name for pivot table.
Adding and removing M:M relationship records using attach() & detach().

$user->skills
$user->skills()->attach($id, ['level' => 'beginner'])
$user->skills()->detach($id)
$skill->users
$skill->users()->attach($id, ['level' => 'advanced'])
$skill->users()->detach($id)

## HTTP Responses

return response('Resource updated', 200); // client will see this message
return response('Resource updated', 204); // client will not see this message

# Laragon

# HeidiSQL

# MySQL Shell

# TDD

Writing up tests before implementing features

# Databases

## Hierarchical Table
How to build a hierarchical category table. `crafts` table.

## Webscraping
Webscraping nomadlist.com; see ws-nomadlist.py

# API

## RESTful API design
As the project requirements began to grow, I paused all coding, and spent days building up a spreadsheet laying out the RESTful API design.


Building a Laravel API

APIs typically use tokens to authenticate users and do not maintain session state between requests.

## Postman

Creating and exporting a Postman Collection


# Non-Tech

## Working With a Deadline
How to work with a 4-week deadline

## Research > Coding
Less coding, more researching
