# TODO – smerovanie k Blog v1.0 + rich domain

## Finished (2026-02-04)

### Security & Infrastructure (COMPLETED)
   - [x] CSRF Protection middleware with token generation/validation
   - [x] Audit logging system with domain entities
   - [x] Rate limiting middleware for abuse prevention
   - [x] Session timeout middleware with fingerprint validation
   - [x] Boot.php system for shared initialization
   - [x] Environment-aware configuration system
   - [x] Security testing automation

### Code Quality (COMPLETED)
   - [x] PHP 8.4 compatibility fixes
   - [x] Container loading improvements
   - [x] Enhanced error handling middleware
   - [x] Theme fallback system in PlatesRenderer

## Aktuálny cieľ: Dokončiť web autentifikáciu + Mark Dashboard CRUD (Q1 2026)

### Denne pred očami – top priority (zaškrtávaj si postupne)

#### 1. Web autentifikácia (session-based) - COMPLETED
   - [x] SessionMiddleware implemented with secure parameters
   - [x] AuthMiddleware updated with login redirect support
   - [x] AuthController::login() with session_regenerate_id(true)
   - [x] Session storage for user_id and user_role
   - [x] AuthController::logout() with session_destroy()
   - [x] Login/logout flow with redirect functionality

### Ďalšie priority (Q1 2026)

#### 2. Mark Dashboard – základný CRUD pre články
   - [ ] MarkDashboardController::index() – zobraziť zoznam článkov (draft + published)
   - [ ] MarkArticlesController::createForm() – formulár na nový článok
   - [ ] MarkArticlesController::create() – zavolať ReviseDraftArticle use-case
   - [ ] MarkArticlesController::editForm() + edit() – edit draftu
   - [ ] Pridať tlačidlá Publish / Archive v zozname + samostatné akcie
   - [ ] Použiť Authorization::requireMark() v controlleroch

#### 3. Rich domain v Article (paralelne, 1–2 nové metódy týždenne)
   - [ ] Article::reviseDraft(Title $title, Content $content): void
   - [ ] Article::publish(): void (len z draft)
   - [ ] Article::archive(): void (len z published)
   - [ ] Nové use-cases: ReviseDraftArticle, PublishArticle, ArchiveArticle
   - [ ] API: POST /api/articles/{id}/publish (zavolať PublishArticle)

#### 4. API bezpečnosť (po dokončení web auth)
   - [ ] Pridať kontrolu Authorization header v ArticleApiController (create/update/delete)
   - [ ] Mock token check (neskôr JWT)

### Týždenné míľniky (aktualizované február 2026)

- [x] **Týždeň 1–2 (január)**: Session auth + security middleware + boot.php
- **Týždeň 3–4 (február)**: Mark Dashboard – list + create článku  
- **Týždeň 5–6 (február)**: Edit + Publish/Archive akcie
- **Týždeň 7+ (marec)**: Začať rich domain (reviseDraft + publish)

### Dlhodobé (po v1.0)

- Rozdeliť UpdateArticle na viacero akcií
- Začať Orders package (sales/) – len štruktúra + PlaceOrder
- Eventy: ArticlePublishedEvent → neskôr integrácia s inými doménami
