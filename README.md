# Task Tracker API (Laravel)

## 📖 Architectural Overview

```
+----------------+       +----------------+       +-----------------+       +------------------+
|    Controller   |  -->  |     Service     |  -->  | Repository (Intf)|  -->  | InMemory Repo Impl|
+----------------+       +----------------+       +-----------------+       +------------------+
         |                         |                          |                            |
         |                         |                          |                            |
         |------------------------>                          |                            |
                       DTO / Entity                         Entity                      
```

- **Controller** — handles incoming HTTP requests, validates data, passes it to services, and returns responses.
- **Service** — contains business logic and coordinates interactions between repositories and entities.
- **Repository Interface** — defines the contract for task storage operations.
- **InMemory Repository Implementation** — a temporary in-memory storage implementation.
- **Entity** — a business object representing a task with fields and behavior.
- **DTO (Data Transfer Object)** — an object for transferring structured data between layers.

## 📌 Justification of Design Decisions and Patterns

- **Repository Pattern** — isolates the data storage layer from business logic, making it easy to switch from in-memory to database implementation.
- **DTO (Data Transfer Object)** — allows structured data transfer between layers without coupling to storage models.
- **Entity** — encapsulates task data and domain behavior.
- **Enum** — used for task statuses to avoid magic strings.
- **Service Layer** — implements business logic, keeping it separate from controllers and repositories.

## 🚀 Instructions to Run the Project

1. Install dependencies:

```
composer install
```

2. Run Laravel Sail:


```
./vendor/bin/sail up -d
```

3. Execute tests:

```
./vendor/bin/sail artisan test
```

4. API Endpoints available:
- `POST /api/tasks`
- `GET /api/tasks`
- `PATCH /api/tasks/{id}/status`
- `PATCH /api/tasks/{id}/assign`

## 🛠 Scalability & Extensibility Plan

**Adding Comments to Tasks**

To support comments, introduce a new `CommentEntity`, `CommentDTO`, `CommentRepositoryInterface`, and an InMemory/DB implementation. Controllers and services for comments will integrate cleanly into the current architecture without altering existing classes.

**Adding User Roles (admin, developer)**

For this:
- Introduce a `UserEntity` and a `RoleEnum`.
- Implement a `UserRepositoryInterface`.
- Extend authentication services and controllers.
- Add policies or middleware to enforce role checks.

**Migrating to Database Storage**

Thanks to `TaskRepositoryInterface`, simply implement a new `EloquentTaskRepository` (or another DB-based implementation) and replace the binding in the service provider:

```
$this->app->bind(TaskRepositoryInterface::class, EloquentTaskRepository::class);
```

The rest of the architecture remains unchanged.

## 📦 Project Structure

```
/app
  /DTO
    TaskDto.php
  /Entities
    Task.php
  /Enums
    TaskStatus.php
  /Http/Controllers
    TaskController.php
  /Repositories
    TaskRepositoryInterface.php
    InMemoryTaskRepository.php
  /Services
    TaskService.php
```
