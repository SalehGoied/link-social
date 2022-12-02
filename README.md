# ![Laravel Example App](public/logo.svg)

# Description

  A simple  REST API with Laravel for social media app.

## resources

- [Database table](https://gitmind.com/app/docs/fsjk7rvx)
- [route documintation](https://salehgoied.github.io/link-social/public/docs/)

# Getting started

## Installation

Clone the repository

    git clone https://github.com/SalehGoied/link-social.git

Switch to the repo folder

    cd link-social

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

**TL;DR command list**

    git clone https://github.com/SalehGoied/link-social.git
    cd link-social
    composer install
    cp .env.example .env

    
**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan migrate
    php artisan serve

## Database seeding

**Populate the database with seed data with relationships which includes users, posts, comments, images, favorites and follows. This can help you to quickly start testing the api or couple a frontend and start using it with ready content.**

Run the database seeder and you're done

    php artisan db:seed

***Note*** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

    php artisan migrate:refresh
    
 ***Note*** : Go to [cloudinary](https://cloudinary.com/)
 - Register your account, get API key and paste it into `.env` file.
# Code overview

## Dependencies

- [cloudinary](https://cloudinary.com/) - For store image in cloud.

## Folders

- `app` - Contains all the Eloquent models
- `app/Http/Controllers/Api` - Contains all the api controllers
- `app/Http/Middleware` - Contains the auth middleware
- `app/Http/Requests` - Contains all the api form requests
- `config` - Contains all the application configuration files
- `database/factories` - Contains the model factory for all the models
- `database/migrations` - Contains all the database migrations
- `database/seeds` - Contains the database seeder
- `routes` - Contains all the api routes defined in api.php file
- `tests` - Contains all the application tests (soon)
- `tests/Feature/Api` - Contains all the api tests (soon)

## Environment variables

- `.env` - Environment variables can be set in this file

***Note*** : You can quickly set the database information and other variables in this file and have the application fully working.

----------

# Testing API

Run the laravel development server

    php artisan serve

The api can now be accessed at

    http://localhost:8000/api/v1

Request headers

| **Required** 	| **Key**              	| **Value**            	|
|----------	|------------------	|------------------	|
| Yes      	| Content-Type     	| application/json 	|
| Yes      	| X-Requested-With 	| XMLHttpRequest   	|
| Optional 	| Authorization    	| Token      	|

----------
 
# Authentication
 
This applications uses auth Sanctum to handle authentication.

----------

# Cross-Origin Resource Sharing (CORS)
 
This applications has CORS enabled by default on all API endpoints.