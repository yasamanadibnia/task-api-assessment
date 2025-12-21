# Laravel Ewmateam Task API Template

A well-structured Laravel backend demonstrating Service + Repository architecture, design patterns, and best practices.

---

## 📝 Developer Notes

This project is built with **PHP 8.2** and **Laravel 12**, using a **Dockerized environment** with Redis, MySQL, and Nginx.

The backend follows **Service + Repository architecture**, separating business logic from data access for maintainability and testability.

**Design patterns used:**
- **Repository Pattern:** Abstracts database queries to keep services independent from Eloquent.
- **Strategy Pattern:** Applied in `BaseRepository` for flexible query filtering, searching, and pagination.
- **Observer Pattern:** Handles Task creation and update events, automatically generating notifications without cluttering service logic.

**Laravel features leveraged:**
- Notifications for async alerts
- Scopes for reusable query logic
- Rate limiting for secure endpoints

Unit tests are written for **core services and repositories** to ensure correctness and cover edge cases.

---

## 📝 CI & DevOps Summary

The project uses a **GitHub Actions CI pipeline** designed for coding assessments and developer efficiency.

### Pipeline Structure
- Independent jobs: **Tests & Coverage**, **Static Analysis**, **Code Style**, **Security Audit**, **Docker Validation**
- Benefits: **failure isolation**, **parallel execution**, easier debugging

### Testing & Coverage
- Runs automated tests with **MySQL and Redis**
- Executes database migrations during CI
- Enforces **minimum 70% code coverage**, failing builds if not met

### Static Analysis & Code Style
- PHPStan (max level) for type and architecture checks
- Laravel Pint & PHPCS (PSR-12) for consistent code style
- Fail-fast policy to maintain code quality

### Security
- `composer audit` checks for dependency vulnerabilities
- No hard-coded secrets; environment values are test-scoped

### Docker & Environment Parity
- Docker and docker-compose validated in CI
- Builds ensure runtime compatibility across local, CI, and production-like environments

**Trade-offs:**
- Queue workers are not continuously run; behavior tested via integration tests
- Coverage enforced but not uploaded externally to keep CI lightweight

---

## 🚀 Getting Started

### Prerequisites
Please check the [Laravel Installation Guide](https://laravel.com/docs/11.x/installation) for server requirements.

### Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/yasamanadibnia/task-api-assessment.git
    cd task-api-assessment
    ```

2. Build and start Docker containers:
    ```bash
    sudo docker compose build --no-cache
    sudo docker compose up -d
    sudo docker exec -it api_task_app bash
    ```

3. Install dependencies:
    ```bash
    composer install
    ```

4. Copy the example environment file and configure `.env`:
    ```bash
    cp .env.example .env
    ```

5. Generate an application key:
    ```bash
    php artisan key:generate
    ```

6. Run database migrations:
    ```bash
    php artisan migrate
    ```

7. Run tests to verify local setup:
    ```bash
    php artisan test
    ```

**Server URL:** `http://localhost:8000`

---

## 🧪 Testing API

Base API URL: `http://localhost:8000/api`

**Required Request Headers:**

| Required | Key               | Value                    |
|----------|------------------|--------------------------|
| Yes      | Content-Type      | application/json         |
| Yes      | X-Requested-With  | XMLHttpRequest           |
| Optional | Authorization     | Bearer {JWT}             |

**Example API Requests:**
```bash
# Login
curl -X POST http://localhost:8080/api/login \
  -d 'email=user@example.com&password=secret'

# register
curl -X POST http://localhost:8000/api/auth/register \
  -d 'name=John Doe&email=john@example.com&password=secret&password_confirmation=secret'
 
# Create Task
curl -X POST http://localhost:8080/api/tasks \
  -H "Authorization: Bearer {token}" \
  -d 'search=New Task&status=todo'
