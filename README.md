# AutoFix Workshop - Sales CRM (Backend & Pipeline Board)

This is the Sales CRM for AutoFix Workshop, built using **PHP and Laravel**. The application uses Laravel's built-in validation, which returns standard **422 Unprocessable Entity** responses for failed validation requests. 

## Tech Stack
- **Framework:** Laravel (PHP)
- **Database:** SQLite (Chosen for zero-configuration local setup)
- **Frontend (Task 2):** Laravel Blade, Tailwind CSS, SortableJS
- **Testing:** PHPUnit (Laravel's default)

## Setup & Run Instructions
1. Clone the repository and navigate into the folder.
2. Run `composer install`.
3. Copy `.env.example` to `.env` (Ensure `DB_CONNECTION=sqlite` is set).
4. Create the database file:
   - On Mac/Linux: `touch database/database.sqlite`
   - On Windows: `type nul > database/database.sqlite`
5. Run migrations and seed the realistic workshop data: `php artisan migrate:fresh --seed`
   *(This will seed 15 realistic workshop leads spread across all 5 pipeline stages).*
6. Start the server: `php artisan serve` (Runs on http://127.0.0.1:8000)
7. Open the Pipeline Board: `http://127.0.0.1:8000/pipeline`

## How to Test
Run `php artisan test` to execute the test suite. 
The tests cover:
- **Main Path:** Successful lead creation, status update, and valid data handling.
- **Failure Path:** Missing/invalid fields returning a 422 status with specific error messages, and invalid status transitions.
*(You can also test the failure path manually by sending an invalid status via curl).*

## API Endpoints
- `GET /api/leads` - Fetch all leads.
- `POST /api/leads` - Create a new lead (Requires JSON body with `customer_name`, `phone`, `make`, `model`, `year`, `license_plate`, and `service_needed`).
- `PATCH /api/leads/{lead}/status` - Update a lead's pipeline status (Requires JSON body with `status`).

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
**Decision:** Before hitting the database or validation rules, phone numbers are normalized using `prepareForValidation()`. The system strips spaces and dashes. It only converts the number to the `+92` international format **if the number starts with `0`** (assumed local format). If it starts with a `+`, it is left untouched.
**Trade-off:** This prevents mangling foreign phone numbers. The trade-off is that a local user must type their number with a leading `0` or `+92`; if they just type `3001234567`, it might fail validation.

### 5. Pipeline Board UI (Task 2 Decision)
**Decision:** I implemented the pipeline board using native Laravel Blade, Tailwind CSS, and SortableJS for drag-and-drop functionality. I chose native JavaScript `fetch` instead of a heavy framework like React/Vue for this specific view.
**Trade-off:** This approach is extremely lightweight, requires zero build tools, and the client can run it instantly. However, if an API call fails (e.g., network error), we have to manually handle DOM state reversion in JavaScript to keep the UI in sync with the database. A framework like React would handle this automatically.

#### Known Limitation (Drag-and-Drop Revert)
**Limitation:** If the API call fails, the UI correctly reverts the card and shows an error toast. However, because it uses `evt.from.appendChild(evt.item)`, the card is placed at the **bottom** of the original column, not at its exact previous index. A future iteration will use `evt.oldIndex` to reinsert it at the exact position.

#### SortableJS Scroll Offset Fix (Tested)
**Decision:** When testing drag-and-drop with a scrolled column, the card jumped slightly. I fixed this by adding `fallbackOnBody: true`, `swapThreshold: 0.65`, and `scroll: true` to the SortableJS config.
**Trade-off:** Using `fallbackOnBody` completely eliminates the offset issue inside scrollable containers. The trade-off is a minor performance hit during drag rendering, but it ensures a perfect user experience.

### 6. Single Source of Truth for Statuses (PHP Enum)
**Decision:** I used a PHP Enum (`App\Enums\LeadStatus`) as the single source of truth for pipeline stages. Both the validation rules and the UI board read directly from this Enum.
**Trade-off:** This prevents mismatch bugs and allows for static analysis. The trade-off is a slight increase in indirection; developers must look at the Enum class to understand the pipeline flow.