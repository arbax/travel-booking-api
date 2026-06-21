# Travel Booking API

A secure and scalable RESTful API for managing flight bookings, passengers, tickets, and payments. Built with Laravel 13, PHP 8.4, and Docker.

## Tech Stack

- **Backend:** PHP 8.4 / Laravel 13
- **Database:** MySQL 8.0
- **Cache/Queue:** Redis
- **Web Server:** Nginx
- **Containerization:** Docker & Docker Compose

## Getting Started

### Requirements
- Docker
- Docker Compose

### Installation & Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/arbax/travel-booking-api.git
   cd travel-booking-api
   ```
2. Copy environment file:
   ```bash
   cp .env.example .env
   ```
3. Start the containers (Exposed on port 8080):
   ```bash
   docker-compose up -d
   ```
4. Install dependencies and generate keys:
   ```bash
   docker-compose exec app composer install
   docker-compose exec app php artisan key:generate
   ```
5. Run database migrations and seeders:
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```

## API Documentation

Swagger UI is available at:  
[http://localhost:8080/api/documentation](http://localhost:8080/api/documentation)

## Authentication

This API uses Laravel Sanctum for token-based authentication. Include the token in the `Authorization` header:
```http
Authorization: Bearer {token}
```

## Roles & Permissions

- **admin** — Full access to all bookings, users, and reports.
- **agent** — Can create and manage only their own bookings (Enforced via Laravel Policies).

## Booking Flow

1. Create a booking (`POST /api/bookings`)
2. Add passengers (`POST /api/bookings/{id}/passengers`)
3. Process payment (`POST /api/bookings/{id}/payment`)
4. Issue tickets (`POST /api/bookings/{id}/passengers/{id}/ticket/issue`)

## Technical Decisions & Architecture

- **Thin Controllers, Fat Services:** Business logic is encapsulated in Service classes to keep controllers clean and maintainable.
- **API Resources:** Used for consistent JSON output and preventing data leakage (e.g., hiding sensitive fields).
- **Form Requests:** Centralized validation logic to separate validation from controllers.
- **Policies & Gates:** Authorization is handled at the route/model level to prevent IDOR vulnerabilities.
- **Swagger Attributes:** API documentation is generated using PHP 8 Attributes for cleaner and maintainable specs.

## Testing

To run the test suite:
```bash
docker-compose exec app php artisan test
```