# Task Management API (Modular Architecture)

This is a robust REST API for a Task Management System built with Laravel 11. It strictly follows a Domain-Driven Design (DDD) inspired Modular Architecture, where features are grouped into modules (`Auth`, `Projects`, `Tasks`, `Dashboard`), utilizing the Repository Pattern and a unified Service Layer for error handling.

## 🚀 Features

- **Modular Structure:** Code is organized by domain in `app/Modules`.
- **Authentication:** Token-based authentication using Laravel Sanctum.
- **Project Management:** Users can exclusively manage their own projects.
- **Task Management:** Full CRUD operations with advanced dynamic filtering (`status`, `priority`, `title`) and pagination.
- **Dashboard Statistics:** Provides comprehensive statistics about a user's projects and tasks (total, active, pending, completed, overdue).
- **Background Jobs:** Automated scheduled queue jobs that notify users when a task becomes overdue.
- **API Resources:** Standardized API responses with unified pagination structures.
- **Centralized Error Handling:** Handled via a foundational `BaseService`.

---

## 🛠 Installation Steps

Follow these steps to set up the project locally:

1. **Clone the repository**
   ```bash
   git clone https://github.com/zooma123/ElectroPiTask.git
   cd ElectroPiTask
   ```

2. **Install Composer Dependencies**
   ```bash
   composer install
   ```

3. **Set up Environment File**
   ```bash
   cp .env.example .env
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

---

## ⚙️ Environment Setup

Update your `.env` file to match your local database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=

# Queue Setup for Background Jobs
QUEUE_CONNECTION=database
```

Once the `.env` file is ready, run the migrations and seeders:

```bash
# Run database migrations and seed dummy data
php artisan migrate:fresh --seed
```

To run the background queue worker for the notifications:
```bash
php artisan queue:work
```

To test the periodic command that dispatches the overdue task notifications:
```bash
php artisan tasks:check-overdue
```

---

## 📖 API Documentation

The Postman base URL is typically: `http://localhost:8000/api`

### 1. Authentication Module

#### Register a new User
- **Endpoint:** `POST /register`
- **Body:** `name`, `email`, `password`
- **Response:** User object + Sanctum API token.

#### Login
- **Endpoint:** `POST /login`
- **Body:** `email`, `password`
- **Response:** Sanctum API token.

#### Logout
- **Endpoint:** `POST /logout`
- **Headers:** `Authorization: Bearer {token}`
- **Response:** Success message.

---

### 2. Projects Module (Requires Bearer Token)

#### List Projects
- **Endpoint:** `GET /projects`
- **Query Params:** `?per_page=15&sort_by=created_at&sort_order=desc`

#### Create Project
- **Endpoint:** `POST /projects`
- **Body:** `name`, `description`, `status` (Active, Completed, Archived)

#### View Project
- **Endpoint:** `GET /projects/{id}`

#### Update Project
- **Endpoint:** `PUT /projects/{id}`
- **Body:** `name`, `description`, `status`

#### Delete Project
- **Endpoint:** `DELETE /projects/{id}`

---

### 3. Tasks Module (Requires Bearer Token)

#### List Tasks
- **Endpoint:** `GET /tasks`
- **Query Params:** 
  - `status` (Todo, In Progress, Done)
  - `priority` (Low, Medium, High)
  - `title`
  - `per_page`, `sort_by`, `sort_order`

#### Create Task
- **Endpoint:** `POST /tasks`
- **Body:** `project_id`, `title`, `description`, `priority`, `status`, `due_date`

#### View Task
- **Endpoint:** `GET /tasks/{id}`

#### Update Task
- **Endpoint:** `PUT /tasks/{id}`
- **Body:** fields to update.

#### Delete Task
- **Endpoint:** `DELETE /tasks/{id}`

---

### 4. Dashboard Module (Requires Bearer Token)

#### View Statistics
- **Endpoint:** `GET /dashboard`
- **Response:** Returns `total_projects`, `active_projects`, `total_tasks`, `completed_tasks`, `pending_tasks`, `overdue_tasks`.
