# Boson Frontend

Boson frontend používa **Lit** (Web Components) a **Vite** pre build.

## Inštalácia

```bash
cd resources/views/boson
pnpm install
```

## Development

```bash
# Spustí Vite dev server na porte 5174
pnpm run dev
```

## Production Build

```bash
# Buildne assets do public/build/assets/
pnpm run build
```

Alebo z root adresára projektu:

```bash
npm run build:boson
```

## Štruktúra

- `assets/` - zdrojové súbory (JS, CSS, komponenty)
  - `app.js` - hlavný entry point
  - `app.css` - hlavný CSS súbor
  - `components/` - Lit web komponenty
  - `layout/` - layout komponenty
  - `styles/` - CSS moduly
- `vite.config.js` - Vite konfigurácia
- `package.json` - npm dependencies

## Output

Build vytvorí:
- `public/build/assets/app.js` - hlavný JS bundle
- `public/build/assets/app.css` - hlavný CSS bundle
- `public/build/assets/chunks/` - lazy-loaded chunks (Mermaid, atď.)

