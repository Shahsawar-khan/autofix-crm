# AutoFix Workshop - Sales CRM (Leads Backend)

This is the backend API for AutoFix Workshop's Sales CRM. It is built using **PHP and Laravel**. The application uses Laravel's built-in validation, which returns standard **422 Unprocessable Entity** responses for failed validation requests.

## Tech Stack
- **Framework:** Laravel (PHP)
- **Database:** SQLite (Chosen for zero-configuration local setup)
- **Testing:** PHPUnit (Laravel's default)

## Setup & Run Instructions
1. Clone the repository and navigate into the folder.
2. Run `composer install`.
3. Copy `.env.example` to `.env` (Ensure `DB_CONNECTION=sqlite` is set).
4. Create the database file:
   - On Mac/Linux: `touch database/database.sqlite`
   - On Windows: `type nul > database/database.sqlite`
5. Run migrations and seed the realistic workshop data: `php artisan migrate --seed`
6. Start the server: `php artisan serve` (Runs on http://127.0.0.1:8000)

## How to Test
Run `php artisan test` to execute the test suite. 
The tests cover both the **main path** (successful lead creation) and the **failure path** (missing/invalid fields returning a 422 status with specific error messages).

## API Endpoints
- `GET /api/leads` - Fetch all leads.
- `POST /api/leads` - Create a new lead (Requires JSON body with `customer_name`, `phone`, `make`, `model`, `year`, `license_plate`, and `service_needed`).

---

## Architecture Decisions & Trade-offs

### 1. Database Choice: SQLite vs. MySQL
**Decision:** I chose Laravel's built-in SQLite database instead of MySQL for this task.
**Trade-off:** SQLite is file-based and requires zero configuration, making it incredibly easy for the client to run the project locally in seconds. However, SQLite struggles with high-concurrency writes compared to MySQL. For a medium-sized workshop CRM, SQLite is perfect, but if the client scales to multiple branches simultaneously, we will need to migrate to MySQL.

### 2. Lead vs. Contact Modeling
**Decision:** I modeled Leads and Contacts in a single `leads` table for now, with the intention of adding a `status` column in the next task to group them in the pipeline board.
**Trade-off:** Using a single table simplifies the initial API and makes building the Task 2 pipeline board much easier (grouping by status). The cost, however, is that we will have unused fields. For example, a pure "lead" who just enquired won't have a "service history" or "company name", but those fields might exist for an existing "contact". If the workshop scales, this single table might become cluttered, and we may need to split them into separate `leads` and `customers` tables.

### 3. Vehicle Data Modeling
**Decision:** Instead of storing vehicle information as a single free-text string (e.g., "Honda Civic 2018"), I split it into structured columns: `make`, `model`, `year`, and `license_plate`.
**Trade-off:** This requires the client to fill in more specific fields during lead capture, slightly increasing input friction. However, the benefit is massive: the pipeline board and future job cards can now efficiently filter and group jobs by car make (e.g., all Hondas) or find a specific car by license plate. A single string would have made this impossible.