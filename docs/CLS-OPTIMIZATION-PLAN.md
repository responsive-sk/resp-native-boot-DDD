# CLS Optimization Plan - Layout Shift Fix

## Problem Analysis

**Current CLS:** 0.300 (High - target <0.1)

**Causes:**
1. ❌ Lit Web Components load asynchronously → causes reflow
2. ❌ No reserved space for components before hydration
3. ❌ Fonts load after initial render
4. ❌ All components load on every page (no code splitting)

## Solution Strategy

### Phase 1: Skeleton Screens (Quick Win)
**Goal:** Reserve space before components load

#### 1.1 CSS Skeleton Placeholders
```css
/* styles/skeleton.css */
/* Reserve exact space for components */

/* Hero section skeleton */
hero-section:not(:defined) {
    display: block;
    min-height: 600px;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

/* Header skeleton */
boson-header:not(:defined),
resp-header:not(:defined) {
    display: block;
    height: 80px;
    background: var(--color-surface);
}

/* Button skeleton */
boson-button:not(:defined) {
    display: inline-block;
    width: 120px;
    height: 48px;
    background: #e0e0e0;
    border-radius: 8px;
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}
```

#### 1.2 CSS containment
```css
/* Prevent layout shifts */
hero-section,
boson-header,
boson-footer {
    content-visibility: auto;
    contain: layout style paint;
}
```

### Phase 2: Font Optimization
**Goal:** Prevent font-swap layout shift

#### 2.1 Preload Critical Fonts
```html
<!-- In master.php <head> -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="font" 
      href="/fonts/inter-var.woff2" 
      type="font/woff2" 
      crossorigin="anonymous">
```

#### 2.2 Font Display Strategy
```css
@font-face {
    font-family: 'Inter';
    src: url('/fonts/inter-var.woff2') format('woff2');
    font-display: optional; /* Prevents FOUT/FOIT */
    font-weight: 100 900;
}
```

### Phase 3: Route-Based Code Splitting
**Goal:** Load only needed components per page

#### 3.1 Component Manifest per Route
```javascript
// src/route-manifests.js
export const routeComponents = {
    '/': [
        'hero-section',
        'call-to-action-section',
        'boson-landing-layout'
    ],
    '/blog': [
        'article-list-section',
        'boson-blog-layout'
    ],
    '/blog/:slug': [
        'boson-breadcrumbs',
        'boson-blog-layout'
    ]
};
```

#### 3.2 Dynamic Component Loader
```javascript
// src/component-loader.js
export class ComponentLoader {
    static loaded = new Set();
    
    static async loadForRoute(pathname) {
        const components = routeComponents[pathname] || [];
        
        const promises = components
            .filter(name => !this.loaded.has(name))
            .map(name => this.loadComponent(name));
        
        await Promise.all(promises);
    }
    
    static async loadComponent(name) {
        if (this.loaded.has(name)) return;
        
        // Dynamic import based on component name
        const module = await import(`./components/${name}.js`);
        this.loaded.add(name);
        return module;
    }
}
```

### Phase 4: SSR Placeholders
**Goal:** Server renders placeholder HTML

#### 4.1 Server-Side Placeholder Generation
```php
// src/Infrastructure/View/ComponentPlaceholder.php
class ComponentPlaceholder {
    public static function hero(array $data = []): string {
        return <<<HTML
        <hero-section style="min-height: 600px">
            <h1 slot="title" style="visibility: hidden">
                {$data['title']}
            </h1>
        </hero-section>
        HTML;
    }
    
    public static function header(): string {
        return <<<HTML
        <resp-header style="height: 80px; display: block">
            <!-- Placeholder content -->
        </resp-header>
        HTML;
    }
}
```

### Phase 5: Critical CSS Inlining
**Goal:** Instant skeleton rendering

#### 5.1 Extract Critical CSS
```bash
# Install critical CSS tool
npm install -D critical

# Generate critical CSS
npx critical public/index.html --base public --inline
```

#### 5.2 Inline in Master Layout
```php
<!-- layout/master.php -->
<head>
    <style>
        <?php 
        // Inline critical CSS for instant render
        echo file_get_contents(__DIR__ . '/../styles/critical.css'); 
        ?>
    </style>
    
    <!-- Load full CSS async -->
    <link rel="preload" 
          href="/build/assets/app.css" 
          as="style" 
          onload="this.onload=null;this.rel='stylesheet'">
</head>
```

## Implementation Plan

### Week 1: Quick Wins (Target CLS: <0.15)
- [ ] Add skeleton CSS for all components
- [ ] Implement CSS containment
- [ ] Preload critical fonts
- [ ] Use font-display: optional

### Week 2: Code Splitting (Target CLS: <0.1)
- [ ] Create route manifests
- [ ] Implement ComponentLoader
- [ ] Update build process for code splitting
- [ ] Add route detection in master.php

### Week 3: SSR Optimization (Target CLS: <0.05)
- [ ] Create ComponentPlaceholder helpers
- [ ] Update PHP templates to use placeholders
- [ ] Inline critical CSS
- [ ] Async load non-critical CSS

### Week 4: Polish & Measure
- [ ] Run Lighthouse tests
- [ ] Fine-tune skeleton animations
- [ ] A/B test different strategies
- [ ] Document final approach

## Quick Start: Minimal Implementation

### Step 1: Add Skeleton CSS (5 min)
```html
<!-- In layout/master.php <head> -->
<style>
    /* Prevent CLS before components load */
    *:not(:defined) {
        visibility: hidden;
    }
    
    hero-section:not(:defined) {
        visibility: visible;
        display: block;
        min-height: 600px;
        background: #f5f5f5;
    }
</style>
```

### Step 2: Reserve Component Space (10 min)
```css
/* In app.css */
hero-section { min-height: 600px; }
boson-header { height: 80px; }
boson-footer { min-height: 200px; }
article-list-section { min-height: 400px; }
```

### Step 3: Font Preload (2 min)
```html
<link rel="preload" 
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" 
      as="style">
```

## Measurement

### Before
```
CLS: 0.300
FCP: ?
LCP: ?
```

### After (Target)
```
CLS: <0.1 ✅
FCP: <1.8s
LCP: <2.5s
```

### Tools
- Lighthouse CI
- WebPageTest
- Chrome DevTools Performance
- web-vitals library

## Notes

- **SEO Impact:** Google uses CLS as ranking factor
- **UX Impact:** Users hate layout jumps
- **Mobile:** CLS worse on mobile (slower JS)
- **Route-based splitting:** Best long-term solution

## Resources

- [Web.dev CLS Guide](https://web.dev/cls/)
- [Lit Performance Best Practices](https://lit.dev/docs/tools/performance/)
- [Font Loading Strategies](https://web.dev/font-best-practices/)
