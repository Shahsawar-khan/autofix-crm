# AutoFix Workshop - Sales CRM (Backend, Pipeline & Reminders)

This is the Sales CRM for AutoFix Workshop, built using **PHP and Laravel**. The application uses Laravel's built-in validation, which returns standard **422 Unprocessable Entity** responses for failed validation requests. 

## Tech Stack
- **Framework:** Laravel (PHP)
- **Database:** SQLite (Chosen for zero-configuration local setup)
- **Frontend:** Laravel Blade, Tailwind CSS, SortableJS
- **Testing:** PHPUnit (Laravel's default)

## Setup & Run Instructions
1. Clone the repository and navigate into the folder.
2. Run `composer install`.
3. Copy `.env.example` to `.env` (Ensure `DB_CONNECTION=sqlite` is set).
4. Create the database file:
   - On Mac/Linux: `touch database/database.sqlite`
   - On Windows: `type nul > database/database.sqlite`
5. Run migrations and seed the realistic workshop data: `php artisan migrate:fresh --seed`
   *(This seeds 15 realistic workshop leads spread across all 5 pipeline stages, including some with and without follow-up dates).*
6. Start the server: `php artisan serve` (Runs on http://127.0.0.1:8000)
7. Open the Pipeline Board: `http://127.0.0.1:8000/pipeline`
8. Open the Follow-up Reminders: `http://127.0.0.1:8000/reminders`

## How to Test
Run `php artisan test` to execute the test suite. 
The tests cover:
- **Main Paths:** Successful lead creation, pipeline status updates, and setting follow-up reminders.
- **Failure Paths:** Missing/invalid fields returning a 422 status, invalid status transitions, and setting past dates for follow-ups.

## API Endpoints
- `GET /api/leads` - Fetch all leads.
- `POST /api/leads` - Create a new lead.
- `PATCH /api/leads/{lead}/status` - Update a lead's pipeline status.
- `PATCH /api/leads/{lead}/follow-up` - Set or update a follow-up date and note.
- `PATCH /api/leads/{lead}/follow-up/complete` - Mark a follow-up as completed (using the boolean flag).

---

## Architecture Decisions & Trade-offs

### 1. Database Choice: SQLite vs. MySQL
**Decision:** I chose Laravel's built-in SQLite database instead of MySQL for this task.
**Trade-off:** SQLite is file-based and requires zero configuration, making it incredibly easy for the client to run the project locally in seconds. However, SQLite struggles with high-concurrency writes compared to MySQL. For a medium-sized workshop CRM, SQLite is perfect, but if the client scales to multiple branches simultaneously, we will need to migrate to MySQL.

### 2. Contacts vs. Leads Normalization
**Decision:** I separated the database into `contacts` (people) and `leads` (job enquiries). The `contacts` table enforces a `unique` constraint on the `phone` column. 
**Trade-off:** This adds a slight complexity to the backend logic, as the controller must now perform a `firstOrCreate()` check to see if the customer exists before creating a new lead. However, the benefit is eliminating duplicate customer data. A repeat customer coming for a second repair will have their new job linked to their existing contact profile, enabling a full vehicle and service history view in the future.

### 3. Vehicle Data Modeling
**Decision:** Instead of storing vehicle information as a single free-text string, I split it into structured columns: `make`, `model`, `year`, and `license_plate`.
**Trade-off:** This requires the client to fill in more specific fields during lead capture, slightly increasing input friction. However, the pipeline board and future job cards can now efficiently filter and group jobs by car make or find a specific car by license plate.

### 4. Phone Number Normalization
**Decision:** Before hitting the database or validation rules, phone numbers are normalized using `prepareForValidation()`. The system strips spaces and dashes. It only converts the number to the `+92` international format **if the number starts with `0`**. If it starts with a `+`, it is left untouched.
**Trade-off:** This prevents mangling foreign phone numbers. The trade-off is that a local user must type their number with a leading `0` or `+92`; if they just type `3001234567`, it might fail validation.

### 5. Pipeline Board UI (Task 2 Decision)
**Decision:** I implemented the pipeline board using native Laravel Blade, Tailwind CSS, and SortableJS. I chose native JavaScript `fetch` instead of a heavy framework like React/Vue.
**Trade-off:** This approach is extremely lightweight, requires zero build tools, and the client can run it instantly. However, if an API call fails, we have to manually handle DOM state reversion in JavaScript to keep the UI in sync with the database.

#### Known Limitation (Drag-and-Drop Revert)
**Limitation:** If the API call fails, the UI correctly reverts the card and shows an error toast. However, because it uses `evt.from.appendChild(evt.item)`, the card is placed at the **bottom** of the original column, not at its exact previous index. A future iteration will use `evt.oldIndex` to reinsert it at the exact position.

#### SortableJS Scroll Offset Fix (Tested)
**Decision:** When testing drag-and-drop with a scrolled column, the card jumped slightly. I fixed this by adding `fallbackOnBody: true`, `swapThreshold: 0.65`, and `scroll: true` to the SortableJS config.
**Trade-off:** Using `fallbackOnBody` completely eliminates the offset issue inside scrollable containers. The trade-off is a minor performance hit during drag rendering, but it ensures a perfect user experience.

### 6. Single Source of Truth for Statuses (PHP Enum)
**Decision:** I used a PHP Enum (`App\Enums\LeadStatus`) as the single source of truth for pipeline stages. Both the validation rules and the UI board read directly from this Enum.
**Trade-off:** This prevents mismatch bugs and allows for static analysis. The trade-off is a slight increase in indirection; developers must look at the Enum class to understand the pipeline flow.

### 7. Follow-up Reminders Architecture (Task 3 Decision)
**Decision:** I chose to store reminders directly in the `leads` table using a boolean `follow_up_completed`, rather than creating a separate `follow_up_logs` table. I also used a database index on `follow_up_date` and SQL `whereDate` for filtering.
**Trade-off (The History Gap):** This approach is extremely fast and keeps the code simple, but the **cost is that we lose history**. A boolean only tells us *if* a follow-up was completed, but it does not tell us **when** it was completed. Once marked as done, we only know the current status, not the full timeline of past calls. If AutoFix Workshop requires a full interaction log (especially necessary for Task 4's email logging), we will have to migrate to a separate `follow_up_logs` table that tracks timestamps.

#### Security Note (IDOR Protection)
**Note:** The API routes (e.g., `/follow-up/complete`) accept the lead ID directly from the URL. To prevent unauthorized access or crashes from invalid IDs, the controller uses Laravel's **Route Model Binding** (`Lead $lead`). This automatically returns a **404 Not Found** if the ID does not exist. In a future task with authentication, we will scope this further so users can only modify their own leads.

### 9. Email Logs & Templates Architecture (Task 4 Decision)
**Decision:** I stored the exact `subject` and `body` text inside the `email_logs` table, rather than just storing a `template_id` reference. Additionally, I chose to simulate email sending (logging to the database) instead of integrating a real SMTP server for this task.
**Trade-off (Data Duplication & Mocking):** Storing the full text duplicates data and increases database size, but it creates an **immutable audit trail**. If a manager edits or deletes a template later, the log will still show exactly what was sent to the customer at that specific time. The `template_id` is kept as a nullable foreign key with `onDelete('set null')` so deleting a template doesn't break the history. 
Regarding the mocking: Skipping real SMTP allows the client to test the CRM instantly without configuring Mailgun/SMTP credentials. The trade-off is that no real email actually leaves the server yet. To fix this in production, we would implement Laravel's `Mail::to()->send()` and a Mailtrap/SMTP setup, keeping the logging logic intact.

### 10. Sales Dashboard Architecture (Task 5 Decision)
**Decision:** I implemented the Sales Dashboard by running SQL aggregate functions (`SUM`, `COUNT`, `GROUP BY`) at the database level, rather than loading all leads into PHP and calculating the metrics in memory. I used **Chart.js** for the frontend data visualization.
**Trade-off:** Performing aggregations in SQL is highly efficient and ensures the dashboard loads instantly, even with tens of thousands of records. The trade-off is that SQLite has limited support for advanced analytical functions compared to a dedicated data warehouse (like BigQuery). If the workshop needs complex time-series forecasting or multi-dimensional reporting in the future, we would need to migrate the database or set up a data pipeline.