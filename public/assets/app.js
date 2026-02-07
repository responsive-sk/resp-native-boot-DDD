import"./chunks/ui-kit-PpAp9_nQ.js";import{i as p,a as h,b as s,t as f,n as g,r as _}from"./chunks/vendor-lit-CeIZiaZY.js";import"./chunks/sections-BnMS8Ce_.js";var L=Object.getOwnPropertyDescriptor,O=(t,a,l,r)=>{for(var e=r>1?void 0:r?L(a,l):a,o=t.length-1,n;o>=0;o--)(n=t[o])&&(e=n(e)||e);return e};let u=class extends h{render(){return s`
      <main class="landing-layout">
        <slot></slot>
      </main>
    `}};u.styles=[p`
      .landing-layout {
        display: flex;
        flex-direction: column;
        gap: var(--landing-layout-gap);
      }
    `];u=O([f("boson-landing-layout")],u);var D=Object.getOwnPropertyDescriptor,k=(t,a,l,r)=>{for(var e=r>1?void 0:r?D(a,l):a,o=t.length-1,n;o>=0;o--)(n=t[o])&&(e=n(e)||e);return e};let y=class extends h{render(){return s`
      <main class="default-layout">
        <slot></slot>
      </main>
    `}};y.styles=[p`
      .default-layout {
      }
    `];y=k([f("boson-default-layout")],y);var A=Object.getOwnPropertyDescriptor,P=(t,a,l,r)=>{for(var e=r>1?void 0:r?A(a,l):a,o=t.length-1,n;o>=0;o--)(n=t[o])&&(e=n(e)||e);return e};let v=class extends h{render(){return s`
      <main class="search-layout">
        <slot></slot>

        <section class="search-content">
          <slot name="content"></slot>
        </section>
      </main>
    `}};v.styles=[p`
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
    `];v=P([f("boson-search-layout")],v);var $=Object.defineProperty,S=Object.getOwnPropertyDescriptor,c=(t,a,l,r)=>{for(var e=r>1?void 0:r?S(a,l):a,o=t.length-1,n;o>=0;o--)(n=t[o])&&(e=(r?n(a,l,e):n(e))||e);return r&&e&&$(a,l,e),e};let i=class extends h{constructor(){super(...arguments),this.logoText="LOGO",this.logoLink="/",this.navigation=[{label:"Work",href:"/work"},{label:"Services",href:"/services"},{label:"About",href:"/about"},{label:"Contact",href:"/contact"}],this.socialLinks=[{label:"Facebook",href:"https://facebook.com"},{label:"Instagram",href:"https://instagram.com"},{label:"LinkedIn",href:"https://linkedin.com"}],this.copyright="© 2024 Your Company. All rights reserved.",this._isScrolled=!1,this._handleScroll=()=>{this._isScrolled=window.scrollY>50}}firstUpdated(){window.addEventListener("scroll",this._handleScroll)}disconnectedCallback(){window.removeEventListener("scroll",this._handleScroll),super.disconnectedCallback()}render(){return s`
      <!-- Fixed Header -->
      <header class="hero-header ${this._isScrolled?"scrolled":""}">
        <a href="${this.logoLink}" class="logo">${this.logoText}</a>
        
        <nav class="nav-links">
          ${this.navigation.map(t=>s`
            <a href="${t.href}" class="nav-link">${t.label}</a>
          `)}
        </nav>
      </header>

      <!-- Main Content Slot -->
      <main class="hero-main">
        <slot></slot>
      </main>

      <!-- Fixed Footer -->
      <footer class="hero-footer">
        <div class="copyright">${this.copyright}</div>
        
        <div class="social-links">
          ${this.socialLinks.map(t=>s`
            <a href="${t.href}" class="social-link" target="_blank" rel="noopener">
              ${t.label}
            </a>
          `)}
        </div>
      </footer>
    `}};i.styles=p`
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

    /* Footer */
    .hero-footer {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 1000;
      color: rgba(255, 255, 255, 0.6);
      font-family: 'Inter', sans-serif;
      font-size: 12px;
    }

    .social-links {
      display: flex;
      gap: 16px;
    }

    .social-link {
      color: rgba(255, 255, 255, 0.6);
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .social-link:hover {
      color: white;
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
      
      .hero-footer {
        padding: 16px 20px;
        flex-direction: column;
        gap: 8px;
        text-align: center;
      }
    }
  `;c([g({type:String})],i.prototype,"logoText",2);c([g({type:String})],i.prototype,"logoLink",2);c([g({type:Array})],i.prototype,"navigation",2);c([g({type:Array})],i.prototype,"socialLinks",2);c([g({type:String})],i.prototype,"copyright",2);c([_()],i.prototype,"_isScrolled",2);i=c([f("hero-layout")],i);var j=Object.getOwnPropertyDescriptor,E=(t,a,l,r)=>{for(var e=r>1?void 0:r?j(a,l):a,o=t.length-1,n;o>=0;o--)(n=t[o])&&(e=n(e)||e);return e};let w=class extends h{render(){return s`
      <slot name="hero"></slot>
    `}};w.styles=p`
    :host {
      display: block;
      width: 100vw;
      height: 100vh;
      position: relative;
    }
  `;w=E([f("fullscreen-hero")],w);function C(){window.PJAX_LOADED=!0;const t=document.querySelector("[data-container]");t&&(document.addEventListener("click",async a=>{const l=a.target.closest("a, [href]");if(!l)return;const r=l.getAttribute("href");if(!r||l.getAttribute("target")||r.includes("#"))return;const o=new URL(r,window.location.href);if(o.origin===window.location.origin){a.preventDefault(),document.body.classList.add("pjax-loading");try{const n=await fetch(o.href,{headers:{"X-PJAX":"true","X-Requested-With":"XMLHttpRequest"}});if(!n.ok)throw new Error(`HTTP ${n.status}`);const x=n.headers.get("X-PJAX"),b=n.headers.get("content-type");if(x==="true"&&b?.includes("application/json")){const d=await n.json();d.title&&(document.title=d.title),d.content&&(t.innerHTML=d.content),d.url&&history.pushState({},"",d.url),window.dispatchEvent(new CustomEvent("pjax:complete"))}else window.location.href=o.href}catch{window.location.href=o.href}finally{document.body.classList.remove("pjax-loading")}}}),window.addEventListener("popstate",()=>{window.location.reload()}))}console.log("=== APP START ===");console.log("PJAX_LOADED before:",window.PJAX_LOADED);function m(){console.log("Starting app...");try{C(),console.log("PJAX initialized"),console.log("PJAX_LOADED after:",window.PJAX_LOADED)}catch(t){console.error("App failed:",t),console.trace()}}document.readyState==="loading"?document.addEventListener("DOMContentLoaded",()=>{console.log("DOM loaded, starting app"),m()}):(console.log("DOM already ready, starting app"),m());
