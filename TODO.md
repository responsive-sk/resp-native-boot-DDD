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

# TODO: CELKOVÝ APP REFAKTOR + CLOUDINARY

1. ARCHITEKTÚRA & CORE

    Implementovať Clean Architecture vrstvy (Domain, Application, Infrastructure)

    Vytvoriť UseCaseHandler + UseCaseMapper integráciu

    Refaktorovať všetky Controllery na UseCaseHandler pattern

    Implementovať Repository pattern pre všetky entity

    Aktualizovať Dependency Injection kontajner

2. CLOUDINARY INTEGRÁCIA

    Nainštalovať Cloudinary SDK

    Vytvoriť Image Domain (Entity, Repository, Value Objects)

    Implementovať CloudinaryStorage service

    Vytvoriť ImageProcessor s transformáciami

    Pridať image upload endpointy

3. ENTITY REFAKTOR

    Article + featured image podpora

    User + avatar Cloudinary integracia

    Category/Tag + cover images

    Vytvoriť Image entity s Cloudinary metadátami

4. CONTROLLER REFAKTOR

   API Controllers:

    ArticleApiController (CRUD + images)

    AuthApiController

    ImageApiController (Cloudinary uploads)

    CategoryApiController

    TagApiController

Web Controllers:

    ArticleController (blog frontend)

    BlogController (listing)

    AuthController (login/register)

Mark/Admin Controllers:

    ArticlesController (admin CRUD)

    UsersController

    MediaController (Cloudinary library)

5. FRONTEND & UX

    Cloudinary upload widget v article editori

    Drag & drop image upload

    Media library pre správu obrázkov

    Responsive images s Cloudinary transformáciami

    Lazy loading + WebP podpora

6. INFRASTRUKTÚRA

    Aktualizovať kontajner služby

    Middleware stack optimalizácia

    Error handling + logging

    Environment config pre dev/prod

    Database migrations pre images

7. TESTOVANIE

    Unit tests pre UseCases

    Integration tests pre Cloudinary

    E2E tests pre user flows

    Performance testing image uploads

8. DEPLOYMENT

    Cloudinary setup (account + config)

    CI/CD pipeline s image processing

    Environment variables management

    Monitoring + analytics

# ROADMAP:

## TÝŽDEŇ 1-2: FOUNDATION

1. Cloudinary SDK + Image Domain
2. UseCaseHandler + BaseController
3. Refaktor 1. controller (ArticleApi)
4. Basic image upload endpoint

TÝŽDEŇ 3-4: INTEGRÁCIA
text

1. Refaktor všetkých API controllers
2. Cloudinary widget v UI
3. Featured images v článkoch
4. User avatars

TÝŽDEŇ 5-6: UX & POLISH
text

1. Media library admin
2. Image optimizácie
3. Responsive images
4. Performance tuning

TÝŽDEŇ 7-8: FINAL ALPHA
text

1. Testing + bug fixes
2. Documentation
3. Deployment setup
4. Alpha release

## PRIORITY ORDER:

    UseCaseHandler + BaseController (core architecture)

    Cloudinary základná integrácia

    Article refaktor s images

    User avatars

    Admin media library

    UX improvements

## KLÚČOVÉ VÝHODY:

Čistá architektúra - lepšia údržba
Cloudinary CDN - rýchle obrázky globálne
Automatické transformácie - žiadne manuálne resize
Scalable - pripravené na rast
Moderný stack - pripravené pre budúce features