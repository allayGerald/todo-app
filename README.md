
# Laravel VueJS TODO LIST
This is a demo TODO List App.

## Contents

- [Requirements](#Requirements)
- [Installation](#Installation)
- [Tests](#Tests)

## Requirements
- PHP >= 8.0
- NodeJS >= 16.14

## Installation
- Clone this repository: `git clone git@github.com:allayGerald/todo-list.git`
- Since we are using SQLite, you should create your own database file and configure your environment variables accordingly. [See the documentation](https://laravel.com/docs/9.x/database#configuration)
- Create `.env`file with variables that suit your environment, there's `.env.example` file for reference.
- Install backend & frontend dependencies by running `composer install` and `npm install` respectively.
- Start frontend/ backend server by running `npm run dev` and `php artisan serve`
- Register and login

## Tests
- All backend tests are found under `test/feature` directory
- You might need to start the frontend server `npm run dev` for some tests to pass.
- To run test use the following command
```sh
php artisan test
```
