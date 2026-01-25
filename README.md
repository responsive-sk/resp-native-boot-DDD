# Responsive Blog System

Experimental blog platform built with **PHP 8.3**, focused on **Clean Architecture, DDD principles, testability and fast iteration**.

This repository is intentionally opinionated and serves as a foundation for a larger modular system.

---

## Core Principles (Non-Negotiable)

- Domain logic is **framework-agnostic**
- No `Service` classes — **each use-case is an explicit class**
- Business rules live only in the **Domain**
- Application layer orchestrates, never contains business logic
- Infrastructure is a thin adapter layer
- Backend and frontend are **fully separated**
- Bounded contexts are strictly separated (one domain = one database file + one set of use-cases)
- Shared kernel exists only for truly universal concepts (UserId, Email, Money, DomainEvent)
- No cross-domain business logic
- Future domains communicate only via domain events or explicit integration points
- Names matter — incorrect naming is considered a bug

> Any architectural deviation from these rules is considered a defect

---

## High-Level Architecture

```
packages/
├── core/                 # shared kernel (UserId, Email, Money, DomainEvent, ...)
├── blog/                 # active domain (DDD backend + API)
├── mark/                 # Moonshine admin panel (Laravel)
├── sales/                # future domain (Orders, Products, ...)
├── users/                # future – ak sa User rozšíri na samostatnú doménu
└── islands/              # frontend experiments

src/                      # legacy monolithic structure (being phased out)
├── Application/
├── Domain/
├── Infrastructure/
└── Core/
```

### Domain Rules

- No dependencies on frameworks, HTTP, DB, or IO
- Entities are **rich models**, not data containers
- Value Objects are immutable (`readonly`)
- Identifiers are Value Objects (e.g. `ArticleId`)
- Domain may emit events but never handles side-effects

---

## Current Domains

- **Blog** (active)  
  Primary aggregate: Article  
  Status: fully functional (CRUD, public + Mark dashboard, API)

- **Users** (active, shared core)  
  Core identity + authentication  
  Status: session-based auth, ROLE_MARK, registration/login

---

## Admin Panels

The system provides two admin interfaces for managing content:

### 1. Blog DDD Mark Dashboard (PHP Native)
- **Location:** `packages/blog/`
- **URL:** `http://localhost:8000/mark`
- **Tech Stack:** PHP 8.3 + Plates templates + FastRoute
- **Architecture:** Clean Architecture + Use Cases pattern
- **Features:** 
  - Article CRUD operations
  - Domain-driven design implementation
  - Framework-agnostic business logic
- **Status:** Active, aligned with DDD principles

### 2. Moonshine Admin Panel (Laravel)
- **Location:** `packages/mark/`
- **URL:** `http://localhost:8001/mark`
- **Tech Stack:** Laravel 12 + Moonshine v4
- **Architecture:** Per-model SQLite databases
- **Features:**
  - Modern UI with Tailwind CSS
  - Bulk actions (publish, unpublish, delete)
  - Advanced filtering and sorting
  - Auto-slug generation from title
  - Real-time validation
- **Database Configuration:**
  - Articles: `packages/blog/data/articles.db` (shared with DDD backend)
  - Moonshine users: `packages/mark/database/database.sqlite`
- **Authentication:** Separate Moonshine admin users (not connected to blog users)
- **Status:** Active, production-ready

**Default Moonshine Credentials (development only):**
```
Email: mark@admin.com
Password: admin123
```

**Creating new Moonshine admin user:**
```bash
cd packages/mark
php artisan moonshine:user
```

See `docs/MOONSHINE_SETUP.md` for detailed configuration and setup instructions.

---

## Planned / In-Progress Domains

The system is designed to grow as a collection of bounded contexts.

- **Orders** (architecture draft)  
  Planned aggregates: Order, OrderItem, Payment  
  Goals:  
  - Separate bounded context with own database (`orders.db`)  
  - Integration with Users domain via shared `UserId`  
  - Event-driven communication (`OrderPlacedEvent` → possible integration with other contexts)  
  - Status: high-level design only, no implementation yet

Future domains under consideration:  
- Payments  
- Inventory / Products  
- Marketing / Campaigns

Each new domain will follow the same strict rules:  
- Own database file  
- Own Application layer (use-cases)  
- Own Infrastructure adapters  
- Minimal or no coupling to other domains except via shared core

---

## Blog Domain

- Primary aggregate: **Article**
- Article lifecycle is explicit and enforced by the domain
- Repositories are **interfaces defined in Domain**

---

## Naming Conventions

- The system operator identity is named **Mark**
- `Mark` is a concrete role, not a generic administrator
- Using `Admin`, `Administrator`, or `Superuser` is considered incorrect naming

**Correct:** Mark, ROLE_MARK, isMark(), promoteToMark()

**Incorrect:** Admin, ROLE_ADMIN, isAdmin(), promoteToAdmin()

---

## Current Status (Refactored)

✅ **Admin → Mark** migration completed  
- ROLE_ADMIN → ROLE_MARK  
- Admin Dashboard → Mark Dashboard  
- All controllers and use cases updated

✅ **Post → Article** migration completed  
- Database tables: `posts` → `articles`  
- Domain entities fully renamed  
- API endpoints: `/api/posts` → `/api/articles`

✅ **Architecture refactored**  
- FastRoute router implemented  
- Container-based dependency injection  
- Use Case pattern for all operations  
- 23 routes, 6 middlewares functional

✅ **Moonshine Admin Panel integrated**  
- Laravel 12 + Moonshine v4  
- Per-model SQLite databases  
- Shared articles.db with DDD backend  
- Bulk actions and advanced filtering

---

## Tech Stack

### Backend (DDD)
- PHP 8.3
- SQLite (per-domain databases)
- Doctrine DBAL (Infrastructure only)
- Plates template engine (View layer only)
- FastRoute for routing
- PHPUnit (unit & integration tests)

### Admin Panel (Moonshine)
- Laravel 12
- Moonshine v4
- Tailwind CSS
- SQLite (shared articles.db)

### Frontend
- pnpm (frontend tooling)
- SvelteKit (`frontend-mark/`)
- Islands Architecture (Svelte, Vue, React ...)

---

## Frontend

Frontend code is intentionally isolated from backend concerns:

- `frontend-mark/` — SvelteKit frontend
- `islands/` — Islands Architecture experiments (Svelte, Vue, React ...)
- No shared runtime dependencies with backend

---

## Getting Started

Quick start (local development environment):

- Clone the repository and install dependencies:

  ```bash
  git clone <repository>
  cd resp-native-boot-DDD
  composer install
  pnpm install   # if using frontend parts
  ```

- Run the DDD backend (executable from repository root):

  ```bash
  # From repository root
  php -S 127.0.0.1:8000 -t public public/index.php
  ```

  Alternative (if you want to serve from the `public` directory):

  ```bash
  cd public
  php -S 127.0.0.1:8000
  ```

- Run the Moonshine admin panel (Laravel) - if packages/mark exists:

  ```bash
  cd packages/mark
  composer install
  php artisan serve --port=8001
  ```

These commands are ready for PHP 8.3 and assume SQLite files are present in the `data/` directory.

### Access Points

**Blog DDD Backend:**
- Frontend: http://127.0.0.1:8000
- API: http://127.0.0.1:8000/api/articles
- Mark Dashboard: http://127.0.0.1:8000/mark
- Development credentials: `admin@admin.com` / `admin123`
  
  ⚠️ **Security Note:** These are development-only credentials. Change them before deploying to production.

**Moonshine Admin Panel (if using packages/mark):**
- Admin Panel: http://127.0.0.1:8001/mark
- Development credentials: `mark@admin.com` / `admin123`
  
  Create new admin user: `cd packages/mark && php artisan moonshine:user`

### API Endpoints

```bash
GET    /api/articles          # List all articles
GET    /api/articles/{id}     # Get single article
POST   /api/articles          # Create article (Mark only)
PATCH  /api/articles/{id}     # Update article (Mark only, TODO)
DELETE /api/articles/{id}     # Delete article (Mark only, TODO)

POST   /api/auth/register     # Register new user
POST   /api/auth/login        # Login (returns mock token)
```

---

## Project Structure

```
packages/
├── blog/                 # Blog domain + DDD backend
│   ├── config/          # Container, routes, services
│   ├── data/
│   │   ├── articles.db  # Article domain database (shared with Moonshine)
│   │   └── users.db     # User domain database
│   ├── public/          # Entry point
│   ├── resources/       # Plates templates
│   ├── src/
│   │   ├── Application/ # Use Cases
│   │   ├── Domain/      # Entities, Value Objects, Repositories
│   │   └── Infrastructure/ # Doctrine, HTTP, Views
│   └── tests/           # Unit & Integration tests
│
├── mark/                # Moonshine admin panel
│   ├── app/
│   │   ├── Models/
│   │   │   └── Article.php  # Connection: 'articles' → ../blog/data/articles.db
│   │   └── MoonShine/
│   │       └── Resources/
│   │           └── Article/
│   │               └── ArticleResource.php
│   ├── config/
│   │   └── database.php  # Per-model connections
│   └── database/
│       └── database.sqlite  # Moonshine meta tables
│
├── core/                # Shared kernel (in progress)
├── islands/             # Web Components htmx in-jections
├── sales/               # Future Orders domain
└── users/               # Future extended User domain
```

---

## Database Architecture

Per-domain SQLite databases with shared access:

```
packages/blog/data/
├── articles.db    # Shared between Blog DDD and Moonshine
└── users.db       # Blog DDD users only

packages/mark/database/
└── database.sqlite  # Moonshine meta tables (cache, sessions, moonshine_users)
```

### Database Schema

```sql
-- articles table (articles.db)
CREATE TABLE articles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    user_id INTEGER,
    status VARCHAR(50) DEFAULT 'draft',
    slug VARCHAR(100) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    category TEXT
);

-- users table (users.db)
CREATE TABLE users (
    id TEXT PRIMARY KEY,
    email TEXT UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'ROLE_USER',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- moonshine_users table (database.sqlite)
CREATE TABLE moonshine_users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email VARCHAR(190) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    moonshine_user_role_id INTEGER,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## Development Commands

### DDD Backend

```bash
# Install dependencies
composer install
pnpm install

# Run backend (recommended)
cd packages/blog
php -S 127.0.0.1:8000 -t public public/index.php

# Run tests
composer test
composer test:unit
composer test:integration

# Code quality
composer test:static-analysis
composer fix:cs
```

### Mark Admin

```bash
# Install dependencies
cd packages/mark
composer install

# Run admin panel
php artisan serve --port=8001

# Create admin user
php artisan moonshine:user

# Run migrations
php artisan migrate

# Clear cache
php artisan config:clear
php artisan cache:clear
```

---

## Operational notes: DB files & permissions

- DB file locations (blog domain):
  - `data/articles.db`
  - `data/users.db`
- Moonshine meta DB location (if using packages/mark):
  - `packages/mark/database/database.sqlite`

- If files are missing, create the directory and set permissions (local development):

```bash
mkdir -p data
touch data/articles.db data/users.db
chmod 664 data/*.db
chown $(whoami):$(whoami) data/*.db
```

- When using a web server (locally), ensure the process has write access to these files. For Docker or CI, you may need to adjust ownership (UID/GID).

---

## Ports (local development)

- DDD backend (Blog): `127.0.0.1:8000`
- Moonshine admin (Laravel): `127.0.0.1:8001`
- Frontend dev (SvelteKit): typically `5173`

---

## Troubleshooting (common issues)

- "PDOException: unable to open database file": check file permissions and ownership in `data/`.
- "Address already in use": port is occupied — choose a different port for `php -S` or kill the running process.
- "Unsupported PHP version": verify with `php -v` and switch to PHP 8.3 (e.g., using phpbrew, docker, or system package manager).

---

## Testing Strategy

- **Unit Tests:** Domain entities and value objects
- **Integration Tests:** Use cases with real repositories
- **HTTP Tests:** Controller endpoints
- **Mutation Testing:** Infection for test quality

```bash
composer test:infection
```

---

## Deployment

See `docs/DEPLOYMENT.md` for detailed instructions.

```bash
# Build production assets
./build-production.sh

# Test production build
./test-production.sh

# Check deployment size
./check-deploy-size.sh
```

---

## Contributing

- Follow the Core Principles strictly
- Use correct naming conventions (Mark, not Admin)
- Write tests for all new functionality
- Keep documentation updated
- No emojis in documentation or commit messages
- Write README files and git commit messages in English only. This ensures consistency across the project and helps contributors from different regions collaborate effectively.

---

## Documentation

Detailed documentation lives in the `docs/` directory:

- `ARCHITECTURE.md` — Architecture overview
- `DATABASE.md` — Database design and migrations
- `DEPLOYMENT.md` — Deployment instructions
- `FRONTEND_ARCHITECTURE.md` — Frontend architecture
- `MICRO_FRONTENDS.md` — Micro-frontends approach
- `ISLANDS_QUICKSTART.md` — Islands Architecture guide
- `PERFORMANCE_OPTIMIZATION.md` — Performance guidelines
- `MOONSHINE_INTEGRATION_SUMMARY.md` — Moonshine integration details
- `MOONSHINE_PACKAGE.md` — Moonshine package structure
- `PER_MODEL_DB.md` — Per-model database configuration

---

## License

MIT
