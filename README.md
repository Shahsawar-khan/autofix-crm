# AutoFix Workshop - Sales CRM (Leads Backend)

Backend API for AutoFix Workshop, built with Laravel.

## Setup & Run Instructions
1. `composer install`
2. Copy `.env.example` to `.env` (Ensure DB_CONNECTION=sqlite is set)
3. `touch database/database.sqlite` (or create empty database.sqlite file)
4. `php artisan migrate --seed`
5. `php artisan serve` (Runs on http://127.0.0.1:8000)

## How to Test
Run `php artisan test` to execute the test suite.

## Architecture Decision & Trade-off
**Decision:** I chose Laravel's built-in SQLite database instead of MySQL.
**Trade-off:** SQLite is file-based and requires zero configuration, making it incredibly easy for the client to run locally. However, it struggles with high-concurrency writes compared to MySQL. For a workshop CRM, SQLite is perfect now, but will need migration to MySQL if they scale to multiple branches.