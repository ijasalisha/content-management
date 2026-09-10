# Content Management System

A small Content Management System built with Laravel 12 and React.

The project includes a Laravel REST API for users, roles, privileges, pages and dynamic menus, together with a React public frontend that consumes the API.

## Project Structure

```text
app/          Laravel application
database/     Migrations, seeders and factories
routes/       API routes
tests/        Automated tests
frontend/     React (Vite) public frontend
```

## 1. Backend setup (Laravel)

```bash
git clone https://github.com/ijasalisha/content-management.git
cd content-management
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
```
## 2. create a MySQL database named content_management
```bash
php artisan migrate --seed
php artisan serve
```

### Seeded login credentials

| Role      | Email                  | Password  |
|-----------|-------------------------|-----------|
| Admin     | admin@example.com       | password  |
| Moderator | moderator@example.com   | password  |

Log in with `POST /api/login` to get a Sanctum bearer token, then send it as
`Authorization: Bearer <token>` on every authenticated request.

### API docs (Swagger)

```bash
php artisan l5-swagger:generate
```
Then open **http://localhost:8000/api/documentation**.

### Automated tests

```bash
php artisan test
# or: ./vendor/bin/pest
```

## Scheduled Publishing

Pages can have a future `publish_at` date.

The public API only displays pages where:

- status is `published`
- publish_at is null or has already been reached

Therefore, a page scheduled for a future date is not visible on the public website until its publish time.


## 2. Frontend setup (React)

```bash
cd frontend
npm install
cp .env.example .env
npm run dev

The frontend runs by default at: http://localhost:5173
```

##The React frontend consumes the Laravel public APIs:
GET /api/public/menus
GET /api/public/pages
GET /api/public/pages/{id}
