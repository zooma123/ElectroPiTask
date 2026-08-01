# Task Management API (Modular Architecture)

Welcome to the **Task Management API**, a highly robust, scalable, and fully featured RESTful API built with **Laravel 11**. This project strictly adheres to professional software engineering standards, specifically utilizing a Domain-Driven Design (DDD) inspired Modular Architecture.

---

## 🌟 Key Features

- **Modular Architecture:** The codebase is logically divided into self-contained modules (`Auth`, `Projects`, `Tasks`, `Dashboard`) inside the `app/Modules` directory, ensuring separation of concerns and high maintainability.
- **Advanced Design Patterns:** Implements the **Repository Pattern** for database interactions and a **Service Layer** for business logic, managed centrally by a `BaseService` for unified error handling.
- **Authentication & Security:** Uses **Laravel Sanctum** for secure token-based API authentication. All operations are strictly scope-limited so users can only view and modify their own projects and tasks.
- **Dynamic Filtering & Pagination:** Endpoints support dynamic filtering (e.g., `?status=Todo&priority=High`), sorting (`sort_by`, `sort_order`), and pagination (`per_page`), utilizing eager loading to prevent N+1 query performance issues.
- **Background Jobs & Notifications:** Automated scheduled Queue Jobs to detect overdue tasks and dispatch email/database notifications using Laravel's Queue system.
- **Automated Testing Suite:** Comprehensive **Feature Tests** ensuring 100% reliability for all endpoints, utilizing `DatabaseTransactions` to run safely against real databases without data loss.
- **Standardized Responses:** Employs Laravel API Resources and Collections to guarantee a consistent JSON response structure (including metadata and pagination links).
- **Postman Collection Included:** A ready-to-use `PostmanCollection.json` file is bundled with the project for immediate testing.

---

## 🛠 System Requirements

- PHP 8.2 or higher
- Composer 2.x
- MySQL (or MariaDB)
- Git

---

## 🚀 Installation & Setup

Follow these steps to get the project up and running locally:

### 1. Clone the Repository
```bash
git clone https://github.com/zooma123/ElectroPiTask.git
cd ElectroPiTask
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Configuration
Duplicate the `.env.example` file to create your local environment settings:
```bash
cp .env.example .env
```
Open the `.env` file and configure your database settings:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=

# Required for Background Jobs
QUEUE_CONNECTION=database

# Recommended for testing emails locally
MAIL_MAILER=log 
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Run Migrations & Seed Dummy Data
This command will create all necessary tables (including the `jobs` table for queues) and populate the database with test users, projects, and tasks.
```bash
php artisan migrate:fresh --seed
```

---

## ⚙️ Background Jobs (Overdue Notifications)

The system automatically checks for overdue tasks and sends notifications to project owners. To make this work locally, you need to run two terminal commands (each in a separate terminal window):

1. **Start the Queue Worker:** (This processes the dispatched notification jobs)
```bash
php artisan queue:work
```

2. **Start the Scheduler:** (This triggers the daily check for overdue tasks at 09:00 AM)
```bash
php artisan schedule:work
```
*(Alternatively, you can manually trigger the check anytime by running: `php artisan tasks:check-overdue`)*

---

## 🧪 Automated Testing

The project includes a robust suite of Feature tests located in `tests/Feature/`. 
These tests use the `DatabaseTransactions` trait, meaning **they are 100% safe to run on your local MySQL database**. They will insert test data, run the assertions, and immediately rollback the changes without affecting your actual data.

To run the tests:
```bash
php artisan test
```

---

## 📮 Postman Collection

For your convenience, a complete Postman Collection is included.
1. Open **Postman**.
2. Click **Import** and select the `PostmanCollection.json` file located in the root directory of this project.
3. The collection is pre-configured with a `{{base_url}}` variable pointing to `http://localhost:8000/api`.
4. **Important:** After you hit the `Login` endpoint, copy the returned token and paste it into the Collection's Variables tab under the `token` variable. All other requests will automatically use this token!

---

## 📖 API Documentation Reference

*Base URL: `http://localhost:8000/api`*

### 1. Authentication (Public)
- **POST `/register`**: Registers a new user. Requires `name`, `email`, `password`.
- **POST `/login`**: Authenticates a user. Requires `email`, `password`. Returns Bearer Token.

### 2. Projects (Requires Bearer Token)
- **GET `/projects`**: Lists all projects owned by the authenticated user.
  - *Query Params:* `per_page`, `sort_by`, `sort_order`.
- **POST `/projects`**: Creates a new project. Requires `name`. Optional `description`, `status`.
- **GET `/projects/{id}`**: Retrieves details of a specific project.
- **PUT `/projects/{id}`**: Updates a project.
- **DELETE `/projects/{id}`**: Deletes a project.

### 3. Tasks (Requires Bearer Token)
- **GET `/tasks`**: Lists all tasks.
  - *Query Params:* `status` (Todo, In Progress, Done), `priority` (Low, Medium, High), `title` (Search).
- **POST `/tasks`**: Creates a new task. Requires `project_id` (must belong to user), `title`. Optional `priority`, `status`, `due_date`.
- **GET `/tasks/{id}`**: Retrieves a specific task.
- **PUT `/tasks/{id}`**: Updates a task.
- **DELETE `/tasks/{id}`**: Deletes a task.

### 4. Dashboard (Requires Bearer Token)
- **GET `/dashboard`**: Returns a JSON object with statistical metrics:
  - `total_projects`, `active_projects`, `total_tasks`, `completed_tasks`, `pending_tasks`, `overdue_tasks`.
