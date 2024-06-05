# Factory PHP Framework

## Running the project

- Clone the repository in xampp\htdocs
- Run the Apache server with Xampp
- Open the browser or Postman and go to `localhost/Factory-PHP-Framework`

## Routes

- `GET | /events` - Display all events
- `GET | /events/{id}` - Display event by id
- `GET | /events/create` - Event creation form
- `GET | /events/edit/{id}` - Edit event form
- `POST | /events` - Create a new event
- `POST | /events/update/{id}` - Update event by id
- `POST | /events/delete/{id}` - Delete event by id
- `POST | /events/soft-delete/{id}` - Soft delete event by id