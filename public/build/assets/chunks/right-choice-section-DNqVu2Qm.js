import{i as u,s as y,a as x,b as L}from"../app.js";class m extends u{static styles=[y,x`
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
    `];static animationConfig={blockDuration:7e3,transitionDuration:500,animationDistance:800};constructor(){super(),this.animationState={currentStage:0,progressDirection:1,startTime:0,animationId:null},this.elements={topLeft:null,topRight:null,bottomLeft:null,bottomRight:null,progressDots:null},this.isMobile=!1}firstUpdated(){this.elements.topLeft=this.shadowRoot.querySelector(".content-top .content-left .inner"),this.elements.topRight=this.shadowRoot.querySelector(".content-top .content-right .inner"),this.elements.bottomLeft=this.shadowRoot.querySelector(".content-bottom .content-left .inner"),this.elements.bottomRight=this.shadowRoot.querySelector(".content-bottom .content-right .inner"),this.elements.progressDots=this.shadowRoot.querySelectorAll(".dots"),this.checkMobile(),this.startAnimation(),window.addEventListener("orientationchange",()=>{setTimeout(()=>{this.checkMobile()},100)}),window.addEventListener("resize",()=>this.checkMobile())}disconnectedCallback(){super.disconnectedCallback(),this.stopAnimation(),window.removeEventListener("orientationchange",this.checkMobile),window.removeEventListener("resize",this.checkMobile)}checkMobile(){const t=this.isMobile;this.isMobile=window.matchMedia("(orientation: portrait)").matches,t!==this.isMobile&&this.elements.topLeft&&this.elements.topRight&&this.elements.bottomLeft&&this.elements.bottomRight&&(this.isMobile?(this.elements.topLeft.style.transform="",this.elements.topRight.style.transform="",this.elements.bottomLeft.style.transform="",this.elements.bottomRight.style.transform=""):this.resetMobileClasses())}resetMobileClasses(){[this.shadowRoot.querySelector(".content-top .content-left"),this.shadowRoot.querySelector(".content-top .content-right"),this.shadowRoot.querySelector(".content-bottom .content-left"),this.shadowRoot.querySelector(".content-bottom .content-right")].forEach(o=>{o&&o.classList.remove("mobile-hidden","mobile-visible")})}startAnimation(){this.animationState.startTime=Date.now(),this.animate()}stopAnimation(){this.animationState.animationId&&(cancelAnimationFrame(this.animationState.animationId),this.animationState.animationId=null)}animate(){const t=m.animationConfig,s=Date.now()-this.animationState.startTime,i=t.blockDuration*4+t.transitionDuration*4,e=s%i,d=t.blockDuration,h=d+t.transitionDuration,g=h+t.blockDuration,p=g+t.transitionDuration,v=p+t.blockDuration,f=v+t.transitionDuration,b=f+t.blockDuration;b+t.transitionDuration;let a=0,r=0,l=!1;if(e<d)a=e/t.blockDuration*.5,r=0,l=!1;else if(e<h){const n=(e-d)/t.transitionDuration;a=.5,r=n,l=n>.5}else if(e<g)a=.5+(e-h)/t.blockDuration*.5,r=1,l=!0;else if(e<p){const n=(e-g)/t.transitionDuration;a=1,r=1-n,l=n<.5}else if(e<v)a=1-(e-p)/t.blockDuration*.5,r=0,l=!1;else if(e<f){const n=(e-v)/t.transitionDuration;a=.5,r=n,l=n>.5}else if(e<b)a=.5-(e-f)/t.blockDuration*.5,r=1,l=!0;else{const n=(e-b)/t.transitionDuration;a=0,r=1-n,l=n<.5}this.isMobile?this.animateMobileElements(l):this.animateDesktopElements(r),this.updateProgressBar(a),this.animationState.animationId=requestAnimationFrame(()=>this.animate())}animateDesktopElements(t){const s=m.animationConfig.animationDistance;if(!this.elements.topLeft||!this.elements.topRight||!this.elements.bottomLeft||!this.elements.bottomRight)return;const i=t*s,e=Math.min(0,-s+t*s),d=-(t*s),h=Math.max(0,s-t*s);this.elements.topLeft.style.transform=`translateX(${i}px)`,this.elements.topRight.style.transform=`translateX(${e}px)`,this.elements.bottomRight.style.transform=`translateX(${d}px)`,this.elements.bottomLeft.style.transform=`translateX(${h}px)`}animateMobileElements(t){const o=this.shadowRoot.querySelector(".content-top .content-left"),s=this.shadowRoot.querySelector(".content-top .content-right"),i=this.shadowRoot.querySelector(".content-bottom .content-left"),e=this.shadowRoot.querySelector(".content-bottom .content-right");!o||!s||!i||!e||(t?(o.classList.add("mobile-hidden"),o.classList.remove("mobile-visible"),s.classList.add("mobile-visible"),s.classList.remove("mobile-hidden")):(o.classList.add("mobile-visible"),o.classList.remove("mobile-hidden"),s.classList.add("mobile-hidden"),s.classList.remove("mobile-visible")),t?(i.classList.add("mobile-hidden"),i.classList.remove("mobile-visible"),e.classList.add("mobile-visible"),e.classList.remove("mobile-hidden")):(i.classList.add("mobile-visible"),i.classList.remove("mobile-hidden"),e.classList.add("mobile-hidden"),e.classList.remove("mobile-visible")))}updateProgressBar(t){if(!this.elements.progressDots||this.elements.progressDots.length===0)return;const o=this.elements.progressDots.length,s=Math.floor(t*o);this.elements.progressDots.forEach((i,e)=>{e<s?(i.classList.remove("grey"),i.classList.add("red")):(i.classList.remove("red"),i.classList.add("grey"))})}render(){return L`
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
        `}}customElements.define("right-choice-section",m);export{m as RightChoiceSection};
