import{i as t,s as e,a as r,b as s}from"../app.js";class o extends t{static styles=[e,r`
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
    `];constructor(){super()}render(){return s`
            <nav class="breadcrumbs">
                <slot></slot>
            </nav>
        `}}customElements.define("boson-breadcrumbs",o);export{o as BosonBreadcrumbs};
