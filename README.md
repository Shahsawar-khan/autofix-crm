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
**Decision:** I chose to model Leads and Contacts in a single `leads` table for now, with the intention of adding a `status` column in the next task to group them in the pipeline board.
**Trade-off:** Using a single table simplifies the initial API and makes building the Task 2 pipeline board much easier (grouping by status). The cost, however, is that we will have unused fields. For example, a pure "lead" who just enquired won't have a "service history" or "company name", but those fields might exist for an existing "contact". If the workshop scales, this single table might become cluttered, and we may need to split them into separate `leads` and `customers` tables.