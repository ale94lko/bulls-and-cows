<h1 align="center">Bulls and Cows Game</h1>
<p>
  <a href="https://github.com/ale94lko/php-cs-fixer-action/blob/main/LICENSE" target="_blank">
    <img alt="License: MIT" src="https://img.shields.io/badge/License-MIT-green.svg" />
  </a>
</p>

> A RESTful api to play the Bulls and Cows game 

## Requirements
  - [Composer 2.5](https://getcomposer.org/download/)
  - [PHP 8.1](https://www.php.net/downloads)

## Installation
### You can fork the repository
- Using GitHub Desktop:
  - [Getting started with GitHub Desktop](https://docs.github.com/en/desktop/installing-and-configuring-github-desktop/getting-started-with-github-desktop) will guide you through setting up Desktop.
  - Once Desktop is set up, you can use it to [fork the repo](https://docs.github.com/en/desktop/contributing-and-collaborating-using-github-desktop/cloning-and-forking-repositories-from-github-desktop)!
- Using the Command Line
  - [Fork the repo](https://docs.github.com/en/github/getting-started-with-github/fork-a-repo#fork-an-example-repository).

### Or you can clone it
```
  ~$ git clone https://github.com/ale94lko/bulls-and-cows.git
```

## Setup
### Navigate to the repo folder
```
  $ cd bulls-and-cows
```
### Install dependencies
```
  $ composer install
```
### Configure game database
  - Find the configuration file `.env.example` and renamed to `.env`
  - Open `.env` and change the database configuration
    ```diff
    -  DB_CONNECTION=mysql
    -  DB_HOST=127.0.0.1
    -  DB_PORT=3306
    -  DB_DATABASE=myapp
    -  DB_USERNAME=root
    -  DB_PASSWORD=
    +  DB_CONNECTION=sqlite
    ```
  - Run migrations
    ```
    $ php artisan migrate
    ```
### Start Laravel's local development server
```
  $ php artisan serve
```
## :tada: Your game is up and running!

### Start making api requests
  - You can use the file `api-documentation.yaml` to load the api documentation into [Swagger](https://editor.swagger.io/) and start making request to the game.
