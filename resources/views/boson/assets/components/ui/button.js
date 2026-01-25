import {css, html, LitElement} from 'lit';

import {sharedStyles} from "../../utils/sharedStyles.js";

export class Button extends LitElement {
    static properties = {
        href: {type: String},
        external: {type: Boolean},
        type: {type: String},
        icon: {type: String},
        iconWidth: {type: String},
        iconHeight: {type: String},
        active: {type: Boolean},
    };

    static styles = [sharedStyles, css`
        :host {
            display: inline-block;
            line-height: var(--height-ui);
            height: var(--height-ui);
            justify-content: center;
        }

        .button {
            font-family: var(--font-title), sans-serif;
            font-size: var(--font-size-secondary);
            letter-spacing: 1px;
            color: var(--color-text-button);
            transition-duration: .1s;
            background: var(--color-bg-button);
            text-transform: uppercase;
            height: 100%;
            padding: 0 2em;
            display: flex;
            gap: 1em;
            justify-content: inherit;
            align-items: center;
            white-space: nowrap;
            text-decoration: none;
        }

        span.button {
            cursor: default;
        }

        .button-active,
        a.button:hover {
            text-decoration: none;
            transition-duration: 0s;
            background: var(--color-bg-button-hover);
            color: var(--color-text-button);
        }

        .icon {
            aspect-ratio: 1 / 1;
            height: 32px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--color-text-button);
            margin: 0 -1em 0 0;
            user-select: none;
        }

        .icon .img {
            height: var(--font-size);
            margin: -2px 0 0 0;
        }

        /** SECONDARY */

        .button.button-secondary {
            background: var(--color-bg-button-secondary);
            color: var(--color-text);
        }

        .button.button-secondary.button-active,
        a.button.button-secondary:hover {
            background: var(--color-bg-button-secondary-hover);
        }

        .button.button-secondary .text {
            color: var(--color-text-button-secondary);
        }

        .button.button-secondary .icon {
            background: var(--color-text-button-secondary);
        }

        /** GHOST */

        .button.button-ghost {
            background: rgba(var(--color-bg-hover), 0);
            color: var(--color-text-secondary );
        }

        .button.button-ghost.button-active,
        a.button.button-ghost:hover {
            background: var(--color-bg-hover);
            color: var(--color-text);
        }

        .button.button-ghost .text {
            color: var(--color-text-button-secondary);
        }

        .button.button-ghost .icon {
            background: none;
            margin: 0 -1em 0 -.5em;
        }

        /** OTHER */

        ::slotted(img.logo) {
            height: 50%;
        }


        :host([inheader="true"]) {
            align-self: stretch;
            justify-content: flex-start;
        }
    `];

    constructor() {
        super();

        this.href = '';
        this.type = 'primary';
        this.icon = '';
        this.iconWidth = '';
        this.iconHeight = '';
        this.external = false;
        this.active = false;
    }

    render() {
        if (this.href === '') {
            return html`
                <span class="button button-${this.type} ${this.active ? 'button-active' : ''}">
                    <slot></slot>

                    <span class="icon" style="${this.icon === '' ? 'display:none': ''}">
                        <img class="img" src="${this.icon}" alt="arrow" width="${this.iconWidth}" height="${this.iconHeight}" />
                    </span>
                </span>
            `;
        }

        return html`
            <a href="${this.href}"
               class="button button-${this.type} ${this.active ? 'button-active' : ''}"
               target="${this.external ? '_blank' : '_self'}">
                <slot></slot>

                <span class="icon" style="${this.icon === '' ? 'display:none': ''}">
                    <img class="img" src="${this.icon}" alt="arrow" width="${this.iconWidth}" height="${this.iconHeight}" />
                </span>
            </a>
        `;
    }
}

customElements.define('boson-button', Button);
