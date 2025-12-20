# Laravel Ewmateam task api template

Example Laravel Task API Template for Ewmateam

A well-structured backend demonstrating Service + Repository architecture, design patterns, and Laravel best practices.

📝 Developer Notes

For this project, I used PHP 8.2 with Laravel 12 and set up the environment myself using Docker and a custom Dockerfile. The stack includes Redis, MySQL, and Nginx, providing a full-featured development setup.

The backend is structured following the Service + Repository architecture, which separates business logic from data access and makes the code more maintainable and testable.

I applied several design patterns:

Repository Pattern: To abstract database queries and keep services independent from Eloquent.

Strategy Pattern: Applied in the BaseRepository to allow different implementations of query filtering, searching, and pagination depending on the use case.

Observer Pattern: Used to handle Task creation and update events, automatically creating notifications for users without cluttering the service logic.

On the Laravel side, I utilized built-in features such as Notifications for async alerts, Scopes for reusable query logic, and Rate Limiting to secure sensitive endpoints.

I also wrote unit tests for core services and repositories to ensure correctness and cover edge cases.

Overall, the goal was to build a well-structured, maintainable backend demonstrating thoughtful architecture, proper use of Laravel features, and best practices in testing and deployment.

📝CI & DevOps Summary

The project implements a production-aware CI pipeline via GitHub Actions, balancing correctness, clarity, and speed, tailored for coding assessments.

Pipeline Structure

Split into independent jobs: Tests & Coverage, Static Analysis, Code Style, Security Audit, Docker Validation

Benefits: failure isolation, parallel execution, and easier debugging

Testing & Coverage

Runs automated tests with MySQL and Redis

Executes database migrations during CI

Enforces minimum 70% code coverage, failing builds if not met

Static Analysis & Code Style

PHPStan (max level) for architecture and type checks

Laravel Pint and PHPCS (PSR-12) for consistent code style

Fail-fast policy to prevent low-quality code merges

Security

composer audit checks for dependency vulnerabilities

No hard-coded secrets; environment values are test-scoped

Docker & Environment Parity

Docker and docker-compose validated in CI

Builds ensure runtime compatibility and parity across local, CI, and production-like environments

Trade-offs

Queue workers not continuously run; queue behavior tested via integration tests

Coverage enforced but not uploaded to external services to keep CI lightweight

Overall
The CI pipeline mirrors real-world production practices while remaining suitable for focused coding assessments.

----------

🚀 Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/11.x/installation)

Clone the repository

    git clone https://github.com/ewmateam/task-api-template.git

Switch to the repo folder

    cd task-api-template

Build and setup docker

     sudo docker compose build --no-cache
     sudo docker compose up -d
     sudo docker exec -it api_task_app bash

Install all the dependencies using composer

     composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Generate database

    php artisan migrate

Testing the local development

    php artisan test

You can now access the server at http://localhost:8000



# Testing API

The api can now be accessed at

    http://localhost:8000/api

Request headers

| **Required** 	| **Key**              	| **Value**            	 |
|----------	|------------------	|------------------------|
| Yes      	| Content-Type     	| application/json 	     |
| Yes      	| X-Requested-With 	| XMLHttpRequest   	     |
| Optional 	| Authorization    	| Token {JWT}      	     |

# API Examples

    curl -X POST http://localhost:8080/api/login \  -d 'email=user@example.com&password=secret'
    curl -X POST http://localhost:8080/api/tasks \  -H "Authorization: Bearer {token}" \-d 'title=New Task&status=todo'

-----------------------------------------------------------

Thank you for reviewing this assessment.
