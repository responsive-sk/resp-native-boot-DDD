# Form Domain Architecture

This document outlines the architecture and components of the Form domain within the blog project, following Domain-Driven Design (DDD) principles.

## 1. Domain Layer (`src/Domain/Form`)

The core business logic resides in the Domain layer. It is independent of any technical implementation details.

### 1.1. Aggregate Root: `Form`

-   **File:** `src/Domain/Form/Entity/Form.php`
-   **Description:** This is the aggregate root for the Form domain. It encapsulates the state and business rules for a form.
-   **Key Properties:**
    -   `FormId` (Value Object)
    -   `title` (string)
    -   `slug` (string)
    -   `fields` (array)
    -   `createdAt`, `updatedAt` (DateTimeImmutable)
-   **Factory Methods:**
    -   `Form::create()`: The primary way to create a new `Form` instance. It generates a new `FormId` and timestamps.
    -   `Form::reconstitute()`: Used by the persistence layer to reconstruct a `Form` object from database data without re-applying creation logic.

### 1.2. Repository Interface

-   **File:** `src/Domain/Form/Repository/FormRepositoryInterface.php`
-   **Description:** Defines the contract for how `Form` aggregates are persisted and retrieved. The Domain layer depends on this interface, not a concrete implementation.
-   **Methods:**
    -   `save(Form $form): void`: Persists a new or existing form.
    -   `getBySlug(string $slug): ?Form`: Retrieves a form by its unique slug.

### 1.3. Value Objects & Exceptions

-   The structure includes directories for `ValueObject` (like `FormId.php`) and `Exception` for domain-specific exceptions, ensuring data integrity and clear error handling.

## 2. Application Layer (`src/Application/Form`)

This layer orchestrates the Domain layer to fulfill specific application use cases. It does not contain business logic.

### 2.1. `CreateForm` Service

-   **File:** `src/Application/Form/CreateForm.php`
-   **Description:** Handles the use case of creating a new form.
-   **Logic:**
    1.  Receives primitive data (title, slug, fields).
    2.  Calls `Form::create()` to instantiate the domain entity.
    3.  Uses the `FormRepositoryInterface` to save the new form.

### 2.2. `GetForm` Service

-   **File:** `src/Application/Form/GetForm.php`
-   **Description:** Handles the use case of retrieving a form for display or use.
-   **Logic:**
    1.  Receives a `slug`.
    2.  Uses the `FormRepositoryInterface` to find and return the corresponding `Form` entity.

## 3. Infrastructure Layer (Next Steps)

This layer has not been implemented yet. It will contain the concrete implementations of the interfaces defined in the Domain layer.

-   **Persistence:** A `DoctrineFormRepository` class needs to be created in `src/Infrastructure/Persistence/Form/` that implements `FormRepositoryInterface` and handles the actual database read/write operations.
-   **Controllers:** New HTTP controllers will be needed to expose the `CreateForm` and `GetForm` application services via API endpoints.

