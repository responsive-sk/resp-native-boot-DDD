# TODO – smerovanie k Blog v1.0 + rich domain

## Aktuálny cieľ: Dokončiť web autentifikáciu + Mark Dashboard CRUD (Q1 2026)

### Denne pred očami – top priority (zaškrtávaj si postupne)

#### 1. Web autentifikácia (session-based)
   - [ ] Pridať SessionMiddleware (spustiť session_start s bezpečnými parametrami)
   - [ ] Upraviť AuthMiddleware – kontrola prihlásenia + redirect na /login s ?redirect=
   - [ ] V AuthController::login():
     - session_regenerate_id(true)
     - uložiť $_SESSION['user_id'] + $_SESSION['user_role']
     - redirect podľa ?redirect= (ak existuje)
   - [ ] V AuthController::logout(): session_destroy() + redirect na /
   - [ ] Otestovať flow: neprihlásený → /mark → redirect na login → po login späť na /mark

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

### Týždenné míľniky (príklad na január/február 2026)

- Týždeň 1–2: Dokončiť session auth + login/logout + redirect
- Týždeň 3–4: Mark Dashboard – list + create článku
- Týždeň 5–6: Edit + Publish/Archive akcie
- Týždeň 7+: Začať rich domain (reviseDraft + publish)

### Dlhodobé (po v1.0)

- Rozdeliť UpdateArticle na viacero akcií
- Začať Orders package (sales/) – len štruktúra + PlaceOrder
- Eventy: ArticlePublishedEvent → neskôr integrácia s inými doménami

Každý deň aspoň 1 zaškrtnutie z č. 1–3 = pokrok zaručený!