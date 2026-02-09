import{i as h,a as p,b as l,t as g,n as d,r as _}from"./chunks/vendor-lit-CeIZiaZY.js";import"./chunks/sections-2UPvd2nH.js";import"./chunks/ui-kit-BODGt7_k.js";var S=Object.getOwnPropertyDescriptor,C=(e,r,a,o)=>{for(var t=o>1?void 0:o?S(r,a):r,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=s(t)||t);return t};let m=class extends p{render(){return l`
      <main class="landing-layout">
        <slot></slot>
      </main>
    `}};m.styles=[h`
      .landing-layout {
        display: flex;
        flex-direction: column;
        gap: var(--landing-layout-gap);
      }
    `];m=C([g("boson-landing-layout")],m);var k=Object.getOwnPropertyDescriptor,O=(e,r,a,o)=>{for(var t=o>1?void 0:o?k(r,a):r,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=s(t)||t);return t};let b=class extends p{render(){return l`
      <main class="default-layout">
        <slot></slot>
      </main>
    `}};b.styles=[h`
      .default-layout {
      }
    `];b=O([g("resp-default-layout")],b);var $=Object.getOwnPropertyDescriptor,P=(e,r,a,o)=>{for(var t=o>1?void 0:o?$(r,a):r,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=s(t)||t);return t};let w=class extends p{render(){return l`
      <main class="search-layout">
        <slot></slot>

        <section class="search-content">
          <slot name="content"></slot>
        </section>
      </main>
    `}};w.styles=[h`
      .search-layout {
      }

      .search-content {
        width: var(--width-content);
        max-width: var(--width-max);
        margin: 0 auto;
        padding-bottom: 3em;
      }

      ::slotted(section) {
        margin: 2em 0;
      }
    `];w=P([g("boson-search-layout")],w);var T=Object.defineProperty,E=Object.getOwnPropertyDescriptor,u=(e,r,a,o)=>{for(var t=o>1?void 0:o?E(r,a):r,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=(o?s(r,a,t):s(t))||t);return o&&t&&T(r,a,t),t};let c=class extends p{constructor(){super(...arguments),this.logoText="LOGO",this.logoLink="/",this.navigation=[{label:"Work",href:"/work"},{label:"Services",href:"/services"},{label:"About",href:"/about"},{label:"Contact",href:"/contact"}],this._isScrolled=!1,this._handleScroll=()=>{this._isScrolled=window.scrollY>50}}firstUpdated(){window.addEventListener("scroll",this._handleScroll)}disconnectedCallback(){window.removeEventListener("scroll",this._handleScroll),super.disconnectedCallback()}render(){return l`
      <!-- Fixed Header -->
      <header class="hero-header ${this._isScrolled?"scrolled":""}">
        <a href="${this.logoLink}" class="logo">${this.logoText}</a>
        
        <nav class="nav-links">
          ${this.navigation.map(e=>l`
            <a href="${e.href}" class="nav-link">${e.label}</a>
          `)}
        </nav>
      </header>

      <!-- Main Content Slot -->
      <main class="hero-main">
        <slot></slot>
      </main>
    `}};c.styles=h`
    :host {
      display: block;
      width: 100vw;
      height: 100vh;
      position: relative;
      overflow: hidden;
    }

    /* Fixed header */
    .hero-header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      padding: 24px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 1000;
      background: linear-gradient(to bottom, rgba(13, 17, 25, 0.9), transparent);
      transition: all 0.3s ease;
    }

    .hero-header.scrolled {
      background: rgba(13, 17, 25, 0.95);
      backdrop-filter: blur(10px);
      padding: 16px 40px;
    }

    .logo {
      font-family: 'Roboto Condensed', sans-serif;
      font-size: 24px;
      font-weight: 700;
      color: white;
      text-decoration: none;
    }

    .nav-links {
      display: flex;
      gap: 32px;
    }

    .nav-link {
      color: rgba(255, 255, 255, 0.8);
      font-family: 'Inter', sans-serif;
      font-size: 14px;
      font-weight: 500;
      text-decoration: none;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      transition: color 0.3s ease;
    }

    .nav-link:hover {
      color: white;
    }

    /* Main content slot */
    .hero-main {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1;
    }

    @media (max-width: 768px) {
      .hero-header {
        padding: 16px 20px;
      }
      
      .hero-header.scrolled {
        padding: 12px 20px;
      }
      
      .nav-links {
        gap: 16px;
      }
    }
  `;u([d({type:String})],c.prototype,"logoText",2);u([d({type:String})],c.prototype,"logoLink",2);u([d({type:Array})],c.prototype,"navigation",2);u([_()],c.prototype,"_isScrolled",2);c=u([g("hero-layout")],c);var L=Object.getOwnPropertyDescriptor,H=(e,r,a,o)=>{for(var t=o>1?void 0:o?L(r,a):r,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=s(t)||t);return t};let y=class extends p{render(){return l`
      <slot name="hero"></slot>
    `}};y.styles=h`
    :host {
      display: block;
      width: 100vw;
      height: 100vh;
      position: relative;
    }
  `;y=H([g("fullscreen-hero")],y);function v(e){if(!e)return!1;const r=e.assignedNodes({flatten:!0}),a=o=>o.children.length>0||o.textContent?.trim()!=="";return r.some(o=>o.nodeType===Node.ELEMENT_NODE?a(o):o.nodeType===Node.TEXT_NODE?o.textContent?.trim()!=="":!1)}var z=Object.defineProperty,f=(e,r,a,o)=>{for(var t=void 0,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=s(r,a,t)||t);return t&&z(r,a,t),t};const D={fromAttribute(e){return e===""||e==="true"?"md":e==="false"||e===null?"":e||""},toAttribute(e){return typeof e=="boolean"?e?"md":null:e||null}},x=class x extends p{constructor(){super(),this._hasHeaderSlotContent=!1,this._hasFooterSlotContent=!1,this.stacked=!1,this.shadow=!1,this.animated=!1,this.rounded="",this.variant=""}_handleSlotChange(r){const a=r.target,o=a.name;o==="header"?this._hasHeaderSlotContent=v(a):o==="footer"&&(this._hasFooterSlotContent=v(a)),this.requestUpdate()}firstUpdated(){const r=this.shadowRoot?.querySelector('slot[name="header"]'),a=this.shadowRoot?.querySelector('slot[name="footer"]');this._hasHeaderSlotContent=v(r),this._hasFooterSlotContent=v(a),this.requestUpdate()}render(){const r=this._hasHeaderSlotContent?"card-header":"card-header empty",a=this._hasFooterSlotContent?"card-footer":"card-footer empty";return l`
      <div class="card-wrapper" part="ag-card-wrapper">
        <div class="${r}" part="ag-card-header">
          <slot name="header" @slotchange=${this._handleSlotChange}></slot>
        </div>
        <div class="card-content" part="ag-card-content">
          <slot></slot>
        </div>
        <div class="${a}" part="ag-card-footer">
          <slot name="footer" @slotchange=${this._handleSlotChange}></slot>
        </div>
      </div>
    `}};x.styles=h`
    :host {
      display: block;
      position: relative;
      box-sizing: border-box;
      width: 100%;
      /* Use the global token directly for padding */
      --card-padding: var(--ag-space-6, --ag-card-padding);
      background-color: var(--ag-background-primary);
      border: var(--ag-border-width-1) solid var(--ag-border);
    }

    /* Rounded variants - no rounding by default */
    :host([rounded="sm"]) {
      border-radius: var(--ag-radius-sm);
    }

    :host([rounded="md"]) {
      border-radius: var(--ag-radius-md);
    }

    :host([rounded="lg"]) {
      border-radius: var(--ag-radius-lg);
    }

    :host([shadow]) {
      box-shadow: var(--ag-shadow-lg);
      overflow: hidden;
    }

    /* Enhanced hover effect for shadow cards */
    :host([shadow]:hover) {
      box-shadow: var(--ag-shadow-xl);
    }

    /* Animated cards - smooth transitions */
    :host([animated]) {
      transition:
        box-shadow var(--ag-timing-fast, 150ms) ease-out,
        transform var(--ag-timing-fast, 150ms) cubic-bezier(0.39, 0.575, 0.565, 1);
    }

    :host([animated]:hover) {
      transform: translateY(-3px);
      transition:
        box-shadow var(--ag-timing-fast, 150ms) ease-out,
        transform var(--ag-timing-slow, 300ms) cubic-bezier(0.39, 0.575, 0.565, 1);
    }

    /* Respect reduced motion preferences */
    @media (prefers-reduced-motion), (update: slow) {
      :host([animated]),
      :host([animated]:hover) {
        transition-duration: 0.001ms !important;
        transform: none !important;
      }
    }

    /* Variant colors */
    :host([variant="success"]) {
      background-color: var(--ag-success-background);
      color: var(--ag-success-text);
    }

    :host([variant="info"]) {
      background-color: var(--ag-info-background);
      color: var(--ag-info-text);
    }

    :host([variant="error"]) {
      background-color: var(--ag-danger-background);
      color: var(--ag-danger-text);
    }

    :host([variant="warning"]) {
      background-color: var(--ag-warning-background);
      color: var(--ag-warning-text);
    }

    :host([variant="monochrome"]) {
      background-color: var(--ag-background-primary-inverted);
      color: var(--ag-text-primary-inverted);
    }

    .card-header,
    .card-footer {
      color: var(--ag-text-primary);
    }

    .card-header {
      padding: var(--ag-space-4) var(--card-padding);
      border-bottom: var(--ag-border-width-1) solid var(--ag-border);
    }

    .card-footer {
      padding: var(--ag-space-4) var(--card-padding);
      border-top: var(--ag-border-width-1) solid var(--ag-border);
    }

    /* Hide header/footer when empty class is applied */
    .card-header.empty,
    .card-footer.empty {
      display: none;
    }

    .card-content {
      padding: var(--card-padding);
    }

    :host([stacked]) .card-content > ::slotted(*:not(:last-child)) {
      margin-block-end: var(--ag-space-8);
    }

    /* The accessible clickable card trick */
    ::slotted(a.card-primary-action)::after {
      content: '';
      position: absolute;
      inset: 0;
      z-index: 1;
      cursor: pointer;
    }

    /* Ensure content and other actions are selectable/clickable */
    .card-content,
    ::slotted(h1), ::slotted(h2), ::slotted(h3), ::slotted(h4), ::slotted(h5), ::slotted(h6),
    ::slotted(p), ::slotted(div) {
      position: relative;
      z-index: 2;
    }

    ::slotted(.card-secondary-action) {
      position: relative;
      z-index: 2;
    }
  `;let i=x;f([d({type:Boolean,reflect:!0})],i.prototype,"stacked");f([d({type:Boolean,reflect:!0})],i.prototype,"shadow");f([d({type:Boolean,reflect:!0})],i.prototype,"animated");f([d({converter:D,reflect:!0})],i.prototype,"rounded");f([d({type:String,reflect:!0})],i.prototype,"variant");customElements.get("ag-card")||customElements.define("ag-card",i);console.log("=== APP START ===");console.log("HTMX loaded:",!!window.htmx);window.htmx?(window.htmx.config.includeIndicatorStyles=!1,window.htmx.config.indicatorClass="htmx-indicator",window.htmx.config.requestClass="htmx-request",document.addEventListener("htmx:afterSwap",e=>{console.log("HTMX swapped content:",e.detail.pathInfo?.requestPath),window.reinitializeComponents&&window.reinitializeComponents()}),document.addEventListener("htmx:beforeRequest",e=>{console.log("HTMX: Request to",e.detail.pathInfo?.requestPath)}),document.addEventListener("htmx:afterRequest",e=>{console.log("HTMX: Request completed",e.detail.successful)}),document.body.setAttribute("hx-boost","true"),console.log("HTMX initialized successfully")):console.warn("HTMX not found!");window.initPJAX&&window.initPJAX();console.log("App fully loaded");
