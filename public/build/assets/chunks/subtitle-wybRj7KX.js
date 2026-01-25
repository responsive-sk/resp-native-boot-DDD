import{i as e,s,a as t,b as i}from"../app.js";class n extends e{static styles=[s,t`
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
    `];render(){return i`
            <div class="container">
                <img class="img" src="/images/icons/subtitle.svg" alt="subtitle"/>

                <h6 class="name">
                    <slot></slot>
                </h6>
            </div>
        `}}customElements.define("boson-subtitle",n);export{n as Subtitle};
