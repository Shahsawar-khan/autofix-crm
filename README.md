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

### Lead vs. Contact Modeling (Task 1 Decision)
**Decision:** I chose to model Leads and Contacts in a single `leads` table for now, with the intention of adding a `status` column in the next task (e.g., 'New', 'Contacted', 'Booked'). 
**Trade-off:** Modeling them as one table simplifies the initial API and database structure. In a car workshop, a "contact" is essentially a "lead" that has successfully booked a repair. By using a single table, we avoid the complex logic of moving data from one table to another upon booking. However, the trade-off is that as the CRM grows, the single table might become cluttered with fields that only apply to existing customers (like past service history). If the business scales, we can split them into separate `leads` and `customers` tables later.