# Factory PHP Framework

## Running the project

- Clone the repository in xampp\htdocs
- Run the Apache server with Xampp
- Open the browser or Postman and go to `localhost/Factory-PHP-Framework`

## Database setup

- Create a mysql database named `php_framework` with credentials `root` and `factory123456789`
- Create a table named `event` with columns `id`, `name` and `description`

## Routes

- `GET` | `/`: "Welcome to the index page!"
- `GET` | `/json`: {"message": "Welcome to the index page!"}
- `GET` | `/html`: HTML page with the message "Welcome to the index page!"