# Refaktorácia Aplikácie - Kompletná Dokumentácia Zmien

## 📋 Obsah
- [FÁZA 1: Cloudinary Integrácia](#fáza-1-cloudinary-integrácia)
- [FÁZA 2: Controller Refactoring](#fáza-2-controller-refactoring)
- [FÁZA 3: Use Cases Refactoring](#fáza-3-use-cases-refactoring)
- [FÁZA 4: Dependency Injection](#fáza-4-dependency-injection)
- [Architektonické Zmeny](#architektonické-zmeny)
- [Bezpečnostné Opravy](#bezpečnostné-opravy)
- [Kľúčové Slovníky](#kľúčové-slová-aplikácie)

---

## FÁZA 1: Cloudinary Integrácia

### 🎯 Cieľ
Implementovať plnú Cloudinary integráciu s DDD architektúrou.

### ✅ Hotové Komponenty
- **Konfigurácia** - `.env.example`, `config/cloudinary.php`
- **Domain Layer** - Image entity, Value Objects, Factory
- **Infrastructure** - CloudinaryStorage, ImageProcessor, ImageUploader
- **Application Layer** - UploadImage, DeleteImage, AttachImageToArticle use cases
- **API Controller** - ImageController s REST endpoints
- **DI Container** - Registrácia všetkých služieb

### 📁 Nové Súbory
```
src/
├── Domain/Image/
│   ├── Entity/Image.php
│   ├── ValueObject/ImageId.php
│   ├── ValueObject/CloudinaryMetadata.php
│   ├── Repository/ImageRepositoryInterface.php
│   ├── Service/ImageStorageInterface.php
│   ├── Service/ImageProcessorInterface.php
│   ├── Service/ImageUploaderInterface.php
│   └── Factory/ImageFactory.php
├── Infrastructure/
│   ├── Storage/CloudinaryStorage.php
│   ├── Image/CloudinaryImageProcessor.php
│   ├── Image/CloudinaryImageUploader.php
│   └── Persistence/Doctrine/DoctrineImageRepository.php
├── Application/Image/
│   ├── UploadImage.php
│   ├── DeleteImage.php
│   └── AttachImageToArticle.php
└── Infrastructure/Http/Controller/Api/ImageController.php
```

---

## FÁZA 2: Controller Refactoring

### 🎯 Cieľ
Refaktorovať všetky controllery na UseCaseHandler pattern.

### ✅ Hotové Komponenty
- **UseCaseInterface** a **BaseUseCase** - Nová štruktúra pre use cases
- **BaseController** - Aktualizovaný s ContainerInterface podporou
- **API Controllers** - ArticleApiController, AuthApiController, SessionPingController
- **Web Controllers** - ArticleController, BlogController, SearchController, AuthController
- **Mark Controllers** - ArticlesController, DashboardController, UsersController

### 🔄 Architektonické Zmeny
```php
// Pred refaktoráciou
public function create(ServerRequestInterface $request): ResponseInterface
{
    $data = json_decode((string) $request->getBody(), true);
    $article = $this->createArticle->__invoke($data['title'], $data['content']);
    return $this->jsonResponse($article);
}

// Po refaktorácii
public function create(ServerRequestInterface $request): ResponseInterface
{
    $useCase = $this->useCaseHandler->get(CreateArticle::class);
    $result = $this->executeUseCase($request, $useCase, [
        'title' => 'body:title',
        'content' => 'body:content',
        'author_id' => 'session:user_id'
    ], 'api');
    return $this->jsonResponse($result);
}
```

---

## FÁZA 3: Use Cases Refactoring

### 🎯 Cieľ
Zabezpečiť konzistentné rozhranie pre všetky use cases s typmi a validáciou.

### ✅ Hotové Komponenty
- **UseCaseInterface** - Jednotné rozhranie pre všetky use cases
- **BaseUseCase** - Abstraktná trieda s helper metódami
- **Refaktorované Use Cases** - CreateArticle, UpdateArticle, DeleteArticle, GetAllArticles, SearchArticles, LoginUser, RegisterUser
- **Validácia** - Každý use case má vlastnú `validate()` metódu
- **Štruktúrované Response** - Všetky use cases vracia `success()` alebo `error()`

### 📊 Validácia Pravidlá
- **Required fields** - `empty()` check
- **String length** - `strlen()` bounds
- **Email format** - `FILTER_VALIDATE_EMAIL`
- **Password complexity** - Regex pre uppercase, lowercase, číslo
- **UUID format** - Regex validácia

---

## FÁZA 4: Dependency Injection

### 🎯 Cieľ
Aktualizovať DI kontajner pre nové služby a UseCaseHandler.

### ✅ Hotové Komponenty
- **config/container.php** - Aktualizovaný s novými službami
- **UseCaseHandler** - Registrovaný v kontajneri s ContainerInterface podporou
- **Controller Factories** - Všetky aktualizované na nový BaseController pattern
- **Core Services** - Plná DI registrácia

### 🔄 Architektonické Zmeny
```php
// BaseController šablóna
abstract class BaseController
{
    public function __construct(
        protected ContainerInterface $container,
        protected UseCaseHandler $useCaseHandler
    ) {}
    
    protected function get(string $id): mixed
    {
        return $this->container->get($id);
    }
}
```

---

## Architektonické Zmeny

### 🏗️ Nová Architektúra
- **UseCaseHandler Pattern** - Centralizované spúšťanie use cases
- **BaseController** - Spoločná základňa pre všetky controllery
- **DI Container** - Automatická dependency resolution
- **Type Safety** - Strict typing v celom systéme

### 📋 Separácia Zodpovedností
- **Web Controllers** - Zobrazenie, read-only operácie
- **Mark Controllers** - Plné CRUD operácie
- **API Controllers** - RESTful endpoints
- **Use Cases** - Business logic a validácia

---

## Bezpečnostné Opravy

### 🛡️ Architektonický Bug Fix
**Problém:** Registrácia umožňovala vytvoriť admin/mark používateľov.

### ✅ Opravené Zmeny
- **RegisterUser** - Vždy vytvá `ROLE_USER` (role assignment je admin-only)
- **UpdateUserRole** - Nový use case pre správne role management
- **Validácia Role Hierarchie** - Len MARK users môžu byť povýšení na ADMIN
- **Controller Updates** - Odstránené role parametre z registrácie

---

## Kľúčové Slovníky Aplikácie

### 1. DOMÉNA (BUSINESS)

#### ARTICLE (Článok)
```
├── Slug (SEO URL)
├── Title (Nadpis)
├── Content (Obsah)
├── Status (Stav: draft/published/archived)
├── PublishedAt (Dátum publikácie)
├── Author (Autor)
└── Meta (Metadata)
```

#### CATEGORY (Kategória)
```
├── Name (Názov)
├── Slug (SEO URL)
└── Description (Popis)
```

#### TAG (Štítok)
```
├── Name (Názov)
└── Slug (SEO URL)
```

#### USER (Používateľ)
```
├── Email
├── Username
├── Password (hash)
├── Role (rola: user/author/admin)
└── Profile (Profil)
```

#### AUDIT LOG (Auditný záznam)
```
├── Event (Udalosť)
├── User (Používateľ)
├── Timestamp (Čas)
└── Data (Dáta)
```

### 2. CORE KONCEPTY

#### USECASE (Prípad použitia)
```
├── Input (Vstup)
├── Business Logic (Obchodná logika)
├── Output (Výstup)
└── Validation (Validácia)
```

#### CONTROLLER (Ovládač)
```
├── HTTP Handler (HTTP spracovanie)
├── Request Mapping (Mapovanie požiadavky)
├── UseCase Execution (Spustenie prípadu použitia)
└── Response Building (Vytvorenie odpovede)
```

#### MIDDLEWARE (Stredná vrstva)
```
├── Authentication (Autentifikácia)
├── Authorization (Autorizácia)
├── Validation (Validácia)
└── Logging (Logovanie)
```

#### REPOSITORY (Úložisko)
```
├── Persistence (Uloženie)
├── Query (Dotaz)
├── Criteria (Kritéria)
└── Aggregation (Agregácia)
```

### 3. ARCHITEKTONICKÉ PATTERN-Y

```
┌─────────────────────────────────────────────────┐
│           HTTP Layer (Infrastructure)           │
├─────────────────────────────────────────────────┤
│  Controllers → UseCaseHandler → UseCaseMapper  │
├─────────────────────────────────────────────────┤
│          Application Layer (Use Cases)          │
├─────────────────────────────────────────────────┤
│           Domain Layer (Business)               │
├─────────────────────────────────────────────────┤
│      Entities • Value Objects • Services        │
├─────────────────────────────────────────────────┤
│       Infrastructure Layer (Persistence)        │
└─────────────────────────────────────────────────┘
```

---

## Kľúčové Slovníky Aplikácie

### 1. KLÚČOVÉ SLOVÁ PODĽA VRSTVIEV

#### INFRASTRUCTURE LAYER
```yaml
HTTP:
  - Request (Požiadavka)
  - Response (Odpoveď)
  - Route (Trasa)
  - Middleware (Stredná vrstva)
  - Controller (Ovládač)

Persistence:
  - Repository (Úložisko)
  - Database (Databáza)
  - Migration (Migrácia)
  - Connection (Pripojenie)

View:
  - Template (Šablóna)
  - Renderer (Vykresľovač)
  - Layout (Rozloženie)
  - Partial (Čiastočný pohľad)
```

#### APPLICATION LAYER
```yaml
UseCase:
  - Command (Príkaz - mení stav)
  - Query (Dotaz - číta stav)
  - Handler (Spracovateľ)
  - Validator (Validátor)

Service:
  - Application Service (Aplikačná služba)
  - Domain Service (Doménová služba)
  - Infrastructure Service (Infraštruktúrna služba)
```

#### DOMAIN LAYER
```yaml
Entity:
  - Identity (Identita)
  - Business Rules (Obchodné pravidlá)
  - Lifecycle (Životný cyklus)

ValueObject:
  - Immutable (Nemeniteľný)
  - Validation (Validácia)
  - Equality (Rovnosť)

Aggregate:
  - Root (Koreň)
  - Consistency (Konzistencia)
  - Transaction (Transakcia)

Repository:
  - Interface (Rozhranie)
  - Implementation (Implementácia)
  - Criteria (Kritéria)
```

### 2. SLOVNÍK PRE PREMENOVANIE

| Slovak (Vaše súbory) | English (DDD/Patterns) | Popis
|-------------------|-------------------|------|
| Článok | Article | Hlavná business entita |
| Kategória | Category | Kategorizácia článkov |
| Štítok | Tag | Štítkovanie článkov |
| Používateľ | User | Systémový používateľ |
| Autor | Author | Špecifická rola používateľa |
| Administrátor | Administrator | Admin rola |
| Prípad použitia | Use Case | Business scenár |
| Ovládač | Controller | HTTP request handler |
| Úložisko | Repository | Perzistencia dát |
| Mapovač | Mapper | Transformácia dát |
| Validácia | Validation | Kontrola vstupov |
| Stredná vrstva | Middleware | Cross-cutting concerns |
| Šablóna | Template | HTML rendering |
| Trasa | Route | URL mapping |
| Požiadavka | Request | HTTP request |
| Odpoveď | Response | HTTP response |
| Autentifikácia | Authentication | Overenie identity |
| Autorizácia | Authorization | Overenie oprávnení |
| Kontajner | Container | Service container |
| Továreň | Factory | Object creation |
| Závislosť | Dependency | Service dependency |
| Konfigurácia | Configuration | Application settings |

### 3. BUSINESS KONCEPTY PRE BLOG

#### BLOG (BLOG AKO CELOK)
```yaml
CONTENT MANAGEMENT (SPRÁVA OBSAHU):
├── Article Management (Správa článkov)
│   ├── Create (Vytvorenie)
│   ├── Read (Čítanie)
│   ├── Update (Aktualizácia)
│   └── Delete (Mazanie)
├── Category Management (Správa kategórií)
├── Tag Management (Správa štítkov)
└── User Management (Správa používateľov)

PUBLISHING (PUBLIKOVANIE):
├── Draft (Koncept)
├── Review (Kontrola)
├── Scheduled (Naplánované)
├── Published (Publikované)
└── Archived (Archivované)

CONTENT (OBSAH):
├── Rich Text (Formátovaný text)
├── Markdown (Markdown syntax)
├── Media (Obrázky/videá)
└── Metadata (SEO metadata)

SEARCH (VYHĽADÁVANIE):
├── Full-text (Plnotextové)
├── Filtering (Filtrovanie)
├── Sorting (Zoradenie)
└── Pagination (Stránkovanie)
```

### 4. SECURITY KONCEPTY

#### SECURITY (BEZPEČNOSŤ)
```yaml
AUTHENTICATION (KTO STE?):
├── Session (Relácia)
├── JWT (Token)
├── OAuth (Tretia strana)
└── SSO (Single Sign-On)

AUTHORIZATION (ČO MÔŽETE?):
├── Role-based (Podľa role)
├── Permission-based (Podľa oprávnenia)
├── ACL (Access Control List)
└── RBAC (Role-Based Access Control)

PROTECTION (OCHRANA):
├── CSRF Protection (CSRF ochrana)
├── XSS Protection (XSS ochrana)
├── SQL Injection Protection
└── Rate Limiting (Obmedzenie požiadaviek)
```

### 5. TECHNICKÉ KONCEPTY

#### TECHNICKÉ (TECHNICKÉ)
```yaml
CONTAINERS (KONTAJNERY):
├── Service (Služby)
├── Factory (Továreň)
├── Dependency (Závislosť)
└── Configuration (Konfigurácia)

CONFIGURATION (NASTAVENIE):
├── Environment (Prostredie)
├── Development (Vývoj)
├── Staging (Testovacie)
└── Production (Produkcia)

LOGGING (ZÁZNAM):
├── Debug (Ladenie)
├── Info (Informácie)
├── Warning (Varovanie)
└── Error (Chyba)

CACHING (VYROVNÁVACIA PAMÄŤ):
├── Memory (Pamäť)
├── File (Súbor)
├── Database (Databáza)
└── Redis (Redis)
```

### 6. NAVRHOVANÉ CORE USE CASES

#### CORE USE CASES (JADROVÉ PRÍPADY POUŽITIA)
```yaml
1. PUBLISH_ARTICLE (Publikovať článok)
   - Input: title, content, category_id, tags, author_id
   - Output: article_id, article_data
   - Business: Validácia, slug generovanie, publikovanie

2. SEARCH_ARTICLES (Hľadať články)
   - Input: query, page, limit, filters
   - Output: articles[], count, pagination_info
   - Business: Full-text vyhľadávanie, filtrovanie, zoradenie

3. MANAGE_CATEGORIES (Spravovať kategórie)
   - Input: name, description, slug
   - Output: category_id, category_data
   - Business: Validácia názvu, unikátnosť slug

4. AUTHENTICATE_USER (Autentifikovať používateľa)
   - Input: email, password
   - Output: user_data, session_token
   - Business: Overenie hesla, session management

5. AUDIT_ACTIONS (Auditovať akcie)
   - Input: action, entity_id, user_id, metadata
   - Output: audit_log_id
   - Business: Zaznamenanie všetkých dôležitých akcií
```

#### CORE SERVICES (JADROVÉ SLUŽBY)
```yaml
1. ArticleService (Služba pre články)
   - Operations: CRUD, search, categorization
   - Dependencies: ArticleRepository, CategoryRepository, TagRepository

2. CategoryService (Služba pre kategórie)
   - Operations: CRUD, hierarchy management
   - Dependencies: CategoryRepository

3. UserService (Služba pre používateľov)
   - Operations: Authentication, authorization, profile management
   - Dependencies: UserRepository, RoleRepository

4. SearchService (Vyhľadávacia služba)
   - Operations: Full-text, filtered, paginated search
   - Dependencies: Multiple repositories, search engine

5. AuditService (Auditovacia služba)
   - Operations: Log all business actions
   - Dependencies: AuditLogRepository
```

### 7. OTÁZKY PRE UJASNENIE

#### OTÁZKY PRE UJASNENIE (ČO JE NAJVIAC DÔLEŽITÉ?)
```yaml
BUSINESS ENTITIES:
- Máme správne business entity? (Article, Category, Tag, User)
- Chýbajú nejaké dôležité koncepty? (Aggregate, Value Objects)
- Aké sú hlavné use cases? (Publikovať článok, Hľadať články)

USER WORKFLOWS:
- Aké sú hlavné user flows? (Čitateľ → Autor → Editor → Admin)
- Máme správne role management? (Čitateľ, Autor, Editor, Admin)
- Aké sú permission modely? (Role-based, Resource-based)

ARCHITECTURAL CONCERNS:
- Je architektúra čistá? (Oddelenie vrstiev)
- Sú dependency injection správne? (Container, Factory)
- Je testovateľnosť zabezpečená? (Unit, Integration tests)

BUSINESS LOGIC:
- Aké sú hlavné business pravidlá? (Publikovanie workflow, Validácia pravidiel)
- Chýbajú nejaké dôležité koncepty? (Domain Events, Specifications)
- Aké sú hlavné use cases? (napr. "Publikovať článok", "Hľadať články")
```

#### 6. NAVRHOVANÉ CORE USE CASES

#### CORE USE CASES (JADROVÉ PRÍPADY POUŽITIA)
```yaml
1. PUBLISH_ARTICLE (Publikovať článok)
   - Input: title, content, category_id, tags, author_id
   - Output: article_id, article_data
   - Business: Validácia, slug generovanie, publikovanie

2. SEARCH_ARTICLES (Hľadať články)
   - Input: query, page, limit, filters
   - Output: articles[], count, pagination_info
   - Business: Full-text vyhľadávanie, filtrovanie, zoradenie

3. MANAGE_CATEGORIES (Spravovať kategórie)
   - Input: name, description, slug
   - Output: category_id, category_data
   - Business: Validácia názvu, unikátnosť slug

4. AUTHENTICATE_USER (Autentifikovať používateľa)
   - Input: email, password
   - Output: user_data, session_token
   - Business: Overenie hesla, session management

5. MANAGE_IMAGES (Spravovať obrázky)
   - Input: file, alt_text, category_id
   - Output: image_id, image_url, metadata
   - Business: Upload, resize, metadata extraction

6. AUDIT_ACTIONS (Auditovať akcie)
   - Input: action, entity_id, user_id, metadata
   - Output: audit_log_id
   - Business: Zaznamenanie všetkých dôležitých akcií
```

#### CORE SERVICES (JADROVÉ SLUŽBY)
```yaml
1. ArticleService (Služba pre články)
   - Operations: CRUD, search, categorization
   - Dependencies: ArticleRepository, CategoryRepository, TagRepository

2. CategoryService (Služba pre kategórie)
   - Operations: CRUD, hierarchy management
   - Dependencies: CategoryRepository

3. UserService (Služba pre používateľov)
   - Operations: Authentication, authorization, profile management
   - Dependencies: UserRepository, RoleRepository

4. ImageService (Služba pre obrázky)
   - Operations: Upload, resize, transform, metadata
   - Dependencies: ImageRepository, StorageService

5. SearchService (Vyhľadávacia služba)
   - Operations: Full-text, filtered, paginated search
   - Dependencies: Multiple repositories, search engine

6. AuditService (Auditovacia služba)
   - Operations: Log all business actions
   - Dependencies: AuditLogRepository
```

---

## IMAGE/OBRÁZKY - CHÝBAJÚCI KLÚČOVÉ KONCEPTY

### 1. DOMÉNA IMAGE/OBRÁZKY

#### IMAGE (OBRÁZOK)
```yaml
├── File (Súbor)
│   ├── Path (Cesta)
│   ├── Filename (Názov súboru)
│   ├── Extension (Prípona)
│   └── Size (Veľkosť)
├── Metadata (Metadáta)
│   ├── Title (Názov)
│   ├── Alt Text (Alternatívny text)
│   ├── Caption (Popisok)
│   ├── Description (Popis)
│   ├── Dimensions (Rozmery)
│   │   ├── Width (Šírka)
│   │   ├── Height (Výška)
│   │   └── Aspect Ratio (Pomer strán)
│   ├── File Size (Veľkosť súboru)
│   ├── MIME Type (MIME typ)
│   ├── Upload Date (Dátum nahratia)
│   └── EXIF Data (EXIF dáta)
├── Variants (Varianty)
│   ├── Original (Originál)
│   ├── Thumbnail (Náhľad)
│   ├── Medium (Stredná)
│   ├── Large (Veľká)
│   └── Responsive (Responzívna)
└── Usage (Použitie)
    ├── Featured Image (Hlavný obrázok článku)
    ├── Gallery (Galéria)
    ├── Avatar (Profilový obrázok)
    └── Logo (Logo)
```

### 2. BUSINESS KONCEPTY PRE OBRÁZKY

#### A) CONTENT IMAGES (OBRÁZKY V OBSAHU)
```yaml
FEATURED IMAGE (Hlavný obrázok článku):
├── Primary image for articles
├── Displayed in listings
├── Used for social media sharing
└── Required/optional per article

CONTENT IMAGES (Obrázky v texte):
├── Inline images in article body
├── Image galleries within articles
├── Carousels/sliders
└── Lightbox support

COVER IMAGES (Obalové obrázky):
├── Category/tag cover images
├── Author profile covers
└── Site-wide banners
```

#### B) USER IMAGES (OBRÁZKY POUŽÍVATEĽOV)

#### AVATAR (Profilový obrázok):
```yaml
├── User profile picture
├── Author bio image
├── Comment author image
└── Social media profile pictures
```

BACKGROUND (Pozadie):
```yaml
├── Profile background
├── Cover photo
└── Theme images
```

#### C) SYSTEM IMAGES (SYSTÉMOVÉ OBRÁZKY)

#### LOGO (Logo):
```yaml
├── Site logo
├── Favicon
├── Mobile icon
└── Social media logos
```

ICONS (Ikony):
```yaml
├── UI icons
├── Category icons
├── Social media icons
└── Action icons
```

THEME IMAGES (Obrázky tém):
```yaml
├── Backgrounds
├── Textures
├── Patterns
└── Decorative elements
```

### 3. IMAGE PROCESSING WORKFLOW

#### UPLOAD WORKFLOW (PRACOVNÝ POSTUP NAHRAVANIA):
```yaml
1. UPLOAD (Nahrávanie):
├── File validation
├── Virus scanning
├── Size/format checks
└── Temporary storage

2. PROCESSING (Spracovanie):
├── Image optimization
├── Resizing to multiple sizes
├── Watermarking
├── Metadata extraction
└── Format conversion

3. STORAGE (Uloženie):
├── Primary storage (Cloudinary)
├── Backup storage
├── CDN distribution
└── Database metadata storage

4. CACHING (Caching):
├── Thumbnail caching
├── Metadata caching
├── CDN caching
└── Browser caching headers
```

### 4. TECHNICKÉ ŠPECIFIKÁCIE

#### IMAGE FORMATS (FORMÁTY OBRÁZOK):
```yaml
SUPPORTED FORMATS:
├── JPEG (Photographs)
├── PNG (Graphics with transparency)
├── GIF (Simple animations)
├── WebP (Modern web format)
└── SVG (Vector graphics)

RESIZE DIMENSIONS (Rozmery pre zmenu veľkosti):
├── Thumbnail: 150x150px
├── Medium: 800x600px
├── Large: 1200x900px
├── Social Media: 1200x630px
└── Icon: 64x64px
```

### 5. SECURITY KONCEPTY PRE OBRÁZKY

#### IMAGE SECURITY (BEZPEČNOSŤ OBRÁZOK):
```yaml
UPLOAD SECURITY:
├── File type validation
├── File size limits
├── Malware scanning
├── Content moderation
└── Rate limiting

ACCESS CONTROL:
├── Private/Public visibility
├── User-based permissions
├── Role-based access
└── CDN protection

WATERMARKING:
├── Copyright protection
├── Brand watermarks
├── User-specific watermarks
└── Dynamic watermarks
```

### ✅ Syntax Testy
Všetky zmenené súbory prešli PHP syntax testom:
```
✅ config/container.php
✅ config/services_ddd.php
✅ src/Core/UseCaseHandler.php
✅ src/Core/UseCaseInterface.php
✅ src/Core/BaseUseCase.php
✅ src/Infrastructure/Http/Controller/BaseController.php
✅ src/Core/ApplicationConstants.php
✅ Všetky refaktorované use cases
✅ Všetky controllery
✅ Všetky API controllery
```

### 🧪 Funkčnosť
- **Cloudinary integrácia** - Plne funkčná s upload, delete, transform
- **UseCaseHandler pattern** - Konzistentné vykonávanie use cases
- **DI Container** - Automatická dependency resolution
- **Validácia** - Komplexná validácia vstupných dát
- **Image Management** - Kompletný systém pre správu obrázkov
- **Application Constants** - Centralizované konštanty pre use cases

---

## AKTUALIZÁCIA DOMÉNA S OBRÁZKAMI

### 4. AKTUALIZÁCIA DOMÉNA S OBRÁZKAMI

#### DOMAIN LAYER STRUCTURE
```yaml
DOMAIN/
├── Article/
│   ├── Article.php (Entity)
│   ├── FeaturedImage.php (Value Object)
│   └── ArticleImage.php (Embedded Value Object)
├── Image/
│   ├── Image.php (Entity)
│   ├── ImageId.php (Value Object)
│   ├── ImageMetadata.php (Value Object)
│   ├── ImageVariant.php (Value Object)
│   ├── ImageDimensions.php (Value Object)
│   ├── ImageSize.php (Value Object)
│   ├── ImageMimeType.php (Value Object)
│   ├── ImageRepository.php (Interface)
│   └── ImageProcessor.php (Interface)
├── User/
│   ├── Avatar.php (Value Object)
│   └── ProfileImage.php (Value Object)
├── Category/
│   ├── CoverImage.php (Value Object)
│   └── CategoryImage.php (Value Object)
└── System/
    ├── SiteLogo.php (Value Object)
    ├── Favicon.php (Value Object)
    └── SystemIcon.php (Value Object)
```

#### 5. USE CASES PRE OBRÁZKY

#### CONTENT MANAGEMENT USE CASES
```yaml
UPLOAD_IMAGE (Nahrať obrázok):
├── Input: file, alt_text, category_id, user_id
├── Output: image_id, image_url, metadata, variants
├── Business: File validation, virus scan, resize, metadata extraction

ATTACH_IMAGE_TO_ARTICLE (Pripojiť k článku):
├── Input: image_id, article_id, caption, is_featured
├── Output: attachment_id, article_data
├── Business: Permission check, image validation, attachment limits

DETACH_IMAGE_FROM_ARTICLE (Odpojiť od článku):
├── Input: attachment_id, user_id
├── Output: success status, image_data
├── Business: Permission check, attachment validation, cleanup

DELETE_IMAGE (Zmazať obrázok):
├── Input: image_id, user_id
├── Output: success status, deleted_image_data
├── Business: Permission check, cascade delete, cleanup

GET_IMAGE_GALLERY (Získať galérie):
├── Input: article_id, limit, offset
├── Output: images[], pagination_info
├── Business: Permission check, image filtering, sorting

USER MANAGEMENT USE CASES
```yaml
UPLOAD_AVATAR (Nahrať profilový obrázok):
├── Input: file, user_id
├── Output: avatar_id, avatar_url, metadata
├── Business: File validation, resize, avatar update

UPDATE_AVATAR (Aktualizovať profilový obrázok):
├── Input: user_id, file
├── Output: avatar_id, avatar_url, metadata
├── Business: Permission check, file validation, resize

DELETE_AVATAR (Zmazať profilový obrázok):
├── Input: user_id, avatar_id
├── Output: success status
├── Business: Permission check, cascade delete, cleanup

SYSTEM MANAGEMENT USE CASES
```yaml
UPLOAD_SITE_LOGO (Nahrať logo stránky):
├── Input: file, admin_user_id
├── Output: logo_id, logo_url, metadata
├── Business: Admin permission, file validation, resize, cache update

UPDATE_SITE_FAVICON (Aktualizovať favicon):
├── Input: file, admin_user_id
├── Output: favicon_id, favicon_url, metadata
├── Business: Admin permission, file validation, format conversion

UPLOAD_SYSTEM_ICONS (Nahrať systémové ikony):
├── Input: icon_files[], icon_type, admin_user_id
├── Output: icon_ids[], icon_urls, metadata
├── Business: Admin permission, file validation,批量 processing

GENERATE_IMAGE_THUMBNAILS (Generovať náhľady):
├── Input: image_ids[], sizes, format
├── Output: thumbnail_urls[], generation_metadata
├── Business: Permission check, batch processing, cache update
```

#### 6. VALUE OBJECTS PRE OBRÁZKY

#### IMAGE VALUE OBJECTS
```yaml
ImageId:
├── Unique identifier for images
├── Immutable value object
├── Methods: fromString(), toString(), toInt(), equals()

ImageMetadata:
├── Title, Description, Alt Text, Caption
├── EXIF data, GPS coordinates, camera info
├── Creation date, modification date, file size
├── Methods: fromArray(), toArray(), isEmpty()

ImageVariant:
├── Size identifier (thumbnail, medium, large, original)
├── URL, dimensions, file size
├── Methods: getUrl(), getDimensions(), getSize()

ImageDimensions:
├── Width, Height, Aspect Ratio
├── Immutable value object
├── Methods: getWidth(), getHeight(), getAspectRatio()

ImageSize:
├── Width, Height in bytes
├── File size calculation
├── Methods: getWidth(), getHeight(), getBytes()

ImageMimeType:
├── MIME type validation
├── Supported formats (JPEG, PNG, GIF, WebP, SVG)
├── Methods: isImage(), isVector(), getExtension()

Avatar:
├── User profile image
├── Associated with User entity
├── Methods: getUrl(), getUser(), isDefault()

CoverImage:
├── Category or article cover image
├── Associated with Category or Article entity
├── Methods: getUrl(), getEntity(), getType()

SystemIcon:
├── UI icons, system icons
├── Associated with System configuration
├── Methods: getUrl(), getName(), getGroup()
```

---

## 📊 Výsledky Testovania

---

## 📝 Zhrnutie

Refaktorácia aplikácie bola úspešne dokončená vo všetkých 4 fázach:

1. **FÁZA 1** - Cloudinary integrácia s DDD architektúrou
2. **FÁZA 2** - Kompletná refaktorácia controllerov na UseCaseHandler pattern
3. **FÁZA 3** - Konzistentné use case rozhranie s validáciou
4. **FÁZA 4** - Dependency injection s plnou DI podporou

**Výsledok:** Moderná, škálovateľná a bezpečná architektúra s čistým oddelením zodpovedností a konzistentnými patternmi.
