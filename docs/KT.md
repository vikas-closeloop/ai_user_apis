## User Management API (Laravel) – KT Document

### Overview
This project is a **User Management REST API** built with **Laravel (latest stable)**, **PHP 8+**, and designed with a **Service Layer architecture**.

It provides:
- REST endpoints to **create**, **update**, **delete**, and **list** users.
- Server-side **validation** via Form Requests.
- Business logic in a dedicated **service**.
- A simple, dependency-free **UI** at `/users` that consumes the REST API.
- **PHPUnit** feature tests covering the API.

### Tech Stack
- **Laravel**: v12.x (scaffolded via `composer create-project`)
- **PHP**: 8+
- **DB**: MySQL (recommended for runtime), tests use Laravel’s default testing DB configuration
- **Tests**: PHPUnit via `php artisan test`

---

## Architecture (Service Layer)

### Request Flow (High Level)
1. **Route** maps request to controller action.
2. **Form Request** validates input and returns `validated()` data.
3. **Controller** delegates to the **Service** (no business logic in controller).
4. **Service** performs business operations using Eloquent `User` model.
5. **Controller** returns a JSON response.

### Key Files
- **Model**
  - `app/Models/User.php`
  - Uses Laravel’s standard `Authenticatable` model.
  - Password hashing is handled via Eloquent cast: `'password' => 'hashed'`.

- **Service**
  - `app/Services/UserService.php`
  - Methods:
    - `createUser(array $data)`
    - `updateUser(int $id, array $data)`
    - `deleteUser(int $id)`
    - `getUsers(array $filters)`
    - `getUserById(int $id)`

- **Requests (Validation)**
  - `app/Http/Requests/StoreUserRequest.php`
  - `app/Http/Requests/UpdateUserRequest.php`

- **API Controller**
  - `app/Http/Controllers/API/UserController.php`
  - Only request/response handling; calls `UserService` via dependency injection.

- **API Routes**
  - `routes/api.php`

- **UI**
  - `resources/views/users/index.blade.php`
  - `app/Http/Controllers/UserManagementController.php`
  - `routes/web.php` exposes `/users`

- **Tests**
  - `tests/Feature/UserApiTest.php`

---

## Database

### Migration
Laravel ships with the users table migration:
- `database/migrations/0001_01_01_000000_create_users_table.php`

This includes:
- `users` table (`name`, `email` unique, `password`, timestamps)
- `password_reset_tokens`
- `sessions`

### Password Hashing
Passwords are hashed automatically because the `User` model has:
- `protected function casts(): array { 'password' => 'hashed' }`

So in `UserService::createUser`, we pass the plain password from validated data and Laravel hashes it at save time.

---

## REST API Endpoints

Base path: `/api`

### 1) Create User
- **POST** `/api/users`
- Body:
  - `name` (required, string, max 255)
  - `email` (required, email, unique)
  - `password` (required, min 8)
- Response:
  - **201 Created**
  - JSON user payload

### 2) Update User
- **PUT** `/api/users/{id}`
- Body (optional):
  - `name` (sometimes, string, max 255)
  - `email` (sometimes, email, unique except current user)
- Response:
  - **200 OK**
  - JSON user payload

### 3) Delete User
- **DELETE** `/api/users/{id}`
- Response:
  - **204 No Content**

### 4) List Users
- **GET** `/api/users`
- Query parameters:
  - `page` (optional)
  - `per_page` (optional, default 15, max 100)
  - `sort_dir` (optional: `asc` or `desc`, default `desc`)
  - `search` (optional, matches `name` or `email`)
- Response:
  - **200 OK**
  - JSON payload:
    - `data`: array of users for current page
    - `links`: `{first,last,prev,next}`
    - `meta`: `{current_page,from,last_page,path,per_page,to,total}`

---

## Validation Rules

### StoreUserRequest
- `name`: `required|string|max:255`
- `email`: `required|email|unique:users,email`
- `password`: `required|min:8`

### UpdateUserRequest
- `name`: `sometimes|string|max:255`
- `email`: `sometimes|email|unique (ignore current route {id})`

When validation fails, Laravel returns:
- **422 Unprocessable Entity**
- JSON with `message` and `errors` fields

---

## UI (Simple User Manager)

### URL
- **GET** `/users`

### How it works
- The UI is a Blade page with inline JS using `fetch()`:
  - Lists users by calling `GET /api/users`
  - Creates users via `POST /api/users`
  - Updates via `PUT /api/users/{id}`
  - Deletes via `DELETE /api/users/{id}`
- Supports:
  - Search (debounced)
  - Sort by `created_at` (asc/desc)
  - Per page sizing
  - Pagination next/prev
  - Inline edit (name/email) + save

---

## Local Setup / Runbook

### 1) Install dependencies
```bash
composer install
```

### 2) Environment
Copy and configure:
- `.env`

Set DB for MySQL (example):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ai_user_apis
DB_USERNAME=root
DB_PASSWORD=
```

Generate app key (if needed):
```bash
php artisan key:generate
```

### 3) Run migrations
```bash
php artisan migrate
```

### 4) Start server
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

### 5) Quick smoke checks
- Health: `GET /up`
- UI: `GET /users`
- API list: `GET /api/users`

---

## Testing

Run full test suite:
```bash
php artisan test
```

Feature coverage in `tests/Feature/UserApiTest.php`:
- create user
- update user
- delete user
- list users (pagination/sorting/search)
- validation failure
- email uniqueness check (create + update)

---

## Notes / Extension Points
- **Authentication**: endpoints are currently open; add auth middleware if required.
- **Response Resources**: for stricter API contracts, consider `JsonResource` classes.
- **Error handling**: currently relies on Laravel defaults; can standardize error shapes globally if needed.

