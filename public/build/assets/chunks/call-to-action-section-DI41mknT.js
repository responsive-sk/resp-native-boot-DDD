import{i as t,s as e,a,b as o}from"../app.js";class r extends t{static styles=[e,a`
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
    `];render(){return o`
            <section class="container">
                <div class="wrapper">
                    <div class="text">
                        <slot></slot>
                    </div>

                    <slot name="footer"></slot>
                </div>
            </section>
        `}}customElements.define("call-to-action-section",r);export{r as CallToActionSection};
