import{i as c,a as p,b as l,t as h,n as a,A as v}from"./vendor-lit-CeIZiaZY.js";import{s as m}from"./sections-2UPvd2nH.js";var P=Object.getOwnPropertyDescriptor,S=(e,o,n,i)=>{for(var t=i>1?void 0:i?P(o,n):o,r=e.length-1,s;r>=0;r--)(s=e[r])&&(t=s(t)||t);return t};let w=class extends p{render(){return l`
      <nav class="breadcrumbs">
        <slot></slot>
      </nav>
    `}};w.styles=[m,c`
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
  `];w=S([h("boson-breadcrumbs")],w);var j=Object.getOwnPropertyDescriptor,B=(e,o,n,i)=>{for(var t=i>1?void 0:i?j(o,n):o,r=e.length-1,s;r>=0;r--)(s=e[r])&&(t=s(t)||t);return t};let f=class extends p{constructor(){super(),this.isOpen=!1,this.expandedSections={},this.handleClickOutside=this.handleClickOutside.bind(this),this.handleEscape=this.handleEscape.bind(this)}connectedCallback(){super.connectedCallback(),document.addEventListener("click",this.handleClickOutside),document.addEventListener("keydown",this.handleEscape)}disconnectedCallback(){super.disconnectedCallback(),document.removeEventListener("click",this.handleClickOutside),document.removeEventListener("keydown",this.handleEscape),this.isOpen&&(document.body.style.overflow="")}handleClickOutside(e){this.isOpen&&!this.contains(e.target)&&(this.isOpen=!1,this.toggleBodyScroll())}handleEscape(e){this.isOpen&&e.key==="Escape"&&(this.isOpen=!1,this.toggleBodyScroll())}toggleMenu(){this.isOpen=!this.isOpen,this.toggleBodyScroll()}toggleBodyScroll(){this.isOpen?document.body.style.overflow="hidden":document.body.style.overflow=""}toggleSection(e){this.expandedSections={...this.expandedSections,[e]:!this.expandedSections[e]},this.requestUpdate()}render(){return l`
            <div class="menu-toggle" @click="${this.toggleMenu}">
                ${this.isOpen?l`<div class="close-icon"></div>`:l`<div class="menu-icon"></div>`}
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
        `}};f.properties={isOpen:{type:Boolean},expandedSections:{type:Object}};f.styles=[c`
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
    `];f=B([h("mobile-header-menu")],f);var I=Object.defineProperty,D=Object.getOwnPropertyDescriptor,g=(e,o,n,i)=>{for(var t=i>1?void 0:i?D(o,n):o,r=e.length-1,s;r>=0;r--)(s=e[r])&&(t=(i?s(o,n,t):s(t))||t);return i&&t&&I(o,n,t),t};let d=class extends p{constructor(){super(...arguments),this.href="",this.external=!1,this.type="primary",this.icon="",this.iconWidth="",this.iconHeight="",this.active=!1,this.ariaLabel=""}render(){const e=`button button-${this.type} ${this.active?"button-active":""}`;return this.href?l`
      <a
        href="${this.href}"
        class="${e}"
        target="${this.external?"_blank":"_self"}"
        rel="${this.external?"noopener noreferrer":v}"
        aria-label="${this.ariaLabel||v}"
      >
        <slot></slot>
        ${this.icon?l`<span class="icon" aria-hidden="true">
              <img
                class="img"
                src="${this.icon}"
                width="${this.iconWidth}"
                height="${this.iconHeight}"
                alt=""
              />
            </span>`:v}
      </a>
    `:l`
        <span class="${e}" role="button" aria-label="${this.ariaLabel||v}">
          <slot></slot>
          ${this.icon?l`<span class="icon" aria-hidden="true">
                <img
                  class="img"
                  src="${this.icon}"
                  width="${this.iconWidth}"
                  height="${this.iconHeight}"
                  alt=""
                />
              </span>`:v}
        </span>
      `}};d.styles=[m,c`
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
    `];g([a({type:String})],d.prototype,"href",2);g([a({type:Boolean})],d.prototype,"external",2);g([a({type:String})],d.prototype,"type",2);g([a({type:String})],d.prototype,"icon",2);g([a({type:String})],d.prototype,"iconWidth",2);g([a({type:String})],d.prototype,"iconHeight",2);g([a({type:Boolean})],d.prototype,"active",2);g([a({type:String,attribute:"aria-label"})],d.prototype,"ariaLabel",2);d=g([h("boson-button")],d);var E=Object.getOwnPropertyDescriptor,z=(e,o,n,i)=>{for(var t=i>1?void 0:i?E(o,n):o,r=e.length-1,s;r>=0;r--)(s=e[r])&&(t=s(t)||t);return t};let $=class extends p{render(){return l`
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
    `}};$.styles=[c`
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
  `];$=z([h("boson-footer")],$);var H=Object.defineProperty,T=Object.getOwnPropertyDescriptor,_=(e,o,n,i)=>{for(var t=i>1?void 0:i?T(o,n):o,r=e.length-1,s;r>=0;r--)(s=e[r])&&(t=(i?s(o,n,t):s(t))||t);return i&&t&&H(o,n,t),t};let u=class extends p{constructor(){super(),this.isScrolled=!1,this.isHomePage=!1,this.handleScroll=this.handleScroll.bind(this)}connectedCallback(){super.connectedCallback(),window.addEventListener("scroll",this.handleScroll),this.detectHomePage(),this.handleScroll()}disconnectedCallback(){super.disconnectedCallback(),window.removeEventListener("scroll",this.handleScroll)}detectHomePage(){const e=window.location.pathname==="/"||window.location.pathname==="/home";this.isHomePage=e}handleScroll(){const e=window.pageYOffset||document.documentElement.scrollTop;this.isScrolled=e>0}render(){const e=[!this.isHomePage&&this.isScrolled?"scrolled":"",this.isHomePage?"home-page":""].filter(Boolean).join(" ");return l`
      <header class="${e}" hx-boost="true">
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
    `}};u.styles=[c`
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

      /* Home page špecifické štýly */
      header.home-page {
        background: rgba(13, 17, 25, 0.8);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      }

      header.home-page .logo img {
        filter: brightness(0) invert(1);
      }

      header.home-page boson-button {
        color: rgba(255, 255, 255, 0.8);
      }

      header.home-page boson-button:hover {
        color: white;
      }

      /* Schovať search na home page */
      header.home-page boson-search-input {
        display: none;
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
    `];_([a({type:Boolean})],u.prototype,"isScrolled",2);_([a({type:Boolean})],u.prototype,"isHomePage",2);u=_([h("resp-header")],u);var L=Object.getOwnPropertyDescriptor,q=(e,o,n,i)=>{for(var t=i>1?void 0:i?L(o,n):o,r=e.length-1,s;r>=0;r--)(s=e[r])&&(t=s(t)||t);return t};let x=class extends p{constructor(){super(),this.content=[],this.openIndex=0}handleElementClick(e){this.openIndex=e}renderElement(e,o){const n=this.openIndex===o;return l`
            <div
                class="element ${n?"elementOpen":"elementClosed"}"
                @click=${()=>this.handleElementClick(o)}
            >
                <div class="elementContent">
                    ${n?l`
                        <div class="openTop">
                            <span class="number">0${o+1}</span>
                            <h4 class="headline">${e.headline}</h4>
                        </div>
                    `:l`
                        <div class="closedTop">
                            <span class="number">0${o+1}</span>
                        </div>
                    `}

                    ${n?l`
                        <div class="content">
                            <p class="text">${e.text}</p>
                        </div>
                    `:l`
                        <div class="collapsedContent">
                            <h4 class="closed-headline">${e.headline}</h4>
                            <img src="/images/icons/plus.svg" alt="plus"/>
                        </div>
                    `}
                </div>
            </div>
        `}render(){return l`
            <div class="accordion">
                ${this.content.map((e,o)=>this.renderElement(e,o))}
            </div>
        `}};x.properties={content:{type:Array},openIndex:{type:Number}};x.styles=[m,c`
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
    `];x=q([h("horizontal-accordion")],x);var V=Object.defineProperty,A=Object.getOwnPropertyDescriptor,C=(e,o,n,i)=>{for(var t=i>1?void 0:i?A(o,n):o,r=e.length-1,s;r>=0;r--)(s=e[r])&&(t=(i?s(o,n,t):s(t))||t);return i&&t&&V(o,n,t),t};let b=class extends p{constructor(){super(...arguments),this.currentIndex=0,this.slidesPerView=1,this.autoplayInterval=null,this.slides=[]}connectedCallback(){super.connectedCallback(),this.updateSlidesPerView(),this.startAutoplay(),window.addEventListener("resize",this.updateSlidesPerView.bind(this))}disconnectedCallback(){super.disconnectedCallback(),this.stopAutoplay(),window.removeEventListener("resize",this.updateSlidesPerView.bind(this))}updateSlidesPerView(){this.slidesPerView=window.innerWidth>=768?3:1,this.requestUpdate()}startAutoplay(){this.stopAutoplay(),this.autoplayInterval=window.setInterval(()=>{this.slideNext()},3e3)}stopAutoplay(){this.autoplayInterval&&(clearInterval(this.autoplayInterval),this.autoplayInterval=null)}slidePrev(){this.currentIndex=this.currentIndex<=0?this.slides.length-this.slidesPerView:this.currentIndex-1,this.requestUpdate()}slideNext(){this.currentIndex=this.currentIndex>=this.slides.length-this.slidesPerView?0:this.currentIndex+1,this.requestUpdate()}getTransform(){const e=100/this.slidesPerView;return`translateX(-${this.currentIndex*e}%)`}renderSlide(e,o){return l`
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
        `}render(){return l`
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
        `}};b.styles=[m,c`
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
    `];C([a({type:Number})],b.prototype,"currentIndex",2);C([a({type:Number})],b.prototype,"slidesPerView",2);b=C([h("slider-component")],b);var N=Object.getOwnPropertyDescriptor,W=(e,o,n,i)=>{for(var t=i>1?void 0:i?N(o,n):o,r=e.length-1,s;r>=0;r--)(s=e[r])&&(t=s(t)||t);return t};let y=class extends p{constructor(){super(),this.action="/",this.query=""}render(){return l`
            <form method="get" action="${this.action}">
                <input
                    type="search"
                    name="q"
                    value="${this.query}"
                    placeholder="Search"
                    aria-label="Search"
                />
            </form>
        `}};y.properties={action:{type:String},query:{type:String}};y.styles=[m,c`
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
    `];y=W([h("boson-search-input")],y);var M=Object.getOwnPropertyDescriptor,U=(e,o,n,i)=>{for(var t=i>1?void 0:i?M(o,n):o,r=e.length-1,s;r>=0;r--)(s=e[r])&&(t=s(t)||t);return t};let k=class extends p{render(){return l`
      <div class="container">
        <img class="img" src="/images/icons/subtitle.svg" alt="subtitle"/>

        <h6 class="name">
          <slot></slot>
        </h6>
      </div>
    `}};k.styles=[m,c`
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
  `];k=U([h("boson-subtitle")],k);var R=Object.getOwnPropertyDescriptor,F=(e,o,n,i)=>{for(var t=i>1?void 0:i?R(o,n):o,r=e.length-1,s;r>=0;r--)(s=e[r])&&(t=s(t)||t);return t};let O=class extends p{render(){return l`
      <hgroup class="page-title">
        <span class="page-title-container">
          <slot></slot>
        </span>
      </hgroup>
    `}};O.styles=[m,c`
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
  `];O=F([h("boson-page-title")],O);
