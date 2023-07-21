# Wishlist

## Overview
A website for creating and sharing wishlists with family and friends

## Run Locally

Clone the project

```bash
git clone git@github.com:angus-websites/wishlist.git
```

Go to the project directory

```bash
cd wishlist
```

Setup Laravel Sail

**_NOTE:_**  Ensure you have Docker installed

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

Generate a .env file using the example provided

```bash
cp .env.example .env
```

Run Laravel Sail (Development server)

```bash
./vendor/bin/sail up
```

**Open a new Terminal tab in the same project root folder**

Generate an app encryption key

```bash
./vendor/bin/sail php artisan key:generate
```

Migrate the database

```bash
./vendor/bin/sail php artisan migrate
```

Run Vite (frontend)

```bash
./vendor/bin/sail npm run dev
```

Visit [Localhost](http://localhost/)

## Configuration

Variables in the `.env` file can be customised...

| Variable Name      | Purpose                               | Accepted Values   | Default Value |
| ------------------ | ------------------------------------- | ----------------- | ------------- |
| ADMIN_NAME         | Name of the admin user                 | String            | -             |
| ADMIN_EMAIL        | Email of the admin user                | String            | -             |
| ADMIN_PASSWORD     | Password for the admin user            | String            | -             |


## Tests

Tests are located in the `tests` folder and are split into two sections...

### Feature testing

Feature tests, also known as end-to-end tests, simulate user interactions with our application. These tests cover scenarios from a user's perspective and ensure that different parts of the application work together correctly.

### Unit tests
Unit testing is a fundamental practice in software development aimed at testing individual units or components of code in isolation. Each unit test focuses on a specific function, method, or class and verifies that it behaves as expected. The goal is to detect and fix bugs early in the development process, leading to more reliable and maintainable code.

### Running test
Tests can be run using the following command...

```bash
./vendor/bin/sail php artisan test
```


## Tips

When updating certain fields in the `.env` file when using Laravel Sail, you may need to restart the Docker container for changes to take affect.


## Authors

- [@angusgoody](https://github.com/angusgoody)

