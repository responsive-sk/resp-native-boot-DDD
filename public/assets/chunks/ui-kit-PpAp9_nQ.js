import{i as d,n as u,a as m,b as a,t as g,A as C}from"./vendor-lit-CeIZiaZY.js";var T=Object.defineProperty,A=Object.getOwnPropertyDescriptor,X=(e,o,s,n)=>{for(var t=n>1?void 0:n?A(o,s):o,r=e.length-1,i;r>=0;r--)(i=e[r])&&(t=(n?i(o,s,t):i(t))||t);return n&&t&&T(o,s,t),t};let _=class extends m{constructor(){super(),this.isScrolled=!1,this.handleScroll=this.handleScroll.bind(this)}connectedCallback(){super.connectedCallback(),window.addEventListener("scroll",this.handleScroll),this.handleScroll()}disconnectedCallback(){super.disconnectedCallback(),window.removeEventListener("scroll",this.handleScroll)}handleScroll(){const e=window.pageYOffset||document.documentElement.scrollTop;this.isScrolled=e>0}render(){return a`
      <header class="${this.isScrolled?"scrolled":""}">
        <div class="dots">
          <dots-container></dots-container>
        </div>

        <slot name="logo"></slot>

        <div class="nav">
          <slot></slot>
        </div>

        <aside class="aside">
          <slot style="display: flex" name="aside"></slot>
          <slot name="mobile-menu"></slot>
        </aside>

        <div class="dots">
          <dots-container></dots-container>
        </div>
      </header>
      <div class="header-padding"></div>
    `}};_.styles=[d`
      :host {
        --header-height: 100px;
        --header-height-scrolled: 70px;
      }

      header {
        height: var(--header-height, 100px);
        line-height: var(--header-height, 100px);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--color-border);
        transition-duration: 0.2s;
        background: var(--color-bg-opacity);
        backdrop-filter: blur(14px);
        z-index: 10;
      }

      header.scrolled {
        height: var(--header-height-scrolled, 70px);
        line-height: var(--header-height-scrolled, 70px);
      }

      .header-padding {
        width: 100%;
        height: var(--header-height, 100px);
      }

      .dots,
      ::slotted(*) {
        height: 100% !important;
        max-height: 100% !important;
        line-height: inherit !important;
      }

      ::slotted(boson-dropdown) {
        display: flex;
      }

      ::slotted(boson-search-input) {
        border: none;
        margin-left: auto !important;
        order: 2 !important;
        padding: 0 !important;
      }

      ::slotted(.logo) {
        border-right: solid 1px var(--color-border);
      }

      .dots:nth-child(1) {
        border-right: 1px solid var(--color-border);
      }

      .nav {
        flex: 1;
        padding: 0 3em;
        display: flex;
        gap: 1em;
        border-right: 1px solid var(--color-border);
        align-self: stretch;
        align-items: center;
      }

      .aside {
        display: flex;
      }

      .aside ::slotted(*) {
        border-right: 1px solid var(--color-border) !important;
      }

      ::slotted([mobile='true']) {
        display: none;
      }

      ::slotted(mobile-header-menu) {
        display: none;
        border-right: none !important;
      }

      @media (orientation: portrait) {
        ::slotted([pc='true']) {
          display: none;
        }
        ::slotted(.logo) {
          flex: 1;
        }
        ::slotted([mobile='true']) {
          display: flex;
          align-self: stretch;
        }
        ::slotted(mobile-header-menu) {
          display: flex;
          align-self: stretch;
          min-height: var(--header-height-scrolled);
          max-height: var(--header-height-scrolled);
        }
        header {
          height: var(--header-height-scrolled, 70px);
          line-height: var(--header-height-scrolled, 70px);
        }
        .dots {
          display: none;
        }

        .nav {
          display: none;
        }
      }
    `];X([u({type:Boolean})],_.prototype,"isScrolled",2);_=X([g("boson-header")],_);var F=Object.getOwnPropertyDescriptor,N=(e,o,s,n)=>{for(var t=n>1?void 0:n?F(o,s):o,r=e.length-1,i;r>=0;r--)(i=e[r])&&(t=i(t)||t);return t};let E=class extends m{render(){return a`
      <footer class="container">
        <div class="content">
          <div class="top">
            <div class="holder"></div>

            <slot name="main-link"></slot>

            <div class="dots-main">
              <div class="dots-inner"></div>
            </div>

            <slot name="aside-link"></slot>

            <div class="holder"></div>
          </div>

          <div class="bottom">
            <div class="holder"></div>

            <div class="copyright">
              <slot name="copyright"></slot>
            </div>

            <slot name="secondary-link"></slot>

            <div class="holder"></div>
          </div>

          <div class="dots-left">
            <dots-container></dots-container>
          </div>

          <div class="dots-right">
            <dots-container></dots-container>
          </div>
        </div>

      </footer>
    `}};E.styles=[d`
    .container {
      display: flex;
      flex-direction: column;
    }

    .content {
      border-top: 1px solid var(--color-border);
      border-bottom: 1px solid var(--color-border);
      display: flex;
      flex-direction: column;
      position: relative;
    }

    .top {
      display: flex;
      border-bottom: 1px solid var(--color-border);
    }

    .bottom {
      display: flex;
    }

    .dots-left, .dots-right {
      min-width: 120px;
      max-width: 120px;
      position: absolute;
      top: 0;
      bottom: 0;
    }

    .dots-left {
      left: 0;
    }

    .dots-right {
      right: 0;
    }

    .holder {
      min-width: 120px;
      max-width: 120px;
    }

    .holder:nth-child(1) {
      border-right: 1px solid var(--color-border);
    }

    ::slotted(a) {
      padding: 3.5em 0;
      display: flex !important;
      justify-content: center;
      align-items: center;
      width: 230px;
      border-right: 1px solid var(--color-border);
      transition-duration: 0.2s;
      text-transform: uppercase;
      font-family: var(--font-title), sans-serif;
    }

    ::slotted(a:hover) {
      background: var(--color-bg-hover);
    }

    [name="secondary-link"]::slotted(a) {
      color: var(--color-text-secondary) !important;
    }

    [name="secondary-link"]::slotted(a:hover) {
      background: var(--color-bg-hover) !important;
    }

    .dots-main {
      flex: 1;
      border-right: 1px solid var(--color-border);
      padding: 1em;
    }

    .dots-inner {
      height: 100%;
      width: 100%;
      background: url("/images/icons/dots.svg");
    }

    .copyright {
      flex: 1;
      border-right: 1px solid var(--color-border);
      display: flex;
      align-items: center;
      margin-left: 3em;
      color: var(--color-text-secondary);
    }

    .credits {
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 2em;
    }

    .credits img {
      height: 24px;
    }

    .credits-link {
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition-duration: 0.2s;
    }
    .credits-link:hover {
      opacity: 0.7;
    }

    @media (orientation: portrait) {
      .dots-left, .dots-right, .holder {
        display: none;
      }
      .top {
        flex-direction: row-reverse;
        flex-wrap: wrap;
      }
      .top > a {
        background: red;
      }
      ::slotted(a) {
        width: unset;
        flex: 34%;
      }
      .bottom {
        flex-direction: column-reverse;
      }
      ::slotted(.social) {
        flex: 21%;
      }
      [name="secondary-link"]::slotted(a) {
        flex: 1;
        padding: 1.5em 0;
        border-bottom: 1px solid var(--color-border);
      }
      .copyright {
        padding: 1.5em 0;
        margin-left: 0;
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
      }

      .dots-main {
        flex: 1;
        min-width: 90vw;
        height: 100px;
        border-top: 1px solid var(--color-border);
        border-bottom: 1px solid var(--color-border);
      }
    }
  `];E=N([g("boson-footer")],E);const V=d`
h1,
h2,
h3,
h4,
h5,
h6 {
  font-family: var(--font-title), sans-serif;
  color: var(--color-text);
  margin: 0;
  padding: 0;
}

h1 {
  font-size: var(--font-size-h1);
  line-height: var(--font-line-height-h1);
  margin: var(--font-size-h1) 0 calc(var(--font-size-h1)/3) 0;
  font-weight: 600;
}

h1 img {
  margin-right: calc(var(--font-size-h1)/4);
}

h1 span {
  color: var(--color-text);
}

h2 {
  font-size: var(--font-size-h2);
  line-height: var(--font-line-height-h2);
  margin: var(--font-size-h2) 0 calc(var(--font-size-h2)/3) 0;
  font-weight: 400;
}

h2 img {
  margin-right: calc(var(--font-size-h2)/4);
}

h3 {
  font-size: var(--font-size-h3);
  line-height: var(--font-line-height-h3);
  margin: var(--font-size-h3) 0 calc(var(--font-size-h3)/2) 0;
  font-weight: 400;
}

h3 img {
  margin-right: calc(var(--font-size-h3)/4);
}

h4 {
  font-size: var(--font-size-h4);
  line-height: var(--font-line-height-h4);
  margin: var(--font-size-h4) 0 calc(var(--font-size-h4)/2) 0;
  font-weight: 400;
}

h4 img {
  margin-right: calc(var(--font-size-h4)/4);
}

h5 {
  font-size: var(--font-size-h5);
  line-height: var(--font-line-height-h5);
  margin: var(--font-size-h5) 0 calc(var(--font-size-h5)/2) 0;
  text-transform: uppercase;
  font-weight: 400;
}

h5 img {
  margin-right: calc(var(--font-size-h5)/4);
}

h6 {
  font-size: var(--font-size-h6);
  line-height: var(--font-line-height-h6);
  margin: var(--font-size-h5) 0 calc(var(--font-size)/2) 0;
  text-transform: uppercase;
  font-weight: 400;
}

h6 img {
  margin-right: calc(var(--font-size-h6)/4);
}

.heading-permalink {
  margin-right: .2em;
  user-select: none;
}

pre,
code,
kbd {
  font-family: var(--font-mono), monospace;
}

pre[data-lang] {
  padding: 1em 1.5em;
  border: solid 1px var(--color-border);
  background: var(--color-bg-layer);
  margin: 1.5em 0;
  overflow: auto;
}

pre[data-lang="mermaid"] {
  border: none;
  background: none;
  font-weight: 200;
  display: flex;
  justify-content: center;
}

code,
kbd {
  background: rgba(255, 255, 255, .03);
  padding: .05em .4em;
}

kbd {
  font-weight: 100;
  border: solid 1px var(--color-border);
  background: none;
}

pre>code {
  background: none;
  padding: 0;
}

.tooltip,
*[term],
tooltip {
  font-style: italic;
  position: relative;
  border-bottom: dashed 1px var(--color-text);
  cursor: default;
  white-space: nowrap;
}

.tooltip:hover,
*[term]:hover,
tooltip:hover {
  color: var(--color-text-brand);
}

.tooltip::before,
*[term]::before,
tooltip::before {
  display: block;
  position: absolute;
  user-select: none;
  pointer-events: none;
  opacity: 0;
  transform: translateY(10px);
  transition: .2s ease;
  color: var(--color-text);
  font-style: normal;
  font-size: var(--font-size-secondary);
  white-space: nowrap;
}

.tooltip::before,
*[term]::before,
tooltip::before {
  content: attr(term);
  background: var(--color-bg-tooltip, #1c212f);
  border: solid 1px var(--color-border);
  padding: .2em 1em;
  right: 0;
  top: 28px;
  z-index: 99;
}

.tooltip:hover::before,
*[term]:hover::before,
tooltip:hover::before {
  opacity: 1;
  transform: translateY(0);
}

blockquote {
  color: var(--color-quote-text);
  background: var(--color-quote);
  border-left: solid 8px var(--color-quote-border);
  margin: 1em 0;
  padding: 1em 1.2em;
  display: block;
  position: relative;
}

blockquote pre[data-lang] {
  border: solid 1px var(--color-bg);
}

blockquote.tip {
  color: var(--color-quote-tip-text);
  background: var(--color-quote-tip);
  border-left: solid 8px var(--color-quote-tip-border);
}

blockquote.note {
  color: var(--color-quote-note-text);
  background: var(--color-quote-note);
  border-left: solid 8px var(--color-quote-note-border);
}

blockquote.mac,
blockquote.macos,
blockquote.linux,
blockquote.windows,
blockquote.warning {
  color: var(--color-quote-warning-text);
  background: var(--color-quote-warning);
  border-left: solid 8px var(--color-quote-warning-border);
}

blockquote.mac,
blockquote.macos,
blockquote.linux,
blockquote.windows {
  padding-left: 60px;
}

blockquote.mac::before,
blockquote.macos::before,
blockquote.linux::before,
blockquote.windows::before {
  content: '';
  background: var(--color-quote-warning-border) center center no-repeat;
  background-size: 16px 16px;
  display: block;
  width: 32px;
  height: 32px;
  position: absolute;
  left: 14px;
}

blockquote.mac::before,
blockquote.macos::before {
  background-image: url(/images/icons/apple.svg);
}

blockquote.linux::before,
blockquote.linux::before {
  background-image: url(/images/icons/linux.svg);
}

blockquote.windows::before,
blockquote.windows::before {
  background-image: url(/images/icons/windows.svg);
}

blockquote>ul,
blockquote>p {
  margin: 0;
}

blockquote>ul>li {
  margin: .1em 0;
}

table {
  width: 100%;
  border: solid 1px var(--color-border);
}

table>thead {
  background: var(--color-border);
  font-family: var(--font-title), sans-serif;
  text-transform: uppercase;
  text-align: left;
}

table th {
  font-weight: 400;
  font-size: var(--font-size-secondary);
  color: var(--color-text-secondary);
}

table th,
table td {
  padding: 10px;
}

table tr:hover td {
  background: var(--color-bg-hover);
  transition: .2s ease;
}

a:visited,
a {
  color: inherit;
  text-decoration: none;
  position: relative;
  display: inline-block;
  line-height: inherit;
}

a::before {
  content: '';
  height: .1em;
  width: 100%;
  display: inline-block;
  background: var(--color-text-brand);
  position: absolute;
  left: 0;
  bottom: 0;
  transform: scaleX(0);
  transition: transform .2s ease;
  transform-origin: 100% 0;
}

a.active,
a:not(.button):hover {
  color: var(--color-text-brand);
  text-decoration: none;
}

a.active::before,
a:hover::before {
  transform: scaleX(1);
  transform-origin: 0 0;
  transition: transform .3s ease;
}

a.external,
a.external-link {
  margin-right: 14px !important;
}

a.external::after,
a.external-link::after {
  content: '';
  width: 12px;
  height: 12px;
  display: block;
  background: url(https://intellij-icons.jetbrains.design/icons/AllIcons/expui/ide/externalLink_dark.svg) center center no-repeat;
  background-size: 12px 12px;
  text-decoration: none;
  position: absolute;
  top: 4px;
  right: -14px;
  transform: translate(0, 0) scale(1);
  transition: transform .2s ease;
}

a.external:hover::after,
a.external-link:hover::after {
  transform: translate(2px, -6px) scale(1.2);
  transition: transform .3s ease;
}

a img {
  margin-right: 8px;
  display: inline-block;
}

.emphasis {
  color: var(--color-text-brand);
}

ul {
  list-style: square;
  padding-inline-start: 24px;
}

ul>li {
  margin: 1.3em 0;
}

ul ul {
  margin-top: .7em;
}

ul ul>li {
  margin: .3em 0;
  font-size: var(--font-size-secondary);
}

ul>li::marker {
  color: var(--color-text-brand);
}

p {
  margin: 1em 0;
}

* {
  box-sizing: border-box;
}

@media (orientation: portrait) {
  h1 {
    font-size: 5rem;
  }

  h2 {
    font-size: clamp(3rem, 1vw + 3.5rem, 5rem);
  }

  h3 {
    font-size: max(2rem, min(2rem + 1vw, 5rem));
  }

  h4 {
    font-size: max(1.5rem, min(2rem + 1vw, 2.25rem));
  }

  h5 {}

  h6 {}

  p {
    font-size: 1.25rem;
  }
}

::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: var(--color-bg-hover);
}

::-webkit-scrollbar-thumb {
  background: var(--color-text-brand);
}

::-webkit-scrollbar-thumb:hover {
  background: var(--color-text-brand-hover);
}
`,x=d`
  ${V}

  /* Accessibility: default text contrast & focus */
  :host {
    color: var(--color-text);
    /* Spacing variables */
    --spacing-xs: 4px;
    --spacing-sm: 8px;
    --spacing-md: 16px;
    --spacing-lg: 24px;
    --spacing-xl: 32px;
    --spacing-2xl: 48px;
    --spacing-3xl: 64px;
  }

  a, button {
    color: var(--color-text);
    text-decoration: none;
  }

  a:focus-visible,
  button:focus-visible,
  [role="button"]:focus-visible {
    outline: 2px solid var(--color-border-focus);
    outline-offset: 2px;
    box-shadow: 0 0 0 4px var(--color-border-focus-ring, rgba(255, 87, 34, 0.1));
  }

  /* Low-contrast text helper classes */
  .text-secondary {
    color: var(--color-text-secondary);
  }
`;var H=Object.defineProperty,W=Object.getOwnPropertyDescriptor,b=(e,o,s,n)=>{for(var t=n>1?void 0:n?W(o,s):o,r=e.length-1,i;r>=0;r--)(i=e[r])&&(t=(n?i(o,s,t):i(t))||t);return n&&t&&H(o,s,t),t};let p=class extends m{constructor(){super(...arguments),this.href="",this.external=!1,this.type="primary",this.icon="",this.iconWidth="",this.iconHeight="",this.active=!1,this.ariaLabel=""}render(){const e=`button button-${this.type} ${this.active?"button-active":""}`;return this.href?a`
      <a
        href="${this.href}"
        class="${e}"
        target="${this.external?"_blank":"_self"}"
        rel="${this.external?"noopener noreferrer":C}"
        aria-label="${this.ariaLabel||C}"
      >
        <slot></slot>
        ${this.icon?a`<span class="icon" aria-hidden="true">
              <img
                class="img"
                src="${this.icon}"
                width="${this.iconWidth}"
                height="${this.iconHeight}"
                alt=""
              />
            </span>`:C}
      </a>
    `:a`
        <span class="${e}" role="button" aria-label="${this.ariaLabel||C}">
          <slot></slot>
          ${this.icon?a`<span class="icon" aria-hidden="true">
                <img
                  class="img"
                  src="${this.icon}"
                  width="${this.iconWidth}"
                  height="${this.iconHeight}"
                  alt=""
                />
              </span>`:C}
        </span>
      `}};p.styles=[x,d`
      :host {
        display: inline-block;
        line-height: var(--height-ui);
        height: var(--height-ui);
        justify-content: center;
      }

      .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        padding: 0 2em;
        gap: 1em;
        font-family: var(--font-title), sans-serif;
        font-size: var(--font-size-secondary);
        letter-spacing: 1px;
        text-transform: uppercase;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
        color: var(--color-text-button);
        background: var(--color-bg-button);
      }

      /* Varianty tlačidiel */
      .button-primary {
        background: var(--color-bg-button);
        color: var(--color-text-button);
      }
      .button-primary.button-active,
      .button-primary:hover {
        background: var(--color-bg-button-hover);
      }

      .button-secondary {
        background: var(--color-bg-button-secondary);
        color: var(--color-text-button-secondary, var(--color-text));
      }
      .button-secondary.button-active,
      .button-secondary:hover {
        background: var(--color-bg-button-secondary-hover);
      }

      .button-ghost {
        background: transparent;
        color: var(--color-text-secondary);
      }
      .button-ghost.button-active,
      .button-ghost:hover {
        background: var(--color-bg-hover);
        color: var(--color-text);
      }

      .icon {
        display: flex;
        justify-content: center;
        align-items: center;
        aspect-ratio: 1/1;
        height: 32px;
        margin-right: -1em;
        user-select: none;
      }
      .icon img {
        height: var(--font-size-secondary);
        margin: -2px 0 0 0;
      }
    `];b([u({type:String})],p.prototype,"href",2);b([u({type:Boolean})],p.prototype,"external",2);b([u({type:String})],p.prototype,"type",2);b([u({type:String})],p.prototype,"icon",2);b([u({type:String})],p.prototype,"iconWidth",2);b([u({type:String})],p.prototype,"iconHeight",2);b([u({type:Boolean})],p.prototype,"active",2);b([u({type:String,attribute:"aria-label"})],p.prototype,"ariaLabel",2);p=b([g("boson-button")],p);var U=Object.getOwnPropertyDescriptor,G=(e,o,s,n)=>{for(var t=n>1?void 0:n?U(o,s):o,r=e.length-1,i;r>=0;r--)(i=e[r])&&(t=i(t)||t);return t};let L=class extends m{constructor(){super(),this.squares=[],this.squareData=[],this.animationIntervals=[],this.mouseX=0,this.mouseY=0,this.targetMouseX=0,this.targetMouseY=0,this.containerRect=null,this.animationFrame=null,this.isMouseOver=!1,this.resizeObserver=null,this.config={outerRadius:260,innerRadius:60,gapBetweenCircles:10,outerLayers:9,innerLayers:5,squareSize:4,squareSpacing:10,outerColor:"#8B8B8B",innerColor:"#F93904",baseSize:550,mouseRadius:150,animationStrength:25,smoothing:.5},this.squares=[],this.squareData=[],this.animationIntervals=[],this.mouseX=0,this.mouseY=0,this.targetMouseX=0,this.targetMouseY=0,this.containerRect=null,this.animationFrame=null,this.isMouseOver=!1,this.config={outerRadius:260,innerRadius:60,gapBetweenCircles:10,outerLayers:9,innerLayers:5,squareSize:4,squareSpacing:10,outerColor:"#8B8B8B",innerColor:"#F93904",baseSize:550,mouseRadius:150,animationStrength:25,smoothing:.5}}firstUpdated(e){this.createSquares(),this.startAnimations(),this.setupMouseTracking(),this.updateContainerRect(),this.loopAnimation(),this.resizeObserver=new ResizeObserver(()=>{this.updateContainerRect()});const o=this.shadowRoot?.querySelector(".dot-container");o&&this.resizeObserver.observe(o)}disconnectedCallback(){super.disconnectedCallback(),this.animationIntervals.forEach(e=>clearInterval(e)),this.removeMouseTracking(),this.animationFrame&&cancelAnimationFrame(this.animationFrame),this.resizeObserver&&this.resizeObserver.disconnect()}updateContainerRect(){const e=this.shadowRoot?.querySelector(".dot-container");e&&(this.containerRect=e.getBoundingClientRect())}setupMouseTracking(){const e=this.shadowRoot?.querySelector(".container");e&&(this.handleMouseMove=this.handleMouseMove.bind(this),this.handleMouseLeave=this.handleMouseLeave.bind(this),this.handleMouseEnter=this.handleMouseEnter.bind(this),e.addEventListener("mousemove",this.handleMouseMove),e.addEventListener("mouseleave",this.handleMouseLeave),e.addEventListener("mouseenter",this.handleMouseEnter))}removeMouseTracking(){const e=this.shadowRoot?.querySelector(".container");e&&(e.removeEventListener("mousemove",this.handleMouseMove),e.removeEventListener("mouseleave",this.handleMouseLeave),e.removeEventListener("mouseenter",this.handleMouseEnter))}handleMouseMove(e){this.containerRect||this.updateContainerRect(),this.containerRect&&(this.targetMouseX=e.clientX-this.containerRect.left,this.targetMouseY=e.clientY-this.containerRect.top+window.scrollY)}handleMouseEnter(e){this.isMouseOver=!0,this.containerRect||this.updateContainerRect()}handleMouseLeave(){this.isMouseOver=!1,this.mouseX=-100,this.mouseY=-100,this.updateSquarePositions()}loopAnimation(){window.scrollY<window.innerHeight&&(this.isMouseOver?(this.mouseX+=(this.targetMouseX-this.mouseX)*this.config.smoothing,this.mouseY+=(this.targetMouseY-this.mouseY)*this.config.smoothing,this.updateSquarePositions()):(this.mouseX=-1e3,this.mouseY=-1e3,this.resetSquaresToOriginal())),this.animationFrame=requestAnimationFrame(()=>this.loopAnimation())}resetSquaresToOriginal(){this.squareData.forEach((e,o)=>{const s=this.squares[o],t=s.style.transform.match(/calc\(-50% \+ ([-\d.]+)px\), calc\(-50% \+ ([-\d.]+)px\)/);if(t){const r=parseFloat(t[1])||0,i=parseFloat(t[2])||0,f=r*(1-this.config.smoothing),y=i*(1-this.config.smoothing);Math.abs(f)<.1&&Math.abs(y)<.1?s.style.transform="translate(-50%, -50%)":s.style.transform=`translate(calc(-50% + ${f}px), calc(-50% + ${y}px))`}})}updateSquarePositions(){const e=this.config.mouseRadius*this.config.mouseRadius;this.squareData.forEach((o,s)=>{const n=this.squares[s],t=o.originalX-this.mouseX,r=o.originalY-this.mouseY,i=t*t+r*r;if(i<e&&i>0){const f=Math.sqrt(i),y=(this.config.mouseRadius-f)/this.config.mouseRadius*this.config.animationStrength,z=.7/f,R=t*z,v=r*z,h=R*y,w=v*y;n.style.transform=`translate(calc(-50% + ${h}px), calc(-50% + ${w}px))`}else n.style.transform="translate(-50%, -50%)"})}createSquares(){const e=this.shadowRoot?.querySelector(".dot-container");if(!e)return;const o=e.getBoundingClientRect(),s=o.width/2,n=o.height/2,t=Math.min(o.width,o.height)/this.config.baseSize,r=this.config.squareSize*t,i=this.config.squareSpacing*t,f=this.config.outerRadius*t,y=f-(this.config.outerLayers-1)*i;for(let v=0;v<this.config.outerLayers;v++){const h=f-v*i,w=2*Math.PI*h,k=Math.floor(w/i);for(let c=0;c<k;c++){const q=c/k*Math.PI*2,$=s+Math.cos(q)*h,S=n+Math.sin(q)*h,l=document.createElement("div");l.className="square outer",l.style.left=`${$}px`,l.style.top=`${S}px`,l.style.width=`${r}px`,l.style.height=`${r}px`,l.style.transform="translate(-50%, -50%)",e.appendChild(l),this.squares.push(l),this.squareData.push({originalX:$,originalY:S,element:l})}}const z=y-this.config.gapBetweenCircles*t,R=Math.min(this.config.innerRadius*t,z);for(let v=0;v<this.config.innerLayers;v++){const h=R-v*i;if(h<=0)break;if(h<i){const c=document.createElement("div");c.className="square inner",c.style.left=`${s}px`,c.style.top=`${n}px`,c.style.width=`${r}px`,c.style.height=`${r}px`,c.style.transform="translate(-50%, -50%)",e.appendChild(c),this.squares.push(c),this.squareData.push({originalX:s,originalY:n,element:c});break}const w=2*Math.PI*h,k=Math.floor(w/i);for(let c=0;c<k;c++){const q=c/k*Math.PI*2,$=s+Math.cos(q)*h,S=n+Math.sin(q)*h,l=document.createElement("div");l.className="square inner",l.style.left=`${$}px`,l.style.top=`${S}px`,l.style.width=`${r}px`,l.style.height=`${r}px`,l.style.transform="translate(-50%, -50%)",e.appendChild(l),this.squares.push(l),this.squareData.push({originalX:$,originalY:S,element:l})}}}startAnimations(){this.squares.forEach(e=>{Math.random()>.96&&e.classList.add("dimmed");const o=window.setInterval(()=>{Math.random()>.3&&e.classList.toggle("dimmed")},500+Math.random()*3e3);this.animationIntervals.push(o)})}render(){return a`
            <div class="container">
                <div class="circle-wrapper">
                    <div class="dot-container"></div>
                </div>
            </div>
        `}};L.styles=[x,d`
        .container {
            width: 100%;
            height: 100%;
            min-width: 400px;
            min-height: 50px;
            max-width: 550px;
            max-height: 550px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .circle-wrapper {
            width: 100%;
            height: 100%;
            min-width: 50px;
            min-height: 50px;
            max-width: 100%;
            max-height: 100%;
            aspect-ratio: 1;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dot-container {
            width: 100%;
            height: 100%;
            min-width: 50px;
            min-height: 50px;
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .square {
            position: absolute;
            transition: opacity 0.5s ease;
            opacity: 1;
            will-change: transform;
        }

        .square.outer {
            background: #8B8B8B;
        }

        .square.inner {
            background: #F93904;
        }

        .square.dimmed {
            opacity: 0.1;
            border-radius: 50%;
        }

        @media (max-aspect-ratio: 1/1) {
            .circle-wrapper {
                width: 100%;
                height: auto;
            }
        }

        @media (min-aspect-ratio: 1/1) {
            .circle-wrapper {
                width: auto;
                height: 100%;
            }
        }
        @media (orientation: portrait) {
            .container {
                height: 90vw;
                width: 90vw;
                max-width: 400px;
                max-height: 400px;
            }
        }
    `];L=G([g("boson-logo")],L);var J=Object.getOwnPropertyDescriptor,K=(e,o,s,n)=>{for(var t=n>1?void 0:n?J(o,s):o,r=e.length-1,i;r>=0;r--)(i=e[r])&&(t=i(t)||t);return t};let I=class extends m{render(){return a`
      <nav class="breadcrumbs">
        <slot></slot>
      </nav>
    `}};I.styles=[x,d`
    :host {
      display: block;
      min-height: 94px;
      line-height: 94px;
      border-bottom: solid 1px var(--color-border);
    }

    .breadcrumbs {
      margin: 0 auto;
      width: var(--width-content);
      max-width: var(--width-max);
      display: flex;
      justify-content: flex-start;
    }

    ::slotted(.breadcrumb-item) {
      display: flex;
      align-items: center;
    }

    ::slotted(.breadcrumb-item:not(:last-child))::after {
      content: '/';
      color: var(--color-border);
      padding: 0 1em;
    }

    @media (max-width: 700px) {
      :host {
        display: none;
      }
    }
  `];I=K([g("boson-breadcrumbs")],I);var Q=Object.getOwnPropertyDescriptor,Z=(e,o,s,n)=>{for(var t=n>1?void 0:n?Q(o,s):o,r=e.length-1,i;r>=0;r--)(i=e[r])&&(t=i(t)||t);return t};let M=class extends m{constructor(){super(),this.isOpen=!1,this.expandedSections={},this.handleClickOutside=this.handleClickOutside.bind(this),this.handleEscape=this.handleEscape.bind(this)}connectedCallback(){super.connectedCallback(),document.addEventListener("click",this.handleClickOutside),document.addEventListener("keydown",this.handleEscape)}disconnectedCallback(){super.disconnectedCallback(),document.removeEventListener("click",this.handleClickOutside),document.removeEventListener("keydown",this.handleEscape),this.isOpen&&(document.body.style.overflow="")}handleClickOutside(e){this.isOpen&&!this.contains(e.target)&&(this.isOpen=!1,this.toggleBodyScroll())}handleEscape(e){this.isOpen&&e.key==="Escape"&&(this.isOpen=!1,this.toggleBodyScroll())}toggleMenu(){this.isOpen=!this.isOpen,this.toggleBodyScroll()}toggleBodyScroll(){this.isOpen?document.body.style.overflow="hidden":document.body.style.overflow=""}toggleSection(e){this.expandedSections={...this.expandedSections,[e]:!this.expandedSections[e]},this.requestUpdate()}render(){return a`
            <div class="menu-toggle" @click="${this.toggleMenu}">
                ${this.isOpen?a`<div class="close-icon"></div>`:a`<div class="menu-icon"></div>`}
            </div>

            <div class="menu-content ${this.isOpen?"open":""}">
                <div class="menu-inner">
                    <div class="menu-section collapsible">
                        <div class="menu-title clickable ${this.expandedSections.references?"expanded":""}"
                             @click="${()=>this.toggleSection("references")}">
                            <span>References</span>
                        </div>
                        <div class="collapsible-content ${this.expandedSections.references?"expanded":""}">
                            <slot name="references"></slot>
                        </div>
                    </div>
                    <div class="menu-section collapsible">
                        <div class="menu-title clickable ${this.expandedSections.blog?"expanded":""}"
                             @click="${()=>this.toggleSection("blog")}">
                            <span>Blog</span>
                        </div>
                        <div class="collapsible-content ${this.expandedSections.blog?"expanded":""}">
                            <slot name="blog"></slot>
                        </div>
                    </div>
                </div>
            </div>
        `}};M.properties={isOpen:{type:Boolean},expandedSections:{type:Object}};M.styles=[d`
        :host {
            display: none;
        }

        @media (orientation: portrait) {
            :host {
                align-self: stretch;
                display: flex;
            }
        }

        .menu-toggle {
            cursor: pointer;
            align-self: stretch;
            display: flex;
            align-items: center;
            justify-content: center;
            width: var(--header-height-scrolled);
        }

        .menu-content {
            position: absolute;
            top: calc(var(--header-height-scrolled) + 1px);
            left: 0;
            right: 0;
            background: var(--color-bg);
            border-bottom: 1px solid var(--color-border);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease, height 0.3s ease;
            max-height: 600px;
            height: 0;
            overflow-y: auto;
            interpolate-size: allow-keywords;
        }

        .menu-content.open {
            opacity: 1;
            pointer-events: all;
            height: auto;
        }

        .menu-icon , .close-icon {
            height: 24px;
            width: 24px;
            background-position: center;
        }

        .menu-icon {
            background-image: url("/images/icons/burger.svg");
        }
        .close-icon {
            background-image: url("/images/icons/burger-close.svg");
        }

        .menu-inner {
            padding: 2em;
        }
        ::slotted([slot="references"]), ::slotted([slot="blog"]) {
            display: flex;
            align-items: flex-start;
            flex-direction: column;
        }

        ::slotted(boson-button[slot="references"]), ::slotted(boson-button[slot="blog"]) {
            align-self: stretch;
            justify-content: flex-start;
        }

        .menu-section {
            margin-bottom: 0;
        }

        .menu-title.clickable {
            color: var(--color-text);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: color 0.2s ease;
            text-transform: uppercase;
        }

        .menu-title.clickable::after {
            content: '';
            height: 6px;
            width: 6px;
            border-top: 1px solid var(--color-text-button);
            border-right: 1px solid var(--color-text-button);
            transition: transform 0.3s ease;
            transform: rotate(-45deg);
        }

        .menu-title.clickable.expanded::after {
            transform: rotate(135deg);
        }

        .collapsible-content {
            display: flex;
            flex-direction: column;
            height: 0;
            overflow: hidden;
            transition: height 0.3s ease;
            interpolate-size: allow-keywords;
        }

        .collapsible-content.expanded {
            height: auto;
        }

        ::slotted(boson-button),
        ::slotted(a) {
            display: block !important;
            width: 100% !important;
            text-align: left !important;
            padding: 0.75em 0 !important;
            border: none !important;
            border-bottom: 1px solid var(--color-border) !important;
        }

        ::slotted(boson-button:last-child),
        ::slotted(a:last-child) {
            border-bottom: none !important;
        }

        .collapsible-content ::slotted(boson-button),
        .collapsible-content ::slotted(a) {
            padding-left: 1em !important;
        }
    `];M=Z([g("mobile-header-menu")],M);var ee=Object.getOwnPropertyDescriptor,te=(e,o,s,n)=>{for(var t=n>1?void 0:n?ee(o,s):o,r=e.length-1,i;r>=0;r--)(i=e[r])&&(t=i(t)||t);return t};let P=class extends m{constructor(){super(),this.content=[],this.openIndex=0}handleElementClick(e){this.openIndex=e}renderElement(e,o){const s=this.openIndex===o;return a`
            <div
                class="element ${s?"elementOpen":"elementClosed"}"
                @click=${()=>this.handleElementClick(o)}
            >
                <div class="elementContent">
                    ${s?a`
                        <div class="openTop">
                            <span class="number">0${o+1}</span>
                            <h4 class="headline">${e.headline}</h4>
                        </div>
                    `:a`
                        <div class="closedTop">
                            <span class="number">0${o+1}</span>
                        </div>
                    `}

                    ${s?a`
                        <div class="content">
                            <p class="text">${e.text}</p>
                        </div>
                    `:a`
                        <div class="collapsedContent">
                            <h4 class="closed-headline">${e.headline}</h4>
                            <img src="/images/icons/plus.svg" alt="plus"/>
                        </div>
                    `}
                </div>
            </div>
        `}render(){return a`
            <div class="accordion">
                ${this.content.map((e,o)=>this.renderElement(e,o))}
            </div>
        `}};P.properties={content:{type:Array},openIndex:{type:Number}};P.styles=[x,d`
        .accordion {
            display: flex;
            flex: 1;
            min-height: 400px;
        }
        .headline {
            text-transform: uppercase;
        }

        .element {
            border-right: 1px solid var(--color-border);
            transition-duration: 0.3s;
        }

        .elementOpen {
            flex: 1;
        }

        .elementClosed {
            width: 5em;
            cursor: pointer;
        }

        .elementClosed:hover {
            background: var(--color-bg-hover);
        }

        .elementOpen .elementContent {
            padding: 2em 3em;
        }

        .elementClosed .elementContent {
            padding: 2em 0;
        }

        .elementContent {
            box-sizing: border-box !important;
            transition-duration: 0.2s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .openTop {
            display: flex;
            align-items: center;
            gap: 3em;
            animation: appear 1s forwards;
            height: 60px;
        }

        .openTop > .headline {
            margin: 0;
        }

        .closedTop {
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .content {
            flex: 1;
            width: 700px;
            animation: appear 1s forwards;
            margin-left: 4.5em;
            display: flex;
            align-items: flex-end;
            line-height: 1.75;
        }

        .number {
            color: var(--color-text-brand);
            transition-duration: 0.2s;
            font-size: var(--font-size-h4);
            font-family: var(--font-mono), monospace;
            font-weight: 600;
        }

        .elementClosed .elementContent .closedTop .number {
            color: var(--color-text-secondary);
        }

        .elementClosed:hover .elementContent .closedTop .number {
            color: var(--color-text-brand);
        }

        .collapsedContent {
            flex: 1;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            filter: grayscale(100%);
            transition-duration: 0.2s;
        }

        .elementClosed:hover .elementContent .collapsedContent {
            filter: grayscale(0%);
        }

        @keyframes appear {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        .closed-headline {
            display: none;
        }
        @media (orientation: portrait) {
            .accordion {
                flex-direction: column;
            }
            .content {
                width: unset;
            }
            .elementClosed {
                width: unset;
            }
            .elementOpen .elementContent {
                padding: 0;
            }
            .elementOpen .openTop {
                padding: 0 1em;
                height: unset;
                gap: 1em;
            }
            .elementOpen .openTop > .headline {
                margin: 0.25em 0;
            }

            .elementClosed .elementContent {
                padding: 0;
                flex-direction: row;
                margin: 0 1em;
                gap: 1em;
            }
            .collapsedContent {
                align-items: center;
                justify-content: space-between;
            }
            .closedTop {
                align-self: center;
            }
            .closed-headline {
                display: flex;
                text-transform: uppercase;
                margin: 0.8em 0 0.7em 0;
                color: var(--color-text-secondary);
            }
            .accordion > .element {
                border-right: unset;
                border-bottom: 1px solid var(--color-border);
            }
            .accordion > .element:nth-last-child(1) {
                border: none !important;
            }
        }
    `];P=te([g("horizontal-accordion")],P);var oe=Object.defineProperty,re=Object.getOwnPropertyDescriptor,Y=(e,o,s,n)=>{for(var t=n>1?void 0:n?re(o,s):o,r=e.length-1,i;r>=0;r--)(i=e[r])&&(t=(n?i(o,s,t):i(t))||t);return n&&t&&oe(o,s,t),t};let O=class extends m{constructor(){super(...arguments),this.currentIndex=0,this.slidesPerView=1,this.autoplayInterval=null,this.slides=[]}connectedCallback(){super.connectedCallback(),this.updateSlidesPerView(),this.startAutoplay(),window.addEventListener("resize",this.updateSlidesPerView.bind(this))}disconnectedCallback(){super.disconnectedCallback(),this.stopAutoplay(),window.removeEventListener("resize",this.updateSlidesPerView.bind(this))}updateSlidesPerView(){this.slidesPerView=window.innerWidth>=768?3:1,this.requestUpdate()}startAutoplay(){this.stopAutoplay(),this.autoplayInterval=window.setInterval(()=>{this.slideNext()},3e3)}stopAutoplay(){this.autoplayInterval&&(clearInterval(this.autoplayInterval),this.autoplayInterval=null)}slidePrev(){this.currentIndex=this.currentIndex<=0?this.slides.length-this.slidesPerView:this.currentIndex-1,this.requestUpdate()}slideNext(){this.currentIndex=this.currentIndex>=this.slides.length-this.slidesPerView?0:this.currentIndex+1,this.requestUpdate()}getTransform(){const e=100/this.slidesPerView;return`translateX(-${this.currentIndex*e}%)`}renderSlide(e,o){return a`
            <div class="slideWrapper">
                <div class="slide">
                    <img class="quote" src="/images/icons/quote.svg" alt="quote"/>
                    <p class="comment">"${e.comment}"</p>
                    <div class="bottom">
                        <img class="pfp" src="${e.pfp}" alt="${e.name}"/>
                        <div class="info">
                            <span class="name">${e.name}</span>
                            <p class="role">${e.role}</p>
                        </div>
                    </div>
                </div>
            </div>
        `}render(){return a`
            <div class="container" @mouseenter="${this.stopAutoplay}" @mouseleave="${this.startAutoplay}">
                <button class="sliderButton" @click=${this.slidePrev}>
                    <div class="dots">
                        <dots-container></dots-container>
                    </div>
                    <img src="/images/icons/red_arrow_left.svg" alt="prev"/>
                    <span>Prev</span>
                </button>
                <div class="sliderContent">
                    <div class="slidesWrapper" style="transform: ${this.getTransform()}">
                        ${this.slides.map((e,o)=>this.renderSlide(e,o))}
                    </div>
                </div>
                <button class="sliderButton" @click=${this.slideNext}>
                    <div class="dots">
                        <dots-container></dots-container>
                    </div>
                    <img src="/images/icons/red_arrow_right.svg" alt="next"/>
                    <span>Next</span>
                </button>
                <div class="mobile-buttons">
                    <button class="sliderButton" @click=${this.slidePrev}>
                        <img src="/images/icons/red_arrow_left.svg" alt="prev"/>
                        <span>Prev</span>
                    </button>
                    <div class="sep"></div>
                    <button class="sliderButton" @click=${this.slideNext}>
                        <img src="/images/icons/red_arrow_right.svg" alt="next"/>
                        <span>Next</span>
                    </button>
                </div>
            </div>
        `}};O.styles=[x,d`
        .container {
            display: flex;
            max-width: 100vw;
            overflow: hidden;
            border-top: 1px solid var(--color-border);
            border-bottom: 1px solid var(--color-border);
            background: var(--color-bg);
        }

        .sliderContent {
            width: calc(100vw - 240px - 12px);
            overflow: hidden;
        }

        // ... zvyšok CSS ...
    `];Y([u({type:Number})],O.prototype,"currentIndex",2);Y([u({type:Number})],O.prototype,"slidesPerView",2);O=Y([g("slider-component")],O);var ie=Object.getOwnPropertyDescriptor,ne=(e,o,s,n)=>{for(var t=n>1?void 0:n?ie(o,s):o,r=e.length-1,i;r>=0;r--)(i=e[r])&&(t=i(t)||t);return t};let j=class extends m{constructor(){super(),this.action="/",this.query=""}render(){return a`
            <form method="get" action="${this.action}">
                <input
                    type="search"
                    name="q"
                    value="${this.query}"
                    placeholder="Search"
                    aria-label="Search"
                />
            </form>
        `}};j.properties={action:{type:String},query:{type:String}};j.styles=[x,d`
        :host {
            margin: 2em 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        form {
            display: block;
            width: 100%;
        }

        input {
            display: block;
            line-height: var(--height-ui);
            height: var(--height-ui);
            font-family: var(--font-title), sans-serif;
            font-size: var(--font-size-secondary);
            letter-spacing: 1px;
            color: var(--color-text);
            transition-duration: .1s;
            background: var(--color-bg);
            text-transform: uppercase;
            padding: 0 2em;
            white-space: nowrap;
            text-decoration: none;
            width: 100%;
            outline: none;
            border: solid 1px var(--color-bg-hover);
        }

        input:hover {
            border: solid 1px var(--color-text-brand-hover);
        }

        input:focus {
            border: solid 1px var(--color-text-brand);
        }
    `];j=ne([g("boson-search-input")],j);var se=Object.getOwnPropertyDescriptor,ae=(e,o,s,n)=>{for(var t=n>1?void 0:n?se(o,s):o,r=e.length-1,i;r>=0;r--)(i=e[r])&&(t=i(t)||t);return t};let B=class extends m{render(){return a`
      <div class="container">
        <img class="img" src="/images/icons/subtitle.svg" alt="subtitle"/>

        <h6 class="name">
          <slot></slot>
        </h6>
      </div>
    `}};B.styles=[x,d`
    .container {
      display: flex;
      gap: 1em;
      justify-content: center;
      align-items: center;
    }

    .img {
      height: 16px;
      user-select: none;
    }
  `];B=ae([g("boson-subtitle")],B);var le=Object.getOwnPropertyDescriptor,ce=(e,o,s,n)=>{for(var t=n>1?void 0:n?le(o,s):o,r=e.length-1,i;r>=0;r--)(i=e[r])&&(t=i(t)||t);return t};let D=class extends m{render(){return a`
      <hgroup class="page-title">
        <span class="page-title-container">
          <slot></slot>
        </span>
      </hgroup>
    `}};D.styles=[x,d`
    .page-title {
      background: url(/images/icons/dots.svg) center center repeat;
      border-bottom: solid 1px var(--color-border);
    }

    .page-title-container {
      width: var(--width-content);
      max-width: var(--width-max);
      margin: 0 auto;
      display: flex;
      flex-direction: row;
      justify-content: flex-start;
      align-items: center;
    }

    ::slotted(*) {
      display: inline-block;
      background: var(--color-bg);
      margin: 0 !important;
      padding: .5em 1em !important;
    }
  `];D=ce([g("boson-page-title")],D);export{x as s};
