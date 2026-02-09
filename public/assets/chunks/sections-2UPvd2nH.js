import{i as d,n as l,a as h,b as a,t as m,r as c,e as X,o as W,c as K}from"./vendor-lit-CeIZiaZY.js";const U=d`
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
`,v=d`
  ${U}

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
`;var G=Object.defineProperty,V=Object.getOwnPropertyDescriptor,H=(e,i,r,o)=>{for(var t=o>1?void 0:o?V(i,r):i,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=(o?s(i,r,t):s(t))||t);return o&&t&&G(i,r,t),t};let C=class extends h{constructor(){super(...arguments),this.type="horizontal"}render(){return a`
      <section class="container container-${this.type}">
        <hgroup class="segment-title">
          <div class="segment-subtitle">
            <svg width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M4.20167 0L1.03888 14H0L3.15125 0H4.20167Z" />
              <path d="M12 0L8.8372 14H7.79833L10.9496 0H12Z" />
            </svg>

            <h3 class="segment-name">
              <slot name="section"></slot>
            </h3>
          </div>

          <h4 class="title">
            <slot name="title"></slot>
          </h4>
        </hgroup>

        <aside class="segment-content">
          <slot></slot>
        </aside>
      </section>
    `}};C.styles=[v,d`
      .container {
        display: flex;
        flex-direction: row;
        margin: var(--landing-layout-gap) auto 0 auto;
        gap: 3em;
        max-width: min(var(--width-max), 90vw);
      }

      .segment-title {
        display: flex;
        flex-direction: column;
        flex: 3;
        align-items: flex-start;
        gap: 2em;
      }

      .segment-content {
        flex: 2;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        color: var(--color-text-secondary);
      }

      .segment-title .segment-subtitle {
        display: flex;
        gap: 1em;
        justify-content: center;
        align-items: center;
      }

      .segment-title .segment-subtitle .segment-name {
        font-size: var(--font-size-secondary);
        margin: 0;
        text-transform: uppercase;
        font-weight: 400;
      }

      .segment-title .segment-subtitle svg {
        user-select: none;
      }

      .segment-title .segment-subtitle path {
        fill: var(--color-text-brand);
      }

      ::slotted(.anchor) {
        position: relative;
        top: -250px;
      }

      ::slotted(boson-button) {
        margin-top: 1em;
      }

      ::slotted(ul) {
        list-style-image: url(/images/icons/check.svg);
      }

      /** VERTICAL TYPE */

      .container.container-vertical {
        flex-direction: column;
      }

      /** CENTER TYPE */

      .container.container-center {
        align-items: center;
        flex-direction: column;
      }

      .container.container-center ::slotted(span),
      .container.container-center .title,
      .container.container-center .segment-title {
        margin: 0;
        text-align: center;
        align-items: center;
      }
      @media (orientation: portrait) {
        .container {
          flex-direction: column;
        }
        .title {
          margin: 0;
        }
        .segment-title .segment-subtitle .segment-name {
          font-size: var(--font-size-h5);
          margin: 0;
        }
        .segment-title .segment-subtitle svg {
          height: 16px;
          width: 16px;
        }
      }
    `];H([l({type:String})],C.prototype,"type",2);C=H([m("segment-section")],C);var J=Object.getOwnPropertyDescriptor,Z=(e,i,r,o)=>{for(var t=o>1?void 0:o?J(i,r):i,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=s(t)||t);return t};let F=class extends h{render(){return a`
            <section class="container">
                <div class="wrapper">
                    <div class="text">
                        <slot></slot>
                    </div>

                    <slot name="footer"></slot>
                </div>
            </section>
        `}};F.styles=[v,d`
        .container {
            padding-bottom: 8em;
            background-size: 900px 900px;
            background: url("/images/hero.svg") no-repeat 115% 0;
        }

        .wrapper {
            width: var(--width-content);
            max-width: var(--width-max);
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 3em;
            align-items: flex-start;
        }

        ::slotted(.red) {
            color: var(--color-text-brand) !important;
        }

        @media (orientation: portrait) {
            .container {
                padding: 5em 1em;
                background: url("/images/hero.svg") no-repeat 40vw 27vh;
            }
        }
    `];F=Z([m("call-to-action-section")],F);var Q=Object.getOwnPropertyDescriptor,ee=(e,i,r,o)=>{for(var t=o>1?void 0:o?Q(i,r):i,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=s(t)||t);return t};let j=class extends h{render(){return a`
            <section class="container">
                <div class="top">
                    <div class="text">
                        <div class="headlines">
                            <hgroup>
                                <slot name="title"></slot>
                            </hgroup>
                        </div>

                        <p class="description">
                            <slot name="description"></slot>
                        </p>

                        <div class="buttons">
                            <slot name="buttons"></slot>
                        </div>
                    </div>

                    <div class="img">
                        <div class="logo-container">
                            <boson-logo size="large"> SDFSD SD S</boson-logo>
                        </div>
                    </div>
                </div>

                <aside class="bottom">
                    <a href="#nativeness" class="discover">
                        <span class="discover-container">
                            <span class="discover-text">
                                <slot name="discovery"></slot>
                            </span>

                            <img class="discover-icon"
                                 src="/images/icons/arrow_down.svg" alt="down arrow"/>
                        </span>
                    </a>
                </aside>
            </section>
        `}};j.styles=[v,d`
        .container {
            display: flex;
            flex-direction: column;
            margin: 0 auto;
            min-height: calc(100vh - 100px);
        }

        .top {
            display: flex;
            flex-direction: row;
            align-items: center;
            flex: 1;
            gap: 2em;
            justify-content: space-between;
            margin: 0 auto;
            padding: 3em 0;
            max-width: var(--width-max);
            width: var(--width-content);
        }

        .white {
            color: var(--color-text);
        }

        .text {
            flex: 3;
            display: flex;
            flex-direction: column;
            gap: 3em;
        }

        .img {
            flex: 2;
        }

        .headlines {
            line-height: 1.1;
        }

        .headlines ::slotted(h1),
        .headlines ::slotted(h2) {
            margin: 0 !important;
            font-size: var(--font-size-h1) !important;
        }

        .headlines ::slotted(h1) {
            color: var(--color-text-brand) !important;
        }

        .description {
            width: 80%;
            color: var(--color-text-secondary);
        }

        .buttons {
            display: flex;
            flex-direction: row;
            gap: 3em;
        }

        .bottom {
            display: flex;
            align-items: center;
            border-top: 1px solid var(--color-border);
            text-transform: uppercase;
            width: 100%;
        }

        .bottom .discover {
            width: 100%;
            transition-duration: 0.2s;
            font-family: var(--font-title), sans-serif;
            font-size: var(--font-size-secondary);
            letter-spacing: .1em;
            text-decoration: none;
        }

        /* ACCESSIBILITY - Focus indicator for discover link */
        .bottom .discover:focus-visible {
            outline: 2px solid var(--color-border-focus);
            outline-offset: 2px;
            box-shadow: 0 0 0 4px var(--color-border-focus-ring, rgba(255, 87, 34, 0.1));
        }

        .bottom .discover-container {
            transition-duration: 0.2s;
            max-width: var(--width-max);
            width: var(--width-content);
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 3em 0;
        }

        .bottom .discover-icon {
            user-select: none;
        }

        .bottom .discover:hover {
            background-color: var(--color-bg-hover);
        }

        .bottom .discover:hover .discover-container {
            padding: 3em 2em;
        }

        .logo-container {
            display: flex;
            aspect-ratio: 1/1;
        }

        @media (orientation: portrait) {
            .top {
                flex-direction: column;
                padding: 5em 0;
            }

            .text {
                margin: 0 1em;
            }

            .buttons {
                flex-direction: column;
                align-items: flex-start;
                gap: 1em;
            }

            .img {
                max-width: 90vw;
            }

            .bottom {
                padding: 3em 1em;
            }
            .logo-container {
                width: 90vw;
                height: 90vw;
                align-items: center;
                justify-content: center;
            }
        }
    `];j=ee([m("hero-section")],j);var te=Object.getOwnPropertyDescriptor,ie=(e,i,r,o)=>{for(var t=o>1?void 0:o?te(i,r):i,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=s(t)||t);return t};let B=class extends h{get content(){return[{headline:"Saucer: The Core of Performance",text:"At the heart of Boson PHP is saucer, a fast cross-platform C++ library. It allows us to create applications with minimal size and resource consumption, significantly outperforming Electron in terms of performance."},{headline:"Direct OS API calls",text:"Instead of emulating behavior through multiple external layers like a browser, server, and sockets, we use direct access to the operating system API, just like any existing system language does."},{headline:"On the edge of PHP",text:"Boson is built on the basis of advanced architectural approaches and functionality provided by the most modern versions of PHP. No outdated approaches of large frameworks for the sake of backward compatibility."},{headline:"Kernel Optimizations",text:"The kernel is written in such a way as to provide maximum performance without limitations in functionality. Numerous PHP OPCode and JIT optimizations ensure that there are no dubious or slow solutions."},{headline:"Fiber-Based Life Cycle",text:"Using Revolt EventLoop and painless cooperative multitasking ensures high performance and ease of use. Why wait? Use the green threads today!"}]}render(){return a`
            <section class="container">
                <div class="content">
                    <div class="dots">
                        <dots-container></dots-container>
                    </div>
                    <div class="inner">
                        <horizontal-accordion .content=${this.content}></horizontal-accordion>
                    </div>
                    <div class="dots">
                        <dots-container></dots-container>
                    </div>
                </div>
            </section>
        `}};B.styles=[v,d`
        .container {
            display: flex;
            flex-direction: column;
            gap: 4em;
        }

        .content {
            display: flex;
            padding: 1px 0;
            border-bottom: 1px solid var(--color-border);
            border-top: 1px solid var(--color-border);
        }

        .dots {
            min-width: 120px;
        }

        .content .dots:nth-child(1) {
            border-right: 1px solid var(--color-border);
        }

        .inner {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        @media (orientation: portrait) {
            .dots {
                display: none;
            }
        }
    `];B=ie([m("how-it-works-section")],B);var oe=Object.getOwnPropertyDescriptor,re=(e,i,r,o)=>{for(var t=o>1?void 0:o?oe(i,r):i,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=s(t)||t);return t};let O=class extends h{get elements(){return[{headline:"Your own protocols",text:`You can intercept any request and process it without raising
                  the HTTP server. After all, a request is just a client event.
                  In this case, you do not necessarily need to use the "http"
                  or "https" protocol, create your own, to which your own
                  application will respond.`,icon:"rocket"},{headline:"Real-time client information",text:`You can instantly get information from the client directly
                  from PHP code without any layers on JavaScript. Want to get
                  information about the scroll area? No problem! Want
                  information about all the DOM elements? One line of PHP code!`,icon:"clients"},{headline:"You don't need React or Vue",text:`You don't need javascript frameworks when you can
                  do all this with PHP code.`,icon:"case"},{headline:"PHP functions in HTML",text:`You don't need JavaScript when you can specify which
                  PHP function to call directly from HTML`,icon:"convenient"}]}renderElement(e){return a`
            <div class="element">
                <div class="top">
                    <img class="icon" src="/images/icons/${e.icon}.svg" alt="${e.headline}"/>
                    <h5 class="name">${e.headline}</h5>
                </div>
                <p class="text">${e.text}</p>
            </div>
        `}render(){return a`
            <section class="container">
                <div class="left">
                    <div class="wrapper">
                        <slot></slot>
                    </div>
                </div>
                <div class="right">
                    ${this.elements.map(e=>this.renderElement(e))}
                </div>
            </section>
        `}};O.styles=[v,d`
        .container {
            display: flex;
            justify-content: center;
            position: relative;
            border-top: 1px solid var(--color-border);
        }

        .left {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-self: stretch;
            position: relative;
            border-right: 1px solid var(--color-border);
            border-bottom: 1px solid var(--color-border);
        }

        .wrapper {
            top: 10em;
            position: sticky;
            gap: 3em;
            display: flex;
            padding: 4em 6em;
            flex-direction: column;
            align-items: flex-start;
        }

        .right {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .red {
            color: var(--color-text-brand);
        }

        .element {
            border-bottom: 1px solid var(--color-border);
            padding: 4em;
            display: flex;
            flex-direction: column;
            gap: 1.5em;
        }

        .top {
            display: flex;
            align-items: center;
            gap: 1.5em;
        }

        .name {
            text-transform: uppercase;
        }

        .text {
            color: var(--color-text-secondary);
        }
        @media (orientation: portrait) {
            .wrapper {
                padding: 1em;
            }
            .container {
                flex-direction: column;
            }
            .element {
                padding: 1em;
                gap: 0;
            }
            .name {
                margin: 0;
            }
        }
    `];O=re([m("mobile-development-section")],O);var ae=Object.defineProperty,ne=Object.getOwnPropertyDescriptor,N=(e,i,r,o)=>{for(var t=o>1?void 0:o?ne(i,r):i,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=(o?s(i,r,t):s(t))||t);return o&&t&&ae(i,r,t),t};let I=class extends h{constructor(){super(...arguments),this.activeIndex=0,this._intervalId=null}connectedCallback(){super.connectedCallback(),this._startAnimation()}disconnectedCallback(){super.disconnectedCallback(),this._stopAnimation()}_startAnimation(){this._intervalId=window.setInterval(()=>{this.activeIndex=this.activeIndex===4?1:this.activeIndex+1},I.cfg.delay)}_stopAnimation(){this._intervalId&&(clearInterval(this._intervalId),this._intervalId=null)}_getBorderClass(e){const i=[];return this.activeIndex===e&&i.push("border-active"),this.activeIndex===1&&e===2&&i.push("border-top-active"),this.activeIndex===4&&e===3&&i.push("border-top-active"),i.join(" ")}_getSystemClass(e){return this.activeIndex===e?"system system-active":"system"}render(){return a`
            <section class="container">
                <div class="content">
                    <div class="icon"></div>
                    <div class="border-top"></div>
                    <div class="wtf">
                        <div class="edge"></div>
                        <div id="border-1" class="full ${this._getBorderClass(1)}"></div>
                        <div id="border-2" class="half ${this._getBorderClass(2)}"></div>
                        <div id="border-3" class="half ${this._getBorderClass(3)}"></div>
                        <div id="border-4" class="full ${this._getBorderClass(4)}"></div>
                        <div class="edge"></div>
                    </div>
                    <div class="systems">
                        <div class="system-edge"></div>
                        <div id="system-1" class="${this._getSystemClass(1)}">
                            <div class="logo"></div>
                            <span class="name">Windows</span>
                        </div>
                        <div id="system-2" class="${this._getSystemClass(2)}">
                            <div class="logo"></div>
                            <span class="name">Linux</span>
                        </div>
                        <div id="system-3" class="${this._getSystemClass(3)}">
                            <div class="logo"></div>
                            <span class="name">macOS</span>
                        </div>
                        <div id="system-4" class="${this._getSystemClass(4)}">
                            <div class="logo"></div>
                            <span class="name">BSD</span>
                        </div>
                        <div class="system-edge"></div>
                    </div>
                    <div class="technologies">
                        <div class="technology" id="technology-1">
                            <div class="sticky">
                                <div class="tech-logo"></div>
                                <h6 class="tech-name">
                                    Do you write in pure PHP?
                                </h6>
                                <span class="tech-description">
                                    Boson loves it too!
                                </span>
                            </div>
                        </div>
                        <div class="technology" id="technology-2">
                            <div class="dots-container"><dots-container></dots-container></div>
                            <div class="sticky">
                                <div class="tech-logo"></div>
                                <h6 class="tech-name">
                                    Do you work with Laravel?
                                </h6>
                                <span class="tech-description">
                                    Use familiar Blade, Livewire, Inertia or
                                    Eloquent for UI and logic. Your routes and
                                    controllers work just like on the web.
                                </span>
                            </div>
                        </div>
                        <div class="technology" id="technology-3">
                            <div class="dots-container"><dots-container></dots-container></div>
                            <div style="top: 150px" class="dots-container"><dots-container></dots-container></div>
                            <div class="sticky">
                                <div class="tech-logo"></div>
                                <h6 class="tech-name">
                                    Do you prefer Symfony or Yii?
                                </h6>
                                <span class="tech-description">
                                    Just plug in Boson. Your components and
                                    services are ready to work in Desktop application.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        `}};I.cfg={delay:2e3};I.styles=[v,d`
        .container {
            display: flex;
            flex-direction: column;
            margin-bottom: 2em;
        }

        .content {
            display: flex;
            justify-content: center;
            align-items: center;
            align-self: stretch;
            flex-direction: column;
            margin-top: 8em;
        }

        .wtf {
            display: flex;
            align-self: stretch;
        }

        .edge {
            flex: 3;
            border: none !important;
        }

        .full {
            flex: 4;
        }

        .half {
            flex: 2;
        }

        .wtf > div {
            height: 100px;
            border: 1px dashed var(--color-border);
            border-bottom: none;
            transition: 0.3s ease-in-out;
        }

        .icon {
            height: 128px;
            width: 128px;
            background: url("/images/icon.svg") center center no-repeat;
            background-size: contain;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .icon::before {
            z-index: 1;
            position: absolute;
            content: "";
            width: 640px;
            height: 640px;
            background: radial-gradient(50% 50% at 50% 50%, var(--color-text-brand) 0%, var(--color-bg) 100%);
            opacity: 0.1;
        }

        .border-top {
            border-left: 1px dashed var(--color-text-brand);
            height: 100px;
        }

        #border-1 {
            border-right: none;
        }

        #border-2 {
            border-right: none;
        }

        #border-3 {
            border-left: none;
        }

        #border-4 {
            border-left: none;
        }

        .systems {
            display: flex;
            align-self: stretch;
        }

        .system {
            transition: 0.3s ease-in-out;
            position: relative;
            height: 50px;
            flex: 8;
            border: 1px solid var(--color-border);
            border-right: none;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 1.5em;
            padding: 5em 0;
            background: var(--color-bg);
            font-family: var(--font-title), sans-serif;
            color: var(--color-text-secondary);
        }

        .system::before {
            position: absolute;
            transition: 0.3s ease-in-out;
            content: '';
            z-index: 1;
            inset: -1px;
            pointer-events: none;
            border: 1px dashed transparent;
        }

        #system-4 {
            border-right: 1px solid var(--color-border);
        }
        .system-edge {
            flex: 2;
            border-top: 1px solid var(--color-border);
            border-bottom: 1px solid var(--color-border);
            background: var(--color-bg);
        }

        .system-active {
            border-color: transparent;
            color: var(--color-text);
        }

        .system-active::before {
            border: 1px dashed var(--color-text-brand);
        }

        .border-active {
            border-color: var(--color-text-brand) !important;
        }

        .border-top-active {
            border-top-color: var(--color-text-brand) !important;
        }

        .logo {
            min-height: 38px;
            max-height: 38px;
            width: 38px;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: contain;
        }

        #system-1 > .logo {
            background-image: url('/images/icons/windows.svg');
        }

        #system-2 > .logo {
            background-image: url('/images/icons/linux.svg');
        }

        #system-3 > .logo {
            background-image: url('/images/icons/apple.svg');
        }

        #system-4 > .logo {
            background-image: url('/images/icons/freebsd.svg');
        }

        .name {
            text-transform: uppercase;
        }

        .technology-edge {
            flex: 3;
        }
        .technologies {
            display: flex;
            align-self: stretch;
            position: relative;
        }

        .technology {
            flex: 16;
            display: flex;
            position: relative;
        }
        .sticky {
            height: 450px;
            flex-direction: column;
            position: sticky;
            border: 1px solid var(--color-border);
            border-right: none;
            border-top: none;
            background: var(--color-bg);
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 5em;
            gap: 1.5em;
        }
        .technologies > .technology:nth-child(1) {
            flex-direction: column;
        }
        .technologies > .technology:nth-child(2) {
            flex-direction: column-reverse;
            height: 600px;
        }
        .technologies > .technology:nth-child(3) {
            flex-direction: column-reverse;
            height: 750px;
        }
        .technologies > .technology:nth-child(1) > .sticky {
        }
        .technologies > .technology:nth-child(2) > .sticky {
            bottom: 0;
        }
        .technologies > .technology:nth-child(3) > .sticky {
            bottom: 0;
        }
        .dots-container {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            height: 150px;
            border-left: 1px solid var(--color-border);
            border-bottom: 1px solid var(--color-border);
        }
        .tech-logo {
            min-height: 64px;
            max-height: 64px;
            min-width: 250px;
            max-width: 250px;
            background-position: center center;
            background-size: contain;
            background-repeat: no-repeat;
        }
        .tech-name {
            text-transform: uppercase;
            color: var(--color-text);
        }
        .tech-description {
            color: var(--color-text-secondary);
        }
        #technology-1 > .sticky > .tech-logo {
            background-image: url("/images/icons/php.svg");
        }
        #technology-2 > .sticky > .tech-logo {
            background-image: url("/images/icons/laravel.svg");
        }
        #technology-3 > .sticky > .tech-logo {
            background-image: url("/images/icons/symfony.svg");
        }
        @media (orientation: portrait) {
            .top {
                flex-direction: column;
                margin: 0 1em;
            }
            .system-edge {
                display: none;
            }
            .border-top {
                border-color: var(--color-border) !important;
            }
            .full {
                border-color: var(--color-border) !important;
            }
            .half {
                display: none;
                border-color: var(--color-border) !important;
            }
            .half {
                border-left-color: transparent !important;
                border-right-color: transparent !important;
            }
            .systems {
                flex-wrap: wrap;
            }
            .systems > div {
                flex: 34%;
            }
            .technologies {
                flex-direction: column;
            }
            .icon::before {
                width: 95vw;
            }
        }
    `];N([l({type:Number})],I.prototype,"activeIndex",2);I=N([m("nativeness-section")],I);var se=Object.getOwnPropertyDescriptor,le=(e,i,r,o)=>{for(var t=o>1?void 0:o?se(i,r):i,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=s(t)||t);return t};let L=class extends h{firstUpdated(){this.elements.topLeft=this.shadowRoot.querySelector(".content-top .content-left .inner"),this.elements.topRight=this.shadowRoot.querySelector(".content-top .content-right .inner"),this.elements.bottomLeft=this.shadowRoot.querySelector(".content-bottom .content-left .inner"),this.elements.bottomRight=this.shadowRoot.querySelector(".content-bottom .content-right .inner"),this.elements.progressDots=this.shadowRoot.querySelectorAll(".dots"),this.checkMobile(),this.startAnimation(),window.addEventListener("orientationchange",()=>{setTimeout(()=>{this.checkMobile()},100)}),window.addEventListener("resize",()=>this.checkMobile())}disconnectedCallback(){super.disconnectedCallback(),this.stopAnimation(),window.removeEventListener("orientationchange",this.checkMobile),window.removeEventListener("resize",this.checkMobile)}checkMobile(){const e=this.isMobile;this.isMobile=window.matchMedia("(orientation: portrait)").matches,e!==this.isMobile&&this.elements.topLeft&&this.elements.topRight&&this.elements.bottomLeft&&this.elements.bottomRight&&(this.isMobile?(this.elements.topLeft.style.transform="",this.elements.topRight.style.transform="",this.elements.bottomLeft.style.transform="",this.elements.bottomRight.style.transform=""):this.resetMobileClasses())}resetMobileClasses(){[this.shadowRoot.querySelector(".content-top .content-left"),this.shadowRoot.querySelector(".content-top .content-right"),this.shadowRoot.querySelector(".content-bottom .content-left"),this.shadowRoot.querySelector(".content-bottom .content-right")].forEach(i=>{i&&i.classList.remove("mobile-hidden","mobile-visible")})}startAnimation(){this.animationState.startTime=Date.now(),this.animate()}stopAnimation(){this.animationState.animationId&&(cancelAnimationFrame(this.animationState.animationId),this.animationState.animationId=null)}animate(){const e=L.animationConfig,r=Date.now()-this.animationState.startTime,o=e.blockDuration*4+e.transitionDuration*4,t=r%o,n=e.blockDuration,s=n+e.transitionDuration,D=s+e.blockDuration,T=D+e.transitionDuration,E=T+e.blockDuration,R=E+e.transitionDuration,M=R+e.blockDuration;M+e.transitionDuration;let w=0,k=0,_=!1;if(t<n)w=t/e.blockDuration*.5,k=0,_=!1;else if(t<s){const x=(t-n)/e.transitionDuration;w=.5,k=x,_=x>.5}else if(t<D)w=.5+(t-s)/e.blockDuration*.5,k=1,_=!0;else if(t<T){const x=(t-D)/e.transitionDuration;w=1,k=1-x,_=x<.5}else if(t<E)w=1-(t-T)/e.blockDuration*.5,k=0,_=!1;else if(t<R){const x=(t-E)/e.transitionDuration;w=.5,k=x,_=x>.5}else if(t<M)w=.5-(t-R)/e.blockDuration*.5,k=1,_=!0;else{const x=(t-M)/e.transitionDuration;w=0,k=1-x,_=x<.5}this.isMobile?this.animateMobileElements(_):this.animateDesktopElements(k),this.updateProgressBar(w),this.animationState.animationId=requestAnimationFrame(()=>this.animate())}animateDesktopElements(e){const r=L.animationConfig.animationDistance;if(!this.elements.topLeft||!this.elements.topRight||!this.elements.bottomLeft||!this.elements.bottomRight)return;const o=e*r,t=Math.min(0,-r+e*r),n=-(e*r),s=Math.max(0,r-e*r);this.elements.topLeft.style.transform=`translateX(${o}px)`,this.elements.topRight.style.transform=`translateX(${t}px)`,this.elements.bottomRight.style.transform=`translateX(${n}px)`,this.elements.bottomLeft.style.transform=`translateX(${s}px)`}animateMobileElements(e){const i=this.shadowRoot.querySelector(".content-top .content-left"),r=this.shadowRoot.querySelector(".content-top .content-right"),o=this.shadowRoot.querySelector(".content-bottom .content-left"),t=this.shadowRoot.querySelector(".content-bottom .content-right");!i||!r||!o||!t||(e?(i.classList.add("mobile-hidden"),i.classList.remove("mobile-visible"),r.classList.add("mobile-visible"),r.classList.remove("mobile-hidden")):(i.classList.add("mobile-visible"),i.classList.remove("mobile-hidden"),r.classList.add("mobile-hidden"),r.classList.remove("mobile-visible")),e?(o.classList.add("mobile-hidden"),o.classList.remove("mobile-visible"),t.classList.add("mobile-visible"),t.classList.remove("mobile-hidden")):(o.classList.add("mobile-visible"),o.classList.remove("mobile-hidden"),t.classList.add("mobile-hidden"),t.classList.remove("mobile-visible")))}updateProgressBar(e){if(!this.elements.progressDots||this.elements.progressDots.length===0)return;const i=this.elements.progressDots.length,r=Math.floor(e*i);this.elements.progressDots.forEach((o,t)=>{t<r?(o.classList.remove("grey"),o.classList.add("red")):(o.classList.remove("red"),o.classList.add("grey"))})}render(){return a`
            <section class="container">
                <div class="top">
                    <h2>
                        Why is Boson PHP</br>
                        <span class="red">the right choice</span> </br>
                        for you?
                    </h2>
                </div>
                <div class="content">
                    <div class="content-top">
                        <div class="content-left">
                            <div class="inner">
                                <h3>Your PHP — On All Devices</h3>
                            </div>
                        </div>
                        <div class="sep"></div>
                        <div class="content-right">
                            <div class="inner">
                                <h3>One Line to Start With</h3>
                            </div>
                        </div>
                    </div>
                    <div class="content-bottom">
                        <div class="content-left">
                            <div class="inner">
                                <p>
                                    To launch the application, you will need
                                    only one line of code. Without a ton of
                                    settings and configurations.
                                    Even a child can figure it out.
                                </p>

                                <!--
                                <boson-button href="/">
                                    Read More
                                </boson-button>
                                -->
                            </div>
                        </div>
                        <div class="sep"></div>
                        <div class="content-right">
                            <div class="inner">
                                <p>
                                    No need to learn other languages! You already
                                    know PHP, and that's all you need. Write code
                                    once for the Web and create native apps on
                                    Windows, macOS and Linux. The same code,
                                    and your app is available everywhere.
                                </p>

                                <!--
                                <boson-button href="/">
                                    Read More
                                </boson-button>
                                -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="progress">
                    <div class="el">
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                    </div>
                    <span class="progress-text">STAGE</span>
                    <div class="el">
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                        <div class="dots grey"></div>
                    </div>
                </div>
            </section>
        `}};L.styles=[v,d`
        :host {
            margin-top: calc(var(--landing-layout-gap) * -1);
        }

        .container {
            background-size: cover;
            background: url("/images/right_choice_bg.svg") no-repeat center;
            min-height: 200vh;
            display: flex;
            flex-direction: column;
        }

        .top {
            margin: 18em;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .top h2 {
            font-size: var(--font-size-h1);
        }

        .red {
            color: var(--color-text-brand);
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            border-top: 1px solid var(--color-border);
        }
        .content-top {
            border-bottom: 1px solid var(--color-border);
        }
        .sep {
            min-width: 1px;
            align-self: stretch;
            background: var(--color-border);
        }
        .content-top, .content-bottom {
            display: flex;
            align-self: stretch;
            flex: 1;
        }
        .content-left, .content-right {
            display: flex;
            position: relative;
            flex: 1;
            padding: 4em;
            overflow: hidden;
        }
        .content-top > div {
            align-items: flex-end;
        }
        .content-left {
            justify-content: flex-end;
            mask-image: linear-gradient(to left, transparent 0%, black 4em);
        }
        .content-right {
            justify-content: flex-start;
            mask-image: linear-gradient(to right, transparent 0%, black 4em);
        }

        .inner {
            width: 620px;
            transition: transform 0.5s ease;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 1em;
        }
        .progress {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            padding: 3em;
            align-self: stretch;
            border-top: 1px solid var(--color-border);
            gap: 1em;
        }
        .el {
            display: flex;
            flex-direction: row;
            gap: 5px;
        }
        .dots {
            height: 14px;
            width: 5px;
        }
        .dots.red {
            background-image: url("/images/icons/dots_red.svg");
        }
        .dots.grey {
            background-image: url("/images/icons/dots_grey.svg");
        }
        .progress-text {
            text-transform: uppercase;
            color: var(--color-text-secondary);
            font-family: var(--font-title), sans-serif;
        }

        /* Mobile styles - Portrait orientation only */
        @media (orientation: portrait) {
            .progress {
                margin-top: 15em;
            }
            .top {
                margin: 5em 1em;
            }
            .top h2 {
                font-size: 2.5rem;
            }

            .container {
                min-height: 100vh;
            }

            .content {
                position: relative;
            }

            .content-top, .content-bottom {
                position: relative;
                min-height: 200px;
            }

            .content-left, .content-right {
                position: absolute;
                left: 0;
                right: 0;
                padding: 2em;
                mask-image: none;
                justify-content: center;
                align-items: center;
                text-align: center;
                transition: opacity 0.5s ease;
            }

            .content-top .content-left,
            .content-top .content-right {
                bottom: 0;
            }

            .content-bottom .content-left,
            .content-bottom .content-right {
                top: 0;
            }

            .inner {
                width: 100%;
                max-width: 400px;
                align-items: center;
                text-align: center;
                transform: none !important;
            }

            .sep {
                display: none;
            }

            .mobile-hidden {
                opacity: 0;
            }

            .mobile-visible {
                opacity: 1;
            }
        }
    `];L.animationConfig={blockDuration:7e3,transitionDuration:500,animationDistance:800};L=le([m("right-choice-section")],L);var ce=Object.getOwnPropertyDescriptor,de=(e,i,r,o)=>{for(var t=o>1?void 0:o?ce(i,r):i,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=s(t)||t);return t};let q=class extends h{render(){return a`
            <section class="container">
                <div class="content">
                    <div class="dots">
                        <dots-container></dots-container>
                    </div>
                    <div class="inner">
                        <div class="solves">
                            <img src="/images/icons/terminal.svg" alt="terminal"/>
                            <h5>For developers</h5>
                            <p>Pride in your favorite language, which is not dying! A real desire to create something
                                useful and interesting. Boson will allow you to create applications from scratch, as a
                                framework.</p>
                        </div>
                        <div class="solves">
                            <img src="/images/icons/lock.svg" alt="lock"/>
                            <h5>For business</h5>
                            <p>Desktop application – getting different variants of working applications.</p>
                        </div>
                        <div class="solves">
                            <img src="/images/icons/web.svg" alt="web"/>
                            <h5>For web studios</h5>
                            <p>No need to expand your staff to make applications for different platforms, work with
                                Boson and increase your income.</p>
                        </div>
                    </div>
                    <div class="dots">
                        <dots-container></dots-container>
                    </div>
                </div>
            </section>
        `}};q.styles=[v,d`
        .container {
            display: flex;
            flex-direction: column;
            gap: 4em;
        }

        .content {
            display: flex;
            border-bottom: 1px solid var(--color-border);
            border-top: 1px solid var(--color-border);
        }

        .dots {
            min-width: 120px;
        }

        .content .dots:nth-child(1) {
            border-right: 1px solid var(--color-border);
        }

        .inner {
            display: flex;
            flex: 1;
        }

        .solves {
            flex: 1;
            border-right: 1px solid var(--color-border);
            padding: 4em;
            gap: 1.25em;
            display: flex;
            line-height: 1.75;
            flex-direction: column;
        }

        .solves img {
            align-self: flex-start;
        }

        .solves h5 {
            text-transform: uppercase;
        }

        @media (orientation: portrait) {
            .top {
                flex-direction: column;
                margin: 0 1em;
                gap: 3em;
            }
            .dots {
                display: none;
            }
            .inner {
                flex-direction: column;
            }
            .solves {
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 3em 2em;
                text-align: center;
                border-bottom: 1px solid var(--color-border);
            }
            .solves:nth-last-child(1) {
                border-bottom: 1px solid transparent;
            }
            .solves > img {
                align-self: center;
            }
        }
    `];q=de([m("solves-section")],q);var pe=Object.getOwnPropertyDescriptor,ge=(e,i,r,o)=>{for(var t=o>1?void 0:o?pe(i,r):i,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=s(t)||t);return t};let Y=class extends h{get slides(){return[{name:"Aleksei Gagarin",pfp:"/images/u/roxblnfk.png",role:"Maintainer of Spiral, Cycle, RoadRunner PHP",comment:"Finally, genuine native PHP - exactly as it should be."},{name:"Sergey Panteleev",pfp:"/images/u/saundefined.png",role:"PHP Release Manager",comment:`Every year, PHP and its ecosystem get better, partlythanks to projects that bring something new to PHP.
I like how fast it is, how user-friendly it is, and its huge potential for cross-platform applications.

I’ll be following the development of Boson.`},{name:"Valentin Udaltsov",pfp:"/images/u/vudaltsov.png",role:"OSS contributor, Speaker, Author of PHPyh",comment:"As the author of open-source tools for PHP, I see Boson as an invaluable companion for handling input/output in PHP tooling. Instead of writing temporary HTML files or spinning up a web server, you simply pass your data to a Boson process — and boom, you’ve got a window with debug information, errors, metrics, whatever. The best part is that it’s all PHP — no need to learn anything else."},{name:"Danil Shutsky",pfp:"/images/u/lee-to.png",role:"CutCode, Moonshine",comment:"I've been following NativePHP since its announcement at Laracon, but the release ultimately disappointed me with its slow performance and bulkiness. Boson turned out to be the complete opposite: fast, lightweight, and most importantly — it actually works."},{name:"Roman Pronskiy",pfp:"/images/u/pronskiy.png",role:"PhpStorm team, The PHP Foundation founder",comment:"I built a few production apps with Electron before. It has a big ecosystem, but I always missed PHP. The PHP wrappers around Electron felt limited and slow. When I first tried Boson, I thought, wow, is this a mistake? Why is it so fast? I really like the simple API and the smart design under the hood. This feels like the PHP way. Love it."},{name:"Pavel Buchnev",pfp:"/images/u/butschster.png",role:"CTO at Intruforce, Spiral Framework Maintainer",comment:`Recently, I needed to build a desktop application. The only tools I had at hand were PHP and Spiral, and honestly, I didn’t feel like diving into something completely new — I wanted some real hardcore PHP. Then I remembered that Kirill is developing Boson and thought it was the perfect time to give it a try.

It integrated with Spiral seamlessly, like it was made for it. And now—I actually have a desktop application running on PHP. I couldn’t be happier.`},{name:"Curve (Noah)",pfp:"/images/u/curve.png",role:"Developer of Saucer",comment:"I'm very glad to see projects based on Saucer bindings, and I'm especially excited for Boson as it looks very promising and professionally made, all the best!"}]}get slidesInRandomOrder(){let e=this.slides;for(let i=e.length-1;i>0;i--){const r=Math.floor(Math.random()*(i+1));[e[i],e[r]]=[e[r],e[i]]}return e}render(){return a`
            <section class="container">
                <div class="content">
                    <slider-component .slides=${this.slidesInRandomOrder}></slider-component>
                </div>
            </section>
        `}};Y.styles=[v,d`
        .container {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 6em;
        }

        .headline {
            text-align: center;
        }

        .container:before {
            content: '';
            position: absolute;
            pointer-events: none;
            background: radial-gradient(50% 50% at 50% 50%, var(--color-text-brand) 0%, var(--color-bg-layer-hover) 50%);
            opacity: 0.3;
            inset: 0;
            filter: blur(140px);
            z-index: -1;
        }
    `];Y=ge([m("testimonials-section")],Y);var he=Object.defineProperty,me=Object.getOwnPropertyDescriptor,y=(e,i,r,o)=>{for(var t=o>1?void 0:o?me(i,r):i,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=(o?s(i,r,t):s(t))||t);return o&&t&&he(i,r,t),t};let b=class extends h{constructor(){super(...arguments),this.images=[],this.title="Gallery",this.description="",this.showFilters=!1,this.showTags=!0,this.showViewButton=!0,this._currentIndex=0,this._showLightbox=!1,this._filter="all",this.categories=[],this._handleKeyDown=e=>{if(this._showLightbox)switch(e.key){case"Escape":this._closeLightbox();break;case"ArrowLeft":this._prevImage();break;case"ArrowRight":this._nextImage();break}}}get allTags(){const e=new Set;return this.images.forEach(i=>{i.tags&&i.tags.forEach(r=>e.add(r)),i.category&&e.add(i.category)}),Array.from(e)}connectedCallback(){super.connectedCallback(),this._setupKeyboardNavigation()}disconnectedCallback(){this._cleanupKeyboardNavigation(),super.disconnectedCallback()}_setupKeyboardNavigation(){document.addEventListener("keydown",this._handleKeyDown)}_cleanupKeyboardNavigation(){document.removeEventListener("keydown",this._handleKeyDown)}_openLightbox(e){this._currentIndex=e,this._showLightbox=!0,document.body.style.overflow="hidden"}_closeLightbox(){this._showLightbox=!1,document.body.style.overflow=""}_prevImage(){this._currentIndex=this._currentIndex>0?this._currentIndex-1:this.images.length-1}_nextImage(){this._currentIndex=this._currentIndex<this.images.length-1?this._currentIndex+1:0}_setFilter(e){this._filter=e}get filteredImages(){return this._filter==="all"?this.images:this.images.filter(e=>e.tags?.includes(this._filter)||e.category===this._filter)}renderImage(e,i){const r=this.images.indexOf(e);return a`
      <div class="gallery-item">
        <div class="gallery-image-container">
          <img 
            src=${e.src}
            alt=${e.alt}
            class="gallery-image"
            loading="lazy"
            width=${e.width||400}
            height=${e.height||300}
            @load=${()=>{const o=this.shadowRoot?.querySelector(`[data-index="${i}"]`);o&&o.classList.remove("loading")}}
            @error=${o=>{const t=o.target;t.style.opacity="0"}}
          />
          <div class="gallery-overlay"></div>
        </div>

        ${this.showTags&&(e.tags?.length||e.category)?a`
          <div class="gallery-tags">
            ${e.category?a`
              <span class="tag">${e.category}</span>
            `:""}
            ${e.tags?.slice(0,2).map(o=>a`
              <span class="tag">${o}</span>
            `)}
          </div>
        `:""}

        ${this.showViewButton?a`
          <button class="view-button" @click=${o=>{o.stopPropagation(),this._openLightbox(r)}}>
            <span class="view-icon">👁️</span>
            View
          </button>
        `:""}

        ${e.caption?a`
          <div class="gallery-caption">
            <div class="caption-title">${e.alt}</div>
            <div class="caption-description">${e.caption}</div>
          </div>
        `:""}
      </div>
    `}render(){const e=this.images[this._currentIndex],i=this.filteredImages,r=this.allTags;return a`
      <section class="gallery">
        <div class="gallery-header">
          ${this.title?a`
            <h2 class="gallery-title">${this.title}</h2>
          `:""}
          
          ${this.description?a`
            <p class="gallery-description">${this.description}</p>
          `:""}
        </div>

        ${this.showFilters&&r.length>0?a`
          <div class="gallery-filters">
            <button 
              class="filter-btn ${this._filter==="all"?"active":""}"
              @click=${()=>this._setFilter("all")}
            >
              All
            </button>
            ${r.map(o=>a`
              <button 
                class="filter-btn ${this._filter===o?"active":""}"
                @click=${()=>this._setFilter(o)}
              >
                ${o}
              </button>
            `)}
          </div>
        `:""}

        <div class="gallery-grid">
          ${i.length>0?i.map((o,t)=>this.renderImage(o,t)):a`
                <div class="gallery-empty">
                  <h3>No images found</h3>
                  <p>Try selecting a different filter</p>
                </div>
              `}
        </div>

        <!-- Lightbox -->
        <div class="lightbox ${this._showLightbox?"active":""}" 
             @click=${o=>{o.target===o.currentTarget&&this._closeLightbox()}}>
          ${this._showLightbox&&e?a`
            <div class="lightbox-content">
              <button class="lightbox-close" @click=${this._closeLightbox}>
                &times;
              </button>
              
              ${this.images.length>1?a`
                <button class="filter-btn lightbox-prev" 
                        style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%)"
                        @click=${this._prevImage}>
                  ←
                </button>
                <button class="filter-btn lightbox-next" 
                        style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%)"
                        @click=${this._nextImage}>
                  →
                </button>
                <div style="position: absolute; top: -45px; left: 0; color: white; font-size: 14px;">
                  ${this._currentIndex+1} / ${this.images.length}
                </div>
              `:""}

              <img 
                src=${e.src}
                alt=${e.alt}
                class="lightbox-image"
              />
              
              ${e.caption?a`
                <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.8); color: white; padding: 16px; text-align: center; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                  ${e.caption}
                </div>
              `:""}
            </div>
          `:""}
        </div>
      </section>
    `}};b.styles=[v,d`
      :host {
        display: block;
        width: 100%;
        --gallery-shine-color: rgba(255, 255, 255, 0.15);
      }

      .gallery {
        display: grid;
        gap: var(--spacing-xl, 48px);
        margin: 0 auto;
        max-width: var(--width-max, 1440px);
        padding: var(--spacing-3xl, 64px) var(--spacing-md, 16px);
      }

      .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: var(--spacing-lg, 24px);
      }

      @media (min-width: 768px) {
        .gallery-grid {
          grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
          gap: var(--spacing-xl, 32px);
        }
      }

      /* GALLERY ITEM WITH SHINE EFFECT */
      .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        background: var(--color-bg-layer, #0f131c);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        aspect-ratio: 4/3;
        isolation: isolate;
        box-shadow: 
          0 4px 12px rgba(0, 0, 0, 0.1),
          0 8px 24px rgba(0, 0, 0, 0.15),
          inset 0 1px 0 rgba(255, 255, 255, 0.05);
      }

      .gallery-item::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
          135deg,
          transparent 0%,
          var(--gallery-shine-color) 50%,
          transparent 100%
        );
        transform: translateX(-100%) rotate(25deg);
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 2;
        pointer-events: none;
      }

      .gallery-item:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 
          0 16px 32px rgba(0, 0, 0, 0.25),
          0 32px 64px rgba(0, 0, 0, 0.2),
          0 0 0 1px rgba(255, 87, 34, 0.1),
          inset 0 1px 0 rgba(255, 255, 255, 0.1);
      }

      .gallery-item:hover::before {
        transform: translateX(100%) rotate(25deg);
      }

      /* Image container with gradient overlay */
      .gallery-image-container {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
        border-radius: inherit;
      }

      .gallery-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        filter: brightness(0.95) saturate(1.1);
        will-change: transform;
      }

      .gallery-item:hover .gallery-image {
        transform: scale(1.08);
        filter: brightness(1) saturate(1.2);
      }

      /* Gradient overlay for depth */
      .gallery-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
          to bottom,
          transparent 40%,
          rgba(0, 0, 0, 0.3) 70%,
          rgba(0, 0, 0, 0.7) 100%
        );
        opacity: 0.6;
        transition: opacity 0.4s ease;
        border-radius: inherit;
        z-index: 1;
      }

      .gallery-item:hover .gallery-overlay {
        opacity: 0.8;
      }

      /* Caption with glass effect */
      .gallery-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: var(--spacing-xl, 32px) var(--spacing-lg, 24px);
        background: linear-gradient(
          to top,
          rgba(15, 19, 28, 0.95) 0%,
          rgba(15, 19, 28, 0.8) 50%,
          transparent 100%
        );
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transform: translateY(10px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 2;
        border-bottom-left-radius: 16px;
        border-bottom-right-radius: 16px;
      }

      .gallery-item:hover .gallery-caption {
        transform: translateY(0);
        opacity: 1;
      }

      .caption-title {
        font-family: var(--font-title, 'Roboto Condensed');
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--color-text);
        margin-bottom: var(--spacing-xs, 4px);
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }

      .caption-description {
        font-size: 0.875rem;
        color: var(--color-text-secondary);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }

      /* Tags */
      .gallery-tags {
        position: absolute;
        top: var(--spacing-md, 16px);
        left: var(--spacing-md, 16px);
        display: flex;
        gap: var(--spacing-xs, 4px);
        flex-wrap: wrap;
        z-index: 2;
        transform: translateY(-10px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      }

      .gallery-item:hover .gallery-tags {
        transform: translateY(0);
        opacity: 1;
      }

      .tag {
        padding: 4px 10px;
        background: rgba(255, 87, 34, 0.9);
        color: white;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
      }

      /* View button */
      .view-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.8);
        padding: 12px 24px;
        background: rgba(255, 87, 34, 0.95);
        color: white;
        border: none;
        border-radius: 24px;
        font-weight: 500;
        font-size: 0.875rem;
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 2;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: var(--spacing-xs, 4px);
      }

      .gallery-item:hover .view-button {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
      }

      .view-button:hover {
        background: rgba(255, 112, 67, 0.95);
        transform: translate(-50%, -50%) scale(1.05);
      }

      .view-icon {
        font-size: 1rem;
      }

      /* Gallery title and description */
      .gallery-header {
        text-align: center;
        max-width: 800px;
        margin: 0 auto var(--spacing-2xl, 48px);
      }

      .gallery-title {
        font-family: var(--font-title, 'Roboto Condensed');
        font-size: var(--font-size-h2, 64px);
        line-height: var(--font-line-height-h2, 120%);
        margin-bottom: var(--spacing-md, 16px);
        color: var(--color-text);
        background: linear-gradient(135deg, var(--color-text) 0%, var(--color-text-secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }

      .gallery-description {
        font-size: 1.125rem;
        line-height: 1.6;
        color: var(--color-text-secondary);
        max-width: 600px;
        margin: 0 auto;
      }

      /* Filters */
      .gallery-filters {
        display: flex;
        gap: var(--spacing-sm, 8px);
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: var(--spacing-2xl, 48px);
      }

      .filter-btn {
        padding: 10px 20px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        color: var(--color-text-secondary);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
      }

      .filter-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
      }

      .filter-btn.active {
        background: rgba(255, 87, 34, 0.2);
        border-color: rgba(255, 87, 34, 0.4);
        color: var(--color-text-brand);
      }

      /* Lightbox (zachovaný z pôvodnej verzie) */
      .lightbox {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
      }

      .lightbox.active {
        opacity: 1;
        visibility: visible;
      }

      .lightbox-content {
        position: relative;
        max-width: 90vw;
        max-height: 90vh;
      }

      .lightbox-image {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 12px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
      }

      .lightbox-close {
        position: absolute;
        top: -50px;
        right: 0;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 24px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
      }

      .lightbox-close:hover {
        background: rgba(255, 87, 34, 0.8);
      }

      /* Loading animation */
      .gallery-item.loading::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
          90deg,
          transparent 0%,
          rgba(255, 255, 255, 0.1) 50%,
          transparent 100%
        );
        animation: loading-shine 1.5s infinite;
        border-radius: inherit;
      }

      @keyframes loading-shine {
        0% { transform: translateX(-100%) skewX(-15deg); }
        100% { transform: translateX(100%) skewX(-15deg); }
      }

      /* Empty state */
      .gallery-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: var(--spacing-3xl, 64px);
        color: var(--color-text-secondary);
      }
    `];y([l({type:Array})],b.prototype,"images",2);y([l({type:String})],b.prototype,"title",2);y([l({type:String})],b.prototype,"description",2);y([l({type:Boolean})],b.prototype,"showFilters",2);y([l({type:Boolean})],b.prototype,"showTags",2);y([l({type:Boolean})],b.prototype,"showViewButton",2);y([c()],b.prototype,"_currentIndex",2);y([c()],b.prototype,"_showLightbox",2);y([c()],b.prototype,"_filter",2);y([l({type:Array})],b.prototype,"categories",2);b=y([m("gallery-section")],b);var be=Object.defineProperty,ve=Object.getOwnPropertyDescriptor,A=(e,i,r,o)=>{for(var t=o>1?void 0:o?ve(i,r):i,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=(o?s(i,r,t):s(t))||t);return o&&t&&be(i,r,t),t};let P=class extends h{constructor(){super(...arguments),this.slides=[],this.autoplayInterval=5e3,this._currentSlide=0,this._isAnimating=!1,this._autoplayTimer=null,this._handleKeyDown=e=>{if(!this._isAnimating)switch(e.key){case"ArrowLeft":e.preventDefault(),this._prevSlide();break;case"ArrowRight":case" ":e.preventDefault(),this._nextSlide();break;case"Home":e.preventDefault(),this._goToSlide(0);break;case"End":e.preventDefault(),this._goToSlide(this.slides.length-1);break}}}connectedCallback(){super.connectedCallback(),this._startAutoplay(),this._setupKeyboardNavigation()}disconnectedCallback(){this._stopAutoplay(),this._cleanupKeyboardNavigation(),super.disconnectedCallback()}_startAutoplay(){this.autoplayInterval>0&&this.slides.length>1&&(this._autoplayTimer=setInterval(()=>{this._nextSlide()},this.autoplayInterval))}_stopAutoplay(){this._autoplayTimer&&(clearInterval(this._autoplayTimer),this._autoplayTimer=null)}_setupKeyboardNavigation(){document.addEventListener("keydown",this._handleKeyDown)}_cleanupKeyboardNavigation(){document.removeEventListener("keydown",this._handleKeyDown)}_goToSlide(e){this._isAnimating||e<0||e>=this.slides.length||e===this._currentSlide||(this._isAnimating=!0,this._currentSlide=e,this._stopAutoplay(),this._startAutoplay(),setTimeout(()=>{this._isAnimating=!1},1e3))}_nextSlide(){const e=(this._currentSlide+1)%this.slides.length;this._goToSlide(e)}_prevSlide(){const e=this._currentSlide===0?this.slides.length-1:this._currentSlide-1;this._goToSlide(e)}_onDotClick(e,i){i.preventDefault(),this._goToSlide(e)}_onMouseEnter(){this._stopAutoplay()}_onMouseLeave(){this._startAutoplay()}renderSlide(e,i){const o={slide:!0,active:i===this._currentSlide,loading:!1},t=window.innerWidth<992&&e.backgroundImageMobile?e.backgroundImageMobile:e.backgroundImage;return a`
      <li class=${X(o)} 
          data-order=${e.dataOrder||i}
          @click=${()=>this._goToSlide(i)}>
        
        <div class="slide_bg_overlay"></div>
        
        <div class="slide_inner container container--big">
          <div class="slide_content">
            
            <!-- Text Content -->
            <div class="slider--home_text">
              <div class="slider--home_text_inner">
                <a href=${e.link} class="link--none">
                  ${e.tag?a`
                    <h5 class="h3-slider anim-block_outer">
                      <span class="anim-block">
                        <span class="anim-block_line"></span>
                        <span class="anim-block_inner">
                          <span>${e.tag}</span>
                        </span>
                      </span>
                    </h5>
                  `:""}
                  
                  <h1 class="h1-slider anim-block_outer">
                    <span class="anim-block">
                      <span class="anim-block_line anim-delay-1"></span>
                      <span class="anim-block_inner">
                        <span>${e.titleLine1}</span>
                      </span>
                    </span>
                    ${e.titleLine2?a`
                      <span class="anim-block">
                        <span class="anim-block_line anim-delay-2"></span>
                        <span class="anim-block_inner">
                          <span>${e.titleLine2}</span>
                        </span>
                      </span>
                    `:""}
                  </h1>
                  
                  ${e.subtitle?a`
                    <h2 class="h2-slider anim-desc_outer">
                      <span class="anim-desc anim-delay-3">
                        ${e.subtitle}
                      </span>
                    </h2>
                  `:""}
                </a>
                
                <!-- Spinner Button -->
                <div class="loader--js">
                  <a href=${e.link} class="spinner">
                    <svg viewBox="0 0 250 250" preserveAspectRatio="xMinYMin meet">
                      <circle cx="120" cy="120" r="100" stroke-dasharray="628" stroke-dashoffset="628" pathLength="628"/>
                    </svg>
                    <svg viewBox="0 0 250 250" preserveAspectRatio="xMinYMin meet">
                      <circle cx="120" cy="120" r="100" stroke-dasharray="628" stroke-dashoffset="628" pathLength="628"/>
                    </svg>
                    <span class="spinner-icon icon-chevron-down">↓</span>
                    <span class="spinner-text">Read More</span>
                  </a>
                </div>
              </div>
            </div>
            
            <!-- Background Image -->
            <div class="slider--home_img">
              <div class="slider--home_img--inner"
                   style="background-image: url('${t}')">
              </div>
            </div>
            
          </div>
        </div>
      </li>
    `}render(){return a`
      <section class="slider slider--home"
               @mouseenter=${this._onMouseEnter}
               @mouseleave=${this._onMouseLeave}>
        
        <!-- Slides -->
        <div class="slides">
          <ul class="slides_inner">
            ${this.slides.map((e,i)=>this.renderSlide(e,i))}
          </ul>
        </div>
        
        <!-- Navigation Arrows -->
        ${this.slides.length>1?a`
          <button class="slider_nav slider_prev" 
                  @click=${e=>{e.preventDefault(),e.stopPropagation(),this._prevSlide()}}
                  aria-label="Previous slide">
            ←
          </button>
          <button class="slider_nav slider_next" 
                  @click=${e=>{e.preventDefault(),e.stopPropagation(),this._nextSlide()}}
                  aria-label="Next slide">
            →
          </button>
        `:""}
        
        <!-- Pagination Dots -->
        ${this.slides.length>1?a`
          <div class="slider_pagination_container">
            <nav class="slider_pagination">
              ${this.slides.map((e,i)=>a`
                <button class="pagination-dot ${i===this._currentSlide?"active":""}"
                        @click=${r=>this._onDotClick(i,r)}
                        aria-label=${`Go to slide ${i+1}`}
                        aria-current=${i===this._currentSlide?"true":"false"}>
                </button>
              `)}
            </nav>
          </div>
        `:""}
        
      </section>
    `}};P.styles=[v,d`
      :host {
        display: block;
        position: relative;
        width: 100%;
        height: 100vh;
        min-height: 700px;
        overflow: hidden;
        background: var(--color-bg, #0d1119);
      }

      /* Slider Container */
      .slider {
        position: relative;
        width: 100%;
        height: 100%;
      }

      /* Slides List */
      .slides {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        list-style: none;
      }

      .slides_inner {
        position: relative;
        width: 100%;
        height: 100%;
      }

      /* Individual Slide */
      .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        visibility: hidden;
        transition: opacity 1s cubic-bezier(0.4, 0, 0.2, 1),
                  visibility 1s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1;
      }

      .slide.active {
        opacity: 1;
        visibility: visible;
        z-index: 2;
      }

      .slide_inner {
        display: flex;
        align-items: center;
        height: 100%;
        padding: 0 5%;
      }

      /* Slide Content Grid */
      .slide_content {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
      }

      @media (max-width: 992px) {
        .slide_content {
          grid-template-columns: 1fr;
          gap: 40px;
          text-align: center;
        }
        
        .slider--home_img {
          order: -1;
          max-height: 40vh;
        }
      }

      /* Text Content Styles */
      .slider--home_text {
        position: relative;
        z-index: 3;
      }

      .slider--home_text_inner {
        max-width: 600px;
      }

      /* Animated Title Blocks */
      .anim-block_outer {
        display: block;
        overflow: hidden;
        margin: 0;
        line-height: 1;
      }

      .anim-block {
        display: block;
        overflow: hidden;
        margin-bottom: 8px;
      }

      .anim-block_line {
        display: block;
        transform: translateY(100%);
        opacity: 0;
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.1s,
                  opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.1s;
      }

      .slide.active .anim-block_line {
        transform: translateY(0);
        opacity: 1;
      }

      .anim-block_inner {
        display: block;
        font-family: var(--font-title, 'Roboto Condensed');
        font-weight: 700;
        color: var(--color-text, rgba(255, 255, 255, 0.9));
      }

      /* Title Sizes */
      .h1-slider {
        font-size: clamp(3rem, 8vw, 5.5rem);
        margin-bottom: 1.5rem;
      }

      .h2-slider {
        font-size: clamp(1.25rem, 3vw, 1.75rem);
        line-height: 1.4;
        color: var(--color-text-secondary, rgba(255, 255, 255, 0.6));
        margin-bottom: 3rem;
        max-width: 500px;
      }

      .h3-slider {
        font-size: 0.875rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--color-text-brand, #FF5722);
        margin-bottom: 1rem;
      }

      .anim-desc_outer {
        display: block;
        overflow: hidden;
      }

      .anim-desc {
        display: block;
        transform: translateY(30px);
        opacity: 0;
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.3s,
                  opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.3s;
      }

      .slide.active .anim-desc {
        transform: translateY(0);
        opacity: 1;
      }

      /* Link Styling */
      .link--none {
        text-decoration: none;
        color: inherit;
        display: block;
      }

      /* Background Image */
      .slider--home_img {
        position: relative;
        height: 80vh;
        max-height: 800px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      }

      .slider--home_img--inner {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        transition: transform 1.2s cubic-bezier(0.4, 0, 0.2, 1);
      }

      .slide.active .slider--home_img--inner {
        transform: scale(1.05);
      }

      /* Spinner Navigation Button */
      .loader--js {
        position: relative;
        display: inline-block;
      }

      .spinner {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: var(--color-text);
        font-family: var(--font-main, Inter);
        font-size: 0.875rem;
        font-weight: 500;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 16px 24px;
        border-radius: 50px;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
      }

      .spinner:hover {
        background: rgba(255, 87, 34, 0.1);
        border-color: rgba(255, 87, 34, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(255, 87, 34, 0.2);
      }

      .spinner svg {
        position: absolute;
        width: 50px;
        height: 50px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-90deg);
      }

      .spinner circle {
        fill: none;
        stroke: var(--color-text-brand, #FF5722);
        stroke-width: 2;
        stroke-dasharray: 628;
        stroke-dashoffset: 628;
        transition: stroke-dashoffset 0.3s ease;
      }

      .spinner:hover circle {
        stroke-dashoffset: 0;
      }

      .spinner-icon {
        font-size: 1.25rem;
        transition: transform 0.3s ease;
      }

      .spinner:hover .spinner-icon {
        transform: translateY(2px);
      }

      .spinner-text {
        position: relative;
        z-index: 1;
      }

      /* Pagination Navigation */
      .slider_pagination_container {
        position: absolute;
        bottom: 40px;
        left: 0;
        width: 100%;
        z-index: 10;
      }

      .slider_pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
      }

      .pagination-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        cursor: pointer;
        padding: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
      }

      .pagination-dot::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--color-text-brand, #FF5722);
        transform: scale(0);
        border-radius: 50%;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }

      .pagination-dot.active::before {
        transform: scale(1);
      }

      .pagination-dot:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.2);
      }

      /* Previous/Next Buttons */
      .slider_nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        font-size: 24px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 10;
        backdrop-filter: blur(10px);
      }

      .slider_nav:hover {
        background: rgba(255, 87, 34, 0.2);
        border-color: rgba(255, 87, 34, 0.4);
        transform: translateY(-50%) scale(1.1);
      }

      .slider_prev {
        left: 40px;
      }

      .slider_next {
        right: 40px;
      }

      @media (max-width: 768px) {
        .slider_prev {
          left: 20px;
        }
        .slider_next {
          right: 20px;
        }
        .slider_nav {
          width: 50px;
          height: 50px;
          font-size: 20px;
        }
      }

      /* Slide Background Overlay */
      .slide_bg_overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(
          to right,
          rgba(13, 17, 25, 0.9) 0%,
          rgba(13, 17, 25, 0.7) 30%,
          rgba(13, 17, 25, 0.4) 100%
        );
        z-index: 1;
      }

      /* Animation Delay Classes */
      .anim-delay-1 { transition-delay: 0.1s; }
      .anim-delay-2 { transition-delay: 0.2s; }
      .anim-delay-3 { transition-delay: 0.3s; }
      .anim-delay-4 { transition-delay: 0.4s; }
      .anim-delay-5 { transition-delay: 0.5s; }

      /* Loading State */
      .slide.loading .slider--home_img--inner {
        background: linear-gradient(90deg, #1a1a2e 25%, #2a2a3e 50%, #1a1a2e 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
      }

      @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
      }
    `];A([l({type:Array})],P.prototype,"slides",2);A([l({type:Number})],P.prototype,"autoplayInterval",2);A([c()],P.prototype,"_currentSlide",2);A([c()],P.prototype,"_isAnimating",2);A([c()],P.prototype,"_autoplayTimer",2);P=A([m("hero-slider-section")],P);var ue=Object.defineProperty,fe=Object.getOwnPropertyDescriptor,u=(e,i,r,o)=>{for(var t=o>1?void 0:o?fe(i,r):i,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=(o?s(i,r,t):s(t))||t);return o&&t&&ue(i,r,t),t};let p=class extends h{constructor(){super(...arguments),this.slides=[],this.autoplayInterval=4e3,this.showNavigation=!0,this.showScrollHint=!0,this._currentSlide=0,this._isAnimating=!1,this._autoplayTimer=null,this._wheelTimeout=null,this._scrollProgress=0,this._touchStartX=0,this._touchStartY=0,this._wheelBlocked=!1,this._handleResize=()=>{this._updateSlidePosition()},this._handleTouchStart=e=>{this._touchStartX=e.touches[0].clientX,this._touchStartY=e.touches[0].clientY},this._handleTouchEnd=e=>{const i=e.changedTouches[0].clientX,r=e.changedTouches[0].clientY,o=i-this._touchStartX,t=r-this._touchStartY;Math.abs(o)>Math.abs(t)&&Math.abs(o)>50&&(e.preventDefault(),o>0?this._prevSlide():this._nextSlide())},this._handleWheel=e=>{this._isAnimating||this._wheelBlocked||Math.abs(e.deltaY)>Math.abs(e.deltaX)&&(e.preventDefault(),this._wheelBlocked=!0,clearTimeout(this._wheelTimeout),this._wheelTimeout=setTimeout(()=>{this._wheelBlocked=!1},800),e.deltaY>0?this._nextSlide():this._prevSlide())},this._handleKeyDown=e=>{if(!this._isAnimating)switch(e.key){case"ArrowLeft":case"ArrowUp":e.preventDefault(),this._prevSlide();break;case"ArrowRight":case"ArrowDown":case" ":e.preventDefault(),this._nextSlide();break;case"Home":e.preventDefault(),this._goToSlide(0);break;case"End":e.preventDefault(),this._goToSlide(this.slides.length-1);break}}}firstUpdated(){this._container=this.shadowRoot?.querySelector(".slides-wrapper"),this._setupEventListeners(),this._startAutoplay(),this._updateSlidePosition()}connectedCallback(){super.connectedCallback(),this._container&&(this._container.style.overflow="hidden")}disconnectedCallback(){this._stopAutoplay(),this._cleanupEventListeners(),this._container&&(this._container.style.overflow=""),super.disconnectedCallback()}_setupEventListeners(){this.addEventListener("wheel",this._handleWheel,{passive:!1}),document.addEventListener("keydown",this._handleKeyDown),this.addEventListener("touchstart",this._handleTouchStart,{passive:!0}),this.addEventListener("touchend",this._handleTouchEnd,{passive:!0}),window.addEventListener("resize",this._handleResize)}_cleanupEventListeners(){this.removeEventListener("wheel",this._handleWheel),document.removeEventListener("keydown",this._handleKeyDown),this.removeEventListener("touchstart",this._handleTouchStart),this.removeEventListener("touchend",this._handleTouchEnd),window.removeEventListener("resize",this._handleResize)}_startAutoplay(){this.autoplayInterval>0&&this.slides.length>1&&(this._autoplayTimer=setInterval(()=>{this._nextSlide()},this.autoplayInterval))}_stopAutoplay(){this._autoplayTimer&&(clearInterval(this._autoplayTimer),this._autoplayTimer=null)}_goToSlide(e){this._isAnimating||e<0||e>=this.slides.length||e===this._currentSlide||(this._isAnimating=!0,this._currentSlide=e,this._scrollProgress=e/(this.slides.length-1)*100,this._stopAutoplay(),this._startAutoplay(),this._updateSlidePosition(),setTimeout(()=>{this._isAnimating=!1},1200))}_nextSlide(){const e=(this._currentSlide+1)%this.slides.length;this._goToSlide(e)}_prevSlide(){const e=this._currentSlide===0?this.slides.length-1:this._currentSlide-1;this._goToSlide(e)}_updateSlidePosition(){if(this._container){const e=-this._currentSlide*100;this._container.style.transform=`translateX(${e}vw)`}}_onMouseEnter(){this._stopAutoplay()}_onMouseLeave(){this._startAutoplay()}renderSlide(e,i){const r=i===this._currentSlide,o=window.innerWidth<768&&e.backgroundImageMobile?e.backgroundImageMobile:e.backgroundImage;return a`
      <div class="slide ${r?"active":""}" data-id="${e.id}">
        <div 
          class="slide-bg"
          style=${W({backgroundImage:`url('${o}')`,transform:r?"scale(1.05)":"scale(1)"})}
        ></div>
        
        <div class="slide-content">
          <div class="text-content">
            ${e.tag?a`
              <div class="slide-tag">${e.tag}</div>
            `:""}
            
            <div class="title-line">
              <span class="title-text">${e.titleLine1}</span>
            </div>
            
            ${e.titleLine2?a`
              <div class="title-line">
                <span class="title-text">${e.titleLine2}</span>
              </div>
            `:""}
            
            ${e.subtitle?a`
              <div class="subtitle">${e.subtitle}</div>
            `:""}
            
            <a 
              href="${e.link}" 
              class="action-button"
              @click=${t=>{e.link.startsWith("#")&&(t.preventDefault(),window.dispatchEvent(new CustomEvent("pjax:navigate",{detail:{url:e.link}})))}}
            >
              ${e.buttonText||"Read More"}
              <span class="button-icon">→</span>
            </a>
          </div>
        </div>
      </div>
    `}render(){return this.slides.length?a`
      <div 
        class="horizontal-scroll-container"
        @mouseenter=${this._onMouseEnter}
        @mouseleave=${this._onMouseLeave}
      >
        <!-- Progress bar -->
        <div class="progress-container">
          <div class="progress-bar" style="width: ${this._scrollProgress}%"></div>
        </div>
        
        <!-- Slides -->
        <div class="slides-wrapper">
          ${this.slides.map((e,i)=>this.renderSlide(e,i))}
        </div>
        
        <!-- Scroll hint -->
        ${this.showScrollHint&&this.slides.length>1?a`
          <div class="scroll-hint">
            <div class="mouse-wheel"></div>
            <span>Scroll to navigate</span>
          </div>
        `:""}
        
        <!-- Slide counter -->
        ${this.slides.length>1?a`
          <div class="slide-counter">
            <span class="counter-current">${this._currentSlide+1}</span>
            <span class="counter-total">/${this.slides.length}</span>
          </div>
        `:""}
      </div>
    `:a``}};p.styles=d`
    :host {
      display: block;
      width: 100vw;
      height: 100vh;
      min-height: 700px;
      position: relative;
      overflow: hidden;
      background: #0d1119;
    }

    * {
      box-sizing: border-box;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    /* Main container for horizontal scroll */
    .horizontal-scroll-container {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
    }

    /* Slides wrapper - moves horizontally */
    .slides-wrapper {
      display: flex;
      width: 100%;
      height: 100%;
      transition: transform 1.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
      will-change: transform;
    }

    /* Individual slide */
    .slide {
      flex: 0 0 100vw;
      width: 100vw;
      height: 100vh;
      position: relative;
      overflow: hidden;
    }

    /* Slide background */
    .slide-bg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      transition: transform 1.5s cubic-bezier(0.215, 0.61, 0.355, 1);
    }

    .slide-bg::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(
        90deg,
        rgba(0, 0, 0, 0.8) 0%,
        rgba(0, 0, 0, 0.6) 30%,
        rgba(0, 0, 0, 0.4) 50%,
        rgba(0, 0, 0, 0.2) 70%,
        transparent 100%
      );
    }

    /* Slide content */
    .slide-content {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      padding: 0 10%;
      z-index: 2;
    }

    .text-content {
      max-width: 600px;
      position: relative;
      z-index: 3;
    }

    /* Tag */
    .slide-tag {
      display: inline-block;
      font-family: 'Roboto Condensed', sans-serif;
      font-size: 14px;
      font-weight: 600;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: #FF5722;
      margin-bottom: 20px;
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.8s cubic-bezier(0.215, 0.61, 0.355, 1);
    }

    .slide.active .slide-tag {
      opacity: 1;
      transform: translateY(0);
      transition-delay: 0.2s;
    }

    /* Title */
    .title-line {
      display: block;
      overflow: hidden;
      margin-bottom: 8px;
    }

    .title-text {
      display: block;
      font-family: 'Roboto Condensed', sans-serif;
      font-weight: 700;
      font-size: clamp(3rem, 8vw, 5.5rem);
      line-height: 1.1;
      color: #FFFFFF;
      transform: translateY(100%);
      transition: transform 0.9s cubic-bezier(0.215, 0.61, 0.355, 1);
    }

    .slide.active .title-text {
      transform: translateY(0);
    }

    .title-line:nth-child(1) .title-text {
      transition-delay: 0.3s;
    }

    .title-line:nth-child(2) .title-text {
      transition-delay: 0.5s;
    }

    /* Subtitle */
    .subtitle {
      font-family: 'Inter', sans-serif;
      font-size: clamp(1.125rem, 2.5vw, 1.5rem);
      line-height: 1.6;
      color: rgba(255, 255, 255, 0.8);
      margin-top: 24px;
      max-width: 500px;
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.8s cubic-bezier(0.215, 0.61, 0.355, 1);
    }

    .slide.active .subtitle {
      opacity: 1;
      transform: translateY(0);
      transition-delay: 0.7s;
    }

    /* Action Button */
    .action-button {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      margin-top: 40px;
      padding: 16px 32px;
      background: rgba(255, 87, 34, 0.1);
      border: 1px solid rgba(255, 87, 34, 0.3);
      border-radius: 50px;
      color: #FFFFFF;
      font-family: 'Inter', sans-serif;
      font-size: 14px;
      font-weight: 500;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      opacity: 0;
      transform: translateY(20px);
    }

    .slide.active .action-button {
      opacity: 1;
      transform: translateY(0);
      transition: all 0.8s cubic-bezier(0.215, 0.61, 0.355, 1) 0.9s;
    }

    .action-button:hover {
      background: rgba(255, 87, 34, 0.2);
      border-color: rgba(255, 87, 34, 0.5);
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(255, 87, 34, 0.3);
    }

    .button-icon {
      font-size: 20px;
      transition: transform 0.3s ease;
    }

    .action-button:hover .button-icon {
      transform: translateX(4px);
    }

    /* Scroll hint */
    .scroll-hint {
      position: absolute;
      bottom: 40px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      color: rgba(255, 255, 255, 0.6);
      font-family: 'Inter', sans-serif;
      font-size: 12px;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      z-index: 10;
      opacity: 0;
      animation: fadeIn 1s ease 2s forwards;
    }

    @keyframes fadeIn {
      to { opacity: 1; }
    }

    .mouse-wheel {
      width: 24px;
      height: 40px;
      border: 2px solid rgba(255, 255, 255, 0.6);
      border-radius: 12px;
      position: relative;
    }

    .mouse-wheel::after {
      content: '';
      position: absolute;
      top: 8px;
      left: 50%;
      width: 4px;
      height: 4px;
      background: rgba(255, 255, 255, 0.8);
      border-radius: 50%;
      transform: translateX(-50%);
      animation: scrollHint 2s infinite;
    }

    @keyframes scrollHint {
      0%, 100% { transform: translateX(-50%) translateY(0); opacity: 0.8; }
      50% { transform: translateX(-50%) translateY(8px); opacity: 1; }
    }

    /* Progress bar */
    .progress-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 3px;
      background: rgba(255, 255, 255, 0.1);
      z-index: 1000;
    }

    .progress-bar {
      height: 100%;
      background: linear-gradient(90deg, #FF5722, #FF7043);
      width: 0%;
      transition: width 0.3s ease;
    }

    /* Slide counter */
    .slide-counter {
      position: fixed;
      bottom: 40px;
      right: 40px;
      font-family: 'Roboto Condensed', sans-serif;
      font-size: 14px;
      color: rgba(255, 255, 255, 0.6);
      z-index: 100;
    }

    .counter-current {
      color: white;
      font-size: 24px;
      font-weight: 700;
    }

    .counter-total {
      font-size: 14px;
    }

    /* Mobile styles */
    @media (max-width: 768px) {
      .slide-content {
        padding: 0 5%;
      }

      .slide-counter {
        bottom: 20px;
        right: 20px;
      }

      .action-button {
        padding: 14px 28px;
      }
    }
  `;u([l({type:Array})],p.prototype,"slides",2);u([l({type:Number})],p.prototype,"autoplayInterval",2);u([l({type:Boolean})],p.prototype,"showNavigation",2);u([l({type:Boolean})],p.prototype,"showScrollHint",2);u([c()],p.prototype,"_currentSlide",2);u([c()],p.prototype,"_isAnimating",2);u([c()],p.prototype,"_autoplayTimer",2);u([c()],p.prototype,"_wheelTimeout",2);u([c()],p.prototype,"_scrollProgress",2);u([c()],p.prototype,"_touchStartX",2);u([c()],p.prototype,"_touchStartY",2);p=u([m("horizontal-scroll-hero")],p);var xe=Object.defineProperty,ye=Object.getOwnPropertyDescriptor,f=(e,i,r,o)=>{for(var t=o>1?void 0:o?ye(i,r):i,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=(o?s(i,r,t):s(t))||t);return o&&t&&xe(i,r,t),t};let g=class extends h{constructor(){super(...arguments),this.posts=[],this.title="Latest Articles",this.subtitle="Discover insights, tutorials, and updates from our team",this.showFilters=!0,this.showLoadMore=!1,this.showPagination=!1,this.postsPerPage=6,this._filter="all",this._currentPage=1,this._isLoading=!1,this._allCategories=[]}connectedCallback(){super.connectedCallback(),console.log("BlogListSection: connected, posts:",this.posts),this._extractCategories()}updated(e){e.has("posts")&&this._extractCategories()}_extractCategories(){const e=new Set;this.posts.forEach(i=>{e.add(i.category),i.tags?.forEach(r=>e.add(r))}),this._allCategories=Array.from(e)}_setFilter(e){this._filter=e,this._currentPage=1}get filteredPosts(){return this._filter==="all"?this.posts:this.posts.filter(e=>e.category===this._filter||e.tags?.includes(this._filter))}get paginatedPosts(){const e=(this._currentPage-1)*this.postsPerPage,i=e+this.postsPerPage;return this.filteredPosts.slice(e,i)}get totalPages(){return Math.ceil(this.filteredPosts.length/this.postsPerPage)}_loadMore(){this._isLoading||(this._isLoading=!0,setTimeout(()=>{this._currentPage++,this._isLoading=!1},1e3))}_goToPage(e){e<1||e>this.totalPages||e===this._currentPage||(this._currentPage=e,this.scrollIntoView({behavior:"smooth"}))}renderSkeleton(e=3){return Array(e).fill(0).map((i,r)=>a`
      <div class="blog-card">
        <div class="card-image skeleton skeleton-image"></div>
        <div class="card-content">
          <div class="card-meta">
            <div class="skeleton skeleton-text" style="width: 100px;"></div>
          </div>
          <div class="skeleton skeleton-title"></div>
          <div class="skeleton skeleton-text"></div>
          <div class="skeleton skeleton-text"></div>
          <div class="skeleton skeleton-text short"></div>
        </div>
      </div>
    `)}renderPost(e,i){const r=e.featured||i===0;return a`
      <article class="blog-card ${r?"featured":""}">
        ${r?a`
          <div class="featured-badge">Featured</div>
        `:""}
        
        <div class="card-image">
          <div class="category-badge">${e.category}</div>
          <img 
            src="${e.image}" 
            alt="${e.imageAlt}" 
            loading="lazy"
            @load=${o=>{const t=o.target;t.style.animation="none"}}
            @error=${o=>{const t=o.target;t.style.display="none"}}
          />
          <div class="image-overlay"></div>
        </div>
        
        <div class="card-content">
          <div class="card-meta">
            ${e.authorAvatar?a`
              <img src="${e.authorAvatar}" alt="${e.author}" class="author-avatar">
            `:""}
            <span class="author-name">${e.author}</span>
            <span class="meta-divider">•</span>
            <span class="post-date">${e.date}</span>
            <span class="meta-divider">•</span>
            <span class="read-time">⏱️ ${e.readTime}</span>
          </div>
          
          <h3 class="card-title">
            <a href="/blog/${e.slug}" class="link--none">
              ${e.title}
            </a>
          </h3>
          
          <p class="card-excerpt">${e.excerpt}</p>
          
          ${e.tags?.length?a`
            <div class="card-tags">
              ${e.tags.slice(0,3).map(o=>a`
                <span class="tag">#${o}</span>
              `)}
            </div>
          `:""}
          
          <div class="card-footer">
            <div class="stats">
              ${e.views?a`
                <span class="stat">👁️ ${e.views}</span>
              `:""}
              ${e.comments?a`
                <span class="stat">💬 ${e.comments}</span>
              `:""}
            </div>
            
            <a href="/blog/${e.slug}" class="read-more">
              Read More
              <span class="read-more-icon">→</span>
            </a>
          </div>
        </div>
      </article>
    `}render(){const e=this.showPagination?this.paginatedPosts:this.filteredPosts,i=this._isLoading;return console.log("BlogListSection: render, displayPosts:",e.length,"isLoading:",i),a`
      <section class="blog-section" hx-boost="true">
        <!-- Section Header -->
        <header class="section-header">
          <h2 class="section-title">${this.title}</h2>
          ${this.subtitle?a`
            <p class="section-subtitle">${this.subtitle}</p>
          `:""}
        </header>
        
        <!-- Filters -->
        ${this.showFilters&&this._allCategories.length>0?a`
          <div class="blog-filters">
            <button 
              class="filter-btn ${this._filter==="all"?"active":""}"
              @click=${()=>this._setFilter("all")}
            >
              All
            </button>
            ${this._allCategories.map(r=>a`
              <button 
                class="filter-btn ${this._filter===r?"active":""}"
                @click=${()=>this._setFilter(r)}
              >
                ${r}
              </button>
            `)}
          </div>
        `:""}
        
        <!-- Blog Grid -->
        <div class="blog-grid">
          ${i?this.renderSkeleton(this.postsPerPage):""}
          
          ${!i&&e.length>0?e.map((r,o)=>this.renderPost(r,o)):a`
                <div class="empty-state">
                  <div class="empty-icon">📝</div>
                  <h3>No articles found</h3>
                  <p>Try selecting a different category or check back later.</p>
                </div>
              `}
        </div>
        
        <!-- Load More Button -->
        ${this.showLoadMore&&this._currentPage<this.totalPages?a`
          <div class="load-more">
            <button 
              class="load-more-btn"
              @click=${this._loadMore}
              ?disabled=${i}
            >
              ${i?"Loading...":"Load More Articles"}
            </button>
          </div>
        `:""}
        
        <!-- Pagination -->
        ${this.showPagination&&this.totalPages>1?a`
          <nav class="blog-pagination" aria-label="Blog pagination">
            <button 
              class="pagination-btn ${this._currentPage===1?"disabled":""}"
              @click=${()=>this._goToPage(this._currentPage-1)}
              ?disabled=${this._currentPage===1}
              aria-label="Previous page"
            >
              ←
            </button>
            
            ${Array.from({length:Math.min(5,this.totalPages)},(r,o)=>{let t=o+1;return this.totalPages>5&&(this._currentPage<=3?t=o+1:this._currentPage>=this.totalPages-2?t=this.totalPages-4+o:t=this._currentPage-2+o),t<1||t>this.totalPages?"":a`
                <button 
                  class="pagination-btn ${this._currentPage===t?"active":""}"
                  @click=${()=>this._goToPage(t)}
                  aria-label=${`Page ${t}`}
                  aria-current=${this._currentPage===t?"page":"false"}
                >
                  ${t}
                </button>
              `})}
            
            <button 
              class="pagination-btn ${this._currentPage===this.totalPages?"disabled":""}"
              @click=${()=>this._goToPage(this._currentPage+1)}
              ?disabled=${this._currentPage===this.totalPages}
              aria-label="Next page"
            >
              →
            </button>
          </nav>
        `:""}
      </section>
    `}};g.styles=[v,d`
      :host {
        display: block;
        width: 100%;
        background: var(--color-bg, #0d1119);
      }

      /* Section Container */
      .blog-section {
        max-width: var(--width-max, 1440px);
        margin: 0 auto;
        padding: var(--spacing-3xl, 80px) var(--spacing-md, 20px);
      }

      /* Section Header */
      .section-header {
        text-align: center;
        margin-bottom: var(--spacing-3xl, 64px);
      }

      .section-title {
        font-family: var(--font-title, 'Roboto Condensed');
        font-size: var(--font-size-h2, 48px);
        font-weight: 700;
        line-height: 1.2;
        color: var(--color-text);
        margin-bottom: var(--spacing-md, 16px);
        background: linear-gradient(135deg, #FFFFFF 0%, var(--color-text-secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }

      .section-subtitle {
        font-size: 1.125rem;
        line-height: 1.6;
        color: var(--color-text-secondary);
        max-width: 600px;
        margin: 0 auto;
      }

      /* Filter Controls */
      .blog-filters {
        display: flex;
        flex-wrap: wrap;
        gap: var(--spacing-sm, 8px);
        justify-content: center;
        margin-bottom: var(--spacing-2xl, 48px);
      }

      .filter-btn {
        padding: 10px 20px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        color: var(--color-text-secondary);
        font-family: var(--font-main, Inter);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
      }

      .filter-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
      }

      .filter-btn.active {
        background: rgba(255, 87, 34, 0.2);
        border-color: rgba(255, 87, 34, 0.4);
        color: var(--color-text-brand);
      }

      /* Blog Grid */
      .blog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: var(--spacing-xl, 32px);
        margin-bottom: var(--spacing-2xl, 48px);
      }

      @media (max-width: 768px) {
        .blog-grid {
          grid-template-columns: 1fr;
          gap: var(--spacing-lg, 24px);
        }
      }

      /* Blog Card */
      .blog-card {
        background: var(--color-bg-layer, #0f131c);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(255, 255, 255, 0.05);
      }

      .blog-card:hover {
        transform: translateY(-8px);
        box-shadow: 
          0 20px 40px rgba(0, 0, 0, 0.3),
          0 0 0 1px rgba(255, 87, 34, 0.1);
        border-color: rgba(255, 87, 34, 0.2);
      }

      .blog-card.featured {
        grid-column: span 2;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-lg, 24px);
      }

      @media (max-width: 992px) {
        .blog-card.featured {
          grid-column: span 1;
          grid-template-columns: 1fr;
        }
      }

      /* Card Image */
      .card-image {
        position: relative;
        overflow: hidden;
        aspect-ratio: 16/9;
        background: linear-gradient(90deg, #1a1a2e 25%, #2a2a3e 50%, #1a1a2e 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
      }

      .blog-card.featured .card-image {
        height: 100%;
        aspect-ratio: auto;
      }

      .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, transparent 50%);
        opacity: 0;
        transition: opacity 0.3s ease;
      }

      .blog-card:hover .image-overlay {
        opacity: 1;
      }

      .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
      }

      .blog-card:hover .card-image img {
        transform: scale(1.05);
      }

      /* Category Badge */
      .category-badge {
        position: absolute;
        top: var(--spacing-md, 16px);
        left: var(--spacing-md, 16px);
        padding: 6px 12px;
        background: rgba(255, 87, 34, 0.9);
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 12px;
        z-index: 2;
        backdrop-filter: blur(10px);
      }

      /* Card Content */
      .card-content {
        padding: var(--spacing-lg, 24px);
        flex: 1;
        display: flex;
        flex-direction: column;
      }

      .card-meta {
        display: flex;
        align-items: center;
        gap: var(--spacing-sm, 12px);
        margin-bottom: var(--spacing-md, 16px);
        font-size: 0.875rem;
        color: var(--color-text-secondary);
      }

      .author-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
      }

      .author-name {
        font-weight: 500;
        color: var(--color-text);
      }

      .meta-divider {
        opacity: 0.5;
      }

      .read-time {
        display: flex;
        align-items: center;
        gap: 4px;
      }

      /* Card Title */
      .card-title {
        font-family: var(--font-title, 'Roboto Condensed');
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.3;
        color: var(--color-text);
        margin-bottom: var(--spacing-md, 16px);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }

      .blog-card.featured .card-title {
        font-size: 2rem;
        -webkit-line-clamp: 3;
      }

      /* Card Excerpt */
      .card-excerpt {
        font-size: 1rem;
        line-height: 1.6;
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-lg, 24px);
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }

      /* Tags */
      .card-tags {
        display: flex;
        flex-wrap: wrap;
        gap: var(--spacing-xs, 6px);
        margin-bottom: var(--spacing-lg, 24px);
      }

      .tag {
        padding: 4px 10px;
        background: rgba(255, 255, 255, 0.05);
        color: var(--color-text-secondary);
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 12px;
        transition: all 0.2s ease;
      }

      .tag:hover {
        background: rgba(255, 87, 34, 0.1);
        color: var(--color-text-brand);
      }

      /* Card Footer */
      .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: var(--spacing-md, 16px);
        border-top: 1px solid rgba(255, 255, 255, 0.05);
      }

      .stats {
        display: flex;
        gap: var(--spacing-lg, 20px);
        color: var(--color-text-secondary);
        font-size: 0.875rem;
      }

      .stat {
        display: flex;
        align-items: center;
        gap: 4px;
      }

      .read-more {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--color-text-brand);
        font-weight: 500;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.3s ease;
      }

      .read-more:hover {
        gap: 12px;
      }

      .read-more-icon {
        transition: transform 0.3s ease;
      }

      .read-more:hover .read-more-icon {
        transform: translateX(4px);
      }

      /* Featured Badge */
      .featured-badge {
        position: absolute;
        top: var(--spacing-md, 16px);
        right: var(--spacing-md, 16px);
        padding: 6px 12px;
        background: linear-gradient(135deg, #FF5722, #FF7043);
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 12px;
        z-index: 2;
        backdrop-filter: blur(10px);
      }

      /* Loading Skeleton */
      .skeleton {
        background: linear-gradient(90deg, #1a1a2e 25%, #2a2a3e 50%, #1a1a2e 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 8px;
      }

      .skeleton-image {
        aspect-ratio: 16/9;
        width: 100%;
      }

      .skeleton-title {
        height: 24px;
        width: 80%;
        margin-bottom: 12px;
      }

      .skeleton-text {
        height: 16px;
        width: 100%;
        margin-bottom: 8px;
      }

      .skeleton-text.short {
        width: 60%;
      }

      @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
      }

      /* Load More Button */
      .load-more {
        text-align: center;
        margin-top: var(--spacing-2xl, 48px);
      }

      .load-more-btn {
        padding: 16px 40px;
        background: rgba(255, 87, 34, 0.1);
        border: 1px solid rgba(255, 87, 34, 0.3);
        border-radius: 50px;
        color: var(--color-text-brand);
        font-family: var(--font-main, Inter);
        font-size: 0.875rem;
        font-weight: 500;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
      }

      .load-more-btn:hover {
        background: rgba(255, 87, 34, 0.2);
        border-color: rgba(255, 87, 34, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(255, 87, 34, 0.2);
      }

      /* Empty State */
      .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: var(--spacing-3xl, 80px) 0;
        color: var(--color-text-secondary);
      }

      .empty-icon {
        font-size: 3rem;
        margin-bottom: var(--spacing-lg, 24px);
        opacity: 0.5;
      }

      /* Pagination */
      .blog-pagination {
        display: flex;
        justify-content: center;
        gap: var(--spacing-sm, 8px);
        margin-top: var(--spacing-2xl, 48px);
      }

      .pagination-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--color-text-secondary);
        font-family: var(--font-main, Inter);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .pagination-btn:hover {
        background: rgba(255, 87, 34, 0.1);
        border-color: rgba(255, 87, 34, 0.3);
        color: var(--color-text-brand);
      }

      .pagination-btn.active {
        background: rgba(255, 87, 34, 0.2);
        border-color: rgba(255, 87, 34, 0.4);
        color: var(--color-text-brand);
      }

      .pagination-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
      }

      .pagination-btn.disabled:hover {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.1);
        color: var(--color-text-secondary);
      }
    `];f([l({type:Array})],g.prototype,"posts",2);f([l({type:String})],g.prototype,"title",2);f([l({type:String})],g.prototype,"subtitle",2);f([l({type:Boolean,attribute:"show-filters"})],g.prototype,"showFilters",2);f([l({type:Boolean,attribute:"show-load-more"})],g.prototype,"showLoadMore",2);f([l({type:Boolean,attribute:"show-pagination"})],g.prototype,"showPagination",2);f([l({type:Number,attribute:"posts-per-page"})],g.prototype,"postsPerPage",2);f([c()],g.prototype,"_filter",2);f([c()],g.prototype,"_currentPage",2);f([c()],g.prototype,"_isLoading",2);f([c()],g.prototype,"_allCategories",2);g=f([m("blog-list-section")],g);var we=Object.defineProperty,ke=Object.getOwnPropertyDescriptor,S=(e,i,r,o)=>{for(var t=o>1?void 0:o?ke(i,r):i,n=e.length-1,s;n>=0;n--)(s=e[n])&&(t=(o?s(i,r,t):s(t))||t);return o&&t&&we(i,r,t),t};let $=class extends h{constructor(){super(...arguments),this.showAuthor=!0,this.showRelated=!0,this.showActions=!0,this._liked=!1,this._currentUrl=""}connectedCallback(){if(super.connectedCallback(),this._currentUrl=window.location.href,this.article?.id){const e=localStorage.getItem(`article_${this.article.id}_liked`);e&&(this._liked=JSON.parse(e))}}_toggleLike(){this._liked=!this._liked,this.article?.id&&(localStorage.setItem(`article_${this.article.id}_liked`,JSON.stringify(this._liked)),this.article.meta&&(this.article.meta.likes=(this.article.meta.likes||0)+(this._liked?1:-1)),this.requestUpdate())}_shareToTwitter(){const e=encodeURIComponent(this._currentUrl),i=encodeURIComponent(this.article?.title||"Check out this article!");window.open(`https://twitter.com/intent/tweet?url=${e}&text=${i}`,"_blank")}_formatDate(e){return new Date(e).toLocaleDateString("en-US",{year:"numeric",month:"long",day:"numeric"})}renderArticleMeta(){return this.article?a`
      <div class="article-meta">
        <div class="publish-date">
          <span>📅</span>
          <time datetime=${this.article.createdAt}>
            ${this._formatDate(this.article.createdAt)}
          </time>
        </div>

        ${this.article.updatedAt&&this.article.updatedAt!==this.article.createdAt?a`
          <div class="update-date">
            <span>🔄</span>
            <time datetime=${this.article.updatedAt}>
              Updated: ${this._formatDate(this.article.updatedAt)}
            </time>
          </div>
        `:""}

        <div class="status-badge status-${this.article.status}">
          ${this.article.status}
        </div>
      </div>
    `:""}renderFeaturedImage(){return this.article?.featuredImage?a`
      <div class="featured-image">
        <img 
          src="${this.article.featuredImage}" 
          alt="${this.article.featuredImageAlt||this.article.title}" 
          loading="eager"
        />
      </div>
    `:""}renderAuthorInfo(){return!this.showAuthor||!this.article?.author?"":a`
      <div class="author-info">
        ${this.article.author.avatar?a`
          <img 
            src="${this.article.author.avatar}" 
            alt="${this.article.author.name}" 
            class="author-avatar"
          />
        `:""}
        <div class="author-details">
          <h4>${this.article.author.name}</h4>
          ${this.article.author.role?a`
            <p class="author-role">${this.article.author.role}</p>
          `:""}
        </div>
      </div>
    `}renderArticleActions(){return!this.showActions||!this.article?"":a`
      <div class="article-actions">
        <button 
          class="action-btn"
          @click=${this._toggleLike}
          aria-label=${this._liked?"Unlike article":"Like article"}
        >
          <span>${this._liked?"❤️":"🤍"}</span>
          ${this.article.meta?.likes||0}
        </button>
        
        <button 
          class="action-btn"
          @click=${this._shareToTwitter}
          aria-label="Share on Twitter"
        >
          <span>𝕏</span>
          Share
        </button>
        
        <a 
          href="/blog" 
          class="action-btn"
          hx-get="/blog"
          hx-target="#pjax-container"
          hx-push-url="true"
        >
          ← Back to Blog
        </a>
      </div>
    `}renderRelatedArticles(){return!this.showRelated||!this.article?.relatedArticles?.length?"":a`
      <div class="related-articles">
        <h3 class="related-title">Related Articles</h3>
        <div class="related-grid">
          ${this.article.relatedArticles.map(e=>a`
            <a 
              href="/blog/${e.slug}" 
              class="related-article"
              data-pjax
            >
              <div class="related-content">
                <h4 class="related-article-title">${e.title}</h4>
                <p class="related-excerpt">${e.excerpt}</p>
                <div class="related-date">
                  ${this._formatDate(e.createdAt)}
                </div>
              </div>
            </a>
          `)}
        </div>
      </div>
    `}render(){return this.article?a`
      <article class="article-container">
        <!-- Article Header -->
        <header class="article-header">
          <h1 class="article-title">${this.article.title}</h1>
          ${this.renderArticleMeta()}
        </header>

        <!-- Featured Image -->
        ${this.renderFeaturedImage()}

        <!-- Article Content -->
        <div class="article-content">
          ${K(this.article.content)}
        </div>

        <!-- Author Info -->
        ${this.renderAuthorInfo()}

        <!-- Article Actions -->
        ${this.renderArticleActions()}

        <!-- Related Articles -->
        ${this.renderRelatedArticles()}
      </article>
    `:a`
        <div class="article-container">
          <div style="text-align: center; padding: 4rem 0;">
            <h2>Article not found</h2>
            <p>The article you're looking for doesn't exist or has been removed.</p>
            <a href="/blog" class="action-btn" data-pjax>
              ← Back to Blog
            </a>
          </div>
        </div>
      `}};$.styles=[v,d`
      :host {
        display: block;
        width: 100%;
        background: var(--color-bg, #0d1119);
        color: var(--color-text);
      }

      /* Article Container */
      .article-container {
        max-width: min(1200px, 90vw);
        margin: 0 auto;
        padding: var(--spacing-2xl, 48px) var(--spacing-md, 20px);
      }

      /* Article Header */
      .article-header {
        text-align: center;
        margin-bottom: var(--spacing-2xl, 48px);
      }

      .article-title {
        font-family: var(--font-title, 'Roboto Condensed');
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: var(--spacing-md, 16px);
        color: var(--color-text);
      }

      .article-meta {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: var(--spacing-lg, 24px);
        flex-wrap: wrap;
        color: var(--color-text-secondary);
        font-size: 0.95rem;
      }

      .publish-date,
      .update-date {
        display: flex;
        align-items: center;
        gap: 6px;
      }

      .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
      }

      .status-published {
        background: rgba(76, 175, 80, 0.1);
        color: #4CAF50;
        border: 1px solid rgba(76, 175, 80, 0.3);
      }

      .status-draft {
        background: rgba(255, 193, 7, 0.1);
        color: #FFC107;
        border: 1px solid rgba(255, 193, 7, 0.3);
      }

      .status-archived {
        background: rgba(244, 67, 54, 0.1);
        color: #F44336;
        border: 1px solid rgba(244, 67, 54, 0.3);
      }

      /* Featured Image */
      .featured-image {
        width: 100%;
        margin: var(--spacing-2xl, 48px) 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      }

      .featured-image img {
        width: 100%;
        height: auto;
        max-height: 600px;
        object-fit: cover;
        display: block;
      }

      /* Article Content */
      .article-content {
        font-family: var(--font-main, Inter);
        font-size: 1.125rem;
        line-height: 1.8;
        color: var(--color-text);
        max-width: 800px;
        margin: 0 auto;
      }

      .article-content > * {
        margin-bottom: var(--spacing-xl, 32px);
      }

      .article-content h1,
      .article-content h2,
      .article-content h3,
      .article-content h4 {
        font-family: var(--font-title, 'Roboto Condensed');
        font-weight: 700;
        line-height: 1.3;
        margin-top: var(--spacing-2xl, 48px);
        margin-bottom: var(--spacing-lg, 24px);
        color: var(--color-text);
      }

      .article-content h2 {
        font-size: 2rem;
      }

      .article-content h3 {
        font-size: 1.5rem;
      }

      .article-content p {
        margin-bottom: var(--spacing-lg, 24px);
      }

      .article-content a {
        color: var(--color-text-brand);
        text-decoration: none;
        border-bottom: 1px solid rgba(255, 87, 34, 0.3);
        transition: all 0.3s ease;
      }

      .article-content a:hover {
        border-bottom-color: var(--color-text-brand);
      }

      .article-content blockquote {
        border-left: 4px solid var(--color-text-brand);
        padding-left: var(--spacing-lg, 24px);
        margin: var(--spacing-xl, 32px) 0;
        font-style: italic;
        color: var(--color-text-secondary);
        font-size: 1.25rem;
        line-height: 1.6;
      }

      .article-content code {
        font-family: var(--font-mono, 'JetBrains Mono');
        font-size: 0.875em;
        background: rgba(255, 255, 255, 0.05);
        padding: 2px 6px;
        border-radius: 4px;
        color: var(--color-text);
      }

      .article-content pre {
        background: var(--color-bg-layer);
        padding: var(--spacing-lg, 24px);
        border-radius: 8px;
        overflow-x: auto;
        margin: var(--spacing-xl, 32px) 0;
        border: 1px solid rgba(255, 255, 255, 0.05);
      }

      .article-content pre code {
        background: none;
        padding: 0;
        border-radius: 0;
        font-size: 0.875rem;
        line-height: 1.5;
      }

      .article-content ul,
      .article-content ol {
        padding-left: var(--spacing-xl, 32px);
        margin: var(--spacing-lg, 24px) 0;
      }

      .article-content li {
        margin-bottom: var(--spacing-sm, 12px);
      }

      .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: var(--spacing-xl, 32px) 0;
      }

      /* Article Actions */
      .article-actions {
        display: flex;
        justify-content: center;
        gap: var(--spacing-lg, 24px);
        margin: var(--spacing-2xl, 48px) 0;
        padding-top: var(--spacing-xl, 32px);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
      }

      .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 50px;
        color: var(--color-text);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
      }

      .action-btn:hover {
        background: rgba(255, 87, 34, 0.1);
        border-color: rgba(255, 87, 34, 0.3);
        transform: translateY(-2px);
      }

      /* Author Info */
      .author-info {
        display: flex;
        align-items: center;
        gap: var(--spacing-lg, 24px);
        margin: var(--spacing-2xl, 48px) auto;
        padding: var(--spacing-xl, 32px);
        background: var(--color-bg-layer);
        border-radius: 16px;
        max-width: 800px;
        border: 1px solid rgba(255, 255, 255, 0.05);
      }

      .author-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 87, 34, 0.3);
      }

      .author-details h4 {
        margin: 0 0 8px 0;
        font-size: 1.25rem;
      }

      .author-role {
        color: var(--color-text-secondary);
        font-size: 0.95rem;
      }

      /* Related Articles */
      .related-articles {
        margin-top: var(--spacing-3xl, 80px);
        padding-top: var(--spacing-2xl, 48px);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
      }

      .related-title {
        font-family: var(--font-title, 'Roboto Condensed');
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: var(--spacing-xl, 32px);
        color: var(--color-text);
        text-align: center;
      }

      .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: var(--spacing-lg, 24px);
        max-width: 1200px;
        margin: 0 auto;
      }

      .related-article {
        background: var(--color-bg-layer);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.05);
        text-decoration: none;
        display: block;
      }

      .related-article:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        border-color: rgba(255, 87, 34, 0.2);
      }

      .related-content {
        padding: var(--spacing-lg, 24px);
      }

      .related-article-title {
        font-family: var(--font-title, 'Roboto Condensed');
        font-size: 1.125rem;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: var(--spacing-sm, 12px);
        color: var(--color-text);
      }

      .related-excerpt {
        font-size: 0.875rem;
        line-height: 1.5;
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-md, 16px);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }

      .related-date {
        font-size: 0.75rem;
        color: var(--color-text-secondary);
      }

      /* Mobile Styles */
      @media (max-width: 768px) {
        .article-container {
          padding: var(--spacing-xl, 32px) var(--spacing-sm, 12px);
        }

        .article-meta {
          flex-direction: column;
          gap: var(--spacing-md, 16px);
        }

        .author-info {
          flex-direction: column;
          text-align: center;
          padding: var(--spacing-lg, 24px);
        }

        .related-grid {
          grid-template-columns: 1fr;
        }
      }
    `];S([l({type:Object})],$.prototype,"article",2);S([l({type:Boolean})],$.prototype,"showAuthor",2);S([l({type:Boolean})],$.prototype,"showRelated",2);S([l({type:Boolean})],$.prototype,"showActions",2);S([c()],$.prototype,"_liked",2);S([c()],$.prototype,"_currentUrl",2);$=S([m("article-detail-section")],$);export{v as s};
