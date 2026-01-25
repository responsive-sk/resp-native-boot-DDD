# Responsive Blog System

Experimental blog platform built with **PHP 8.2 - 8.5**, focused on **Clean Architecture, DDD principles, testability and fast iteration**.

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

**Default Moonshine Credentials:**
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

```bash
# Clone and install dependencies
git clone <repository>
composer install
pnpm install

# Run the DDD backend (Blog API)
cd packages/blog/public
php -S localhost:8000

# Run Moonshine admin panel
cd packages/mark
php artisan serve --port=8001

# Run frontend development (optional)
cd packages/mark-web
pnpm dev
```

### Access Points

**Blog DDD Backend:**
- Frontend: http://localhost:8000
- API: http://localhost:8000/api/articles
- Mark Dashboard: http://localhost:8000/mark
- Credentials: `admin@admin.com` / `admin123`

**Moonshine Admin Panel:**
- Admin Panel: http://localhost:8001/mark
- Credentials: `mark@admin.com` / `admin123`

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

# Run backend
cd packages/blog/public && php -S localhost:8000

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
