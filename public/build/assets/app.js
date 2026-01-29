const gt="modulepreload",ft=function(r){return"/"+r},Z={},f=function(t,e,o){let i=Promise.resolve();if(e&&e.length>0){let a=function(d){return Promise.all(d.map(h=>Promise.resolve(h).then(l=>({status:"fulfilled",value:l}),l=>({status:"rejected",reason:l}))))};document.getElementsByTagName("link");const n=document.querySelector("meta[property=csp-nonce]"),c=n?.nonce||n?.getAttribute("nonce");i=a(e.map(d=>{if(d=ft(d),d in Z)return;Z[d]=!0;const h=d.endsWith(".css"),l=h?'[rel="stylesheet"]':"";if(document.querySelector(`link[href="${d}"]${l}`))return;const u=document.createElement("link");if(u.rel=h?"stylesheet":gt,h||(u.as="script"),u.crossOrigin="",u.href=d,c&&u.setAttribute("nonce",c),document.head.appendChild(u),h)return new Promise((b,w)=>{u.addEventListener("load",b),u.addEventListener("error",()=>w(new Error(`Unable to preload CSS for ${d}`)))})}))}function s(n){const c=new Event("vite:preloadError",{cancelable:!0});if(c.payload=n,window.dispatchEvent(c),!c.defaultPrevented)throw n}return i.then(n=>{for(const c of n||[])c.status==="rejected"&&s(c.reason);return t().catch(s)})};const R=globalThis,B=R.ShadowRoot&&(R.ShadyCSS===void 0||R.ShadyCSS.nativeShadow)&&"adoptedStyleSheets"in Document.prototype&&"replace"in CSSStyleSheet.prototype,V=Symbol(),J=new WeakMap;let lt=class{constructor(t,e,o){if(this._$cssResult$=!0,o!==V)throw Error("CSSResult is not constructable. Use `unsafeCSS` or `css` instead.");this.cssText=t,this.t=e}get styleSheet(){let t=this.o;const e=this.t;if(B&&t===void 0){const o=e!==void 0&&e.length===1;o&&(t=J.get(e)),t===void 0&&((this.o=t=new CSSStyleSheet).replaceSync(this.cssText),o&&J.set(e,t))}return t}toString(){return this.cssText}};const ct=r=>new lt(typeof r=="string"?r:r+"",void 0,V),v=(r,...t)=>{const e=r.length===1?r[0]:t.reduce((o,i,s)=>o+(n=>{if(n._$cssResult$===!0)return n.cssText;if(typeof n=="number")return n;throw Error("Value passed to 'css' function must be a 'css' function result: "+n+". Use 'unsafeCSS' to pass non-literal values, but take care to ensure page security.")})(i)+r[s+1],r[0]);return new lt(e,r,V)},vt=(r,t)=>{if(B)r.adoptedStyleSheets=t.map(e=>e instanceof CSSStyleSheet?e:e.styleSheet);else for(const e of t){const o=document.createElement("style"),i=R.litNonce;i!==void 0&&o.setAttribute("nonce",i),o.textContent=e.cssText,r.appendChild(o)}},K=B?r=>r:r=>r instanceof CSSStyleSheet?(t=>{let e="";for(const o of t.cssRules)e+=o.cssText;return ct(e)})(r):r;const{is:bt,defineProperty:xt,getOwnPropertyDescriptor:yt,getOwnPropertyNames:$t,getOwnPropertySymbols:_t,getPrototypeOf:wt}=Object,H=globalThis,X=H.trustedTypes,At=X?X.emptyScript:"",Et=H.reactiveElementPolyfillSupport,P=(r,t)=>r,N={toAttribute(r,t){switch(t){case Boolean:r=r?At:null;break;case Object:case Array:r=r==null?r:JSON.stringify(r)}return r},fromAttribute(r,t){let e=r;switch(t){case Boolean:e=r!==null;break;case Number:e=r===null?null:Number(r);break;case Object:case Array:try{e=JSON.parse(r)}catch{e=null}}return e}},dt=(r,t)=>!bt(r,t),G={attribute:!0,type:String,converter:N,reflect:!1,useDefault:!1,hasChanged:dt};Symbol.metadata??=Symbol("metadata"),H.litPropertyMetadata??=new WeakMap;let A=class extends HTMLElement{static addInitializer(t){this._$Ei(),(this.l??=[]).push(t)}static get observedAttributes(){return this.finalize(),this._$Eh&&[...this._$Eh.keys()]}static createProperty(t,e=G){if(e.state&&(e.attribute=!1),this._$Ei(),this.prototype.hasOwnProperty(t)&&((e=Object.create(e)).wrapped=!0),this.elementProperties.set(t,e),!e.noAccessor){const o=Symbol(),i=this.getPropertyDescriptor(t,o,e);i!==void 0&&xt(this.prototype,t,i)}}static getPropertyDescriptor(t,e,o){const{get:i,set:s}=yt(this.prototype,t)??{get(){return this[e]},set(n){this[e]=n}};return{get:i,set(n){const c=i?.call(this);s?.call(this,n),this.requestUpdate(t,c,o)},configurable:!0,enumerable:!0}}static getPropertyOptions(t){return this.elementProperties.get(t)??G}static _$Ei(){if(this.hasOwnProperty(P("elementProperties")))return;const t=wt(this);t.finalize(),t.l!==void 0&&(this.l=[...t.l]),this.elementProperties=new Map(t.elementProperties)}static finalize(){if(this.hasOwnProperty(P("finalized")))return;if(this.finalized=!0,this._$Ei(),this.hasOwnProperty(P("properties"))){const e=this.properties,o=[...$t(e),..._t(e)];for(const i of o)this.createProperty(i,e[i])}const t=this[Symbol.metadata];if(t!==null){const e=litPropertyMetadata.get(t);if(e!==void 0)for(const[o,i]of e)this.elementProperties.set(o,i)}this._$Eh=new Map;for(const[e,o]of this.elementProperties){const i=this._$Eu(e,o);i!==void 0&&this._$Eh.set(i,e)}this.elementStyles=this.finalizeStyles(this.styles)}static finalizeStyles(t){const e=[];if(Array.isArray(t)){const o=new Set(t.flat(1/0).reverse());for(const i of o)e.unshift(K(i))}else t!==void 0&&e.push(K(t));return e}static _$Eu(t,e){const o=e.attribute;return o===!1?void 0:typeof o=="string"?o:typeof t=="string"?t.toLowerCase():void 0}constructor(){super(),this._$Ep=void 0,this.isUpdatePending=!1,this.hasUpdated=!1,this._$Em=null,this._$Ev()}_$Ev(){this._$ES=new Promise(t=>this.enableUpdating=t),this._$AL=new Map,this._$E_(),this.requestUpdate(),this.constructor.l?.forEach(t=>t(this))}addController(t){(this._$EO??=new Set).add(t),this.renderRoot!==void 0&&this.isConnected&&t.hostConnected?.()}removeController(t){this._$EO?.delete(t)}_$E_(){const t=new Map,e=this.constructor.elementProperties;for(const o of e.keys())this.hasOwnProperty(o)&&(t.set(o,this[o]),delete this[o]);t.size>0&&(this._$Ep=t)}createRenderRoot(){const t=this.shadowRoot??this.attachShadow(this.constructor.shadowRootOptions);return vt(t,this.constructor.elementStyles),t}connectedCallback(){this.renderRoot??=this.createRenderRoot(),this.enableUpdating(!0),this._$EO?.forEach(t=>t.hostConnected?.())}enableUpdating(t){}disconnectedCallback(){this._$EO?.forEach(t=>t.hostDisconnected?.())}attributeChangedCallback(t,e,o){this._$AK(t,o)}_$ET(t,e){const o=this.constructor.elementProperties.get(t),i=this.constructor._$Eu(t,o);if(i!==void 0&&o.reflect===!0){const s=(o.converter?.toAttribute!==void 0?o.converter:N).toAttribute(e,o.type);this._$Em=t,s==null?this.removeAttribute(i):this.setAttribute(i,s),this._$Em=null}}_$AK(t,e){const o=this.constructor,i=o._$Eh.get(t);if(i!==void 0&&this._$Em!==i){const s=o.getPropertyOptions(i),n=typeof s.converter=="function"?{fromAttribute:s.converter}:s.converter?.fromAttribute!==void 0?s.converter:N;this._$Em=i;const c=n.fromAttribute(e,s.type);this[i]=c??this._$Ej?.get(i)??c,this._$Em=null}}requestUpdate(t,e,o,i=!1,s){if(t!==void 0){const n=this.constructor;if(i===!1&&(s=this[t]),o??=n.getPropertyOptions(t),!((o.hasChanged??dt)(s,e)||o.useDefault&&o.reflect&&s===this._$Ej?.get(t)&&!this.hasAttribute(n._$Eu(t,o))))return;this.C(t,e,o)}this.isUpdatePending===!1&&(this._$ES=this._$EP())}C(t,e,{useDefault:o,reflect:i,wrapped:s},n){o&&!(this._$Ej??=new Map).has(t)&&(this._$Ej.set(t,n??e??this[t]),s!==!0||n!==void 0)||(this._$AL.has(t)||(this.hasUpdated||o||(e=void 0),this._$AL.set(t,e)),i===!0&&this._$Em!==t&&(this._$Eq??=new Set).add(t))}async _$EP(){this.isUpdatePending=!0;try{await this._$ES}catch(e){Promise.reject(e)}const t=this.scheduleUpdate();return t!=null&&await t,!this.isUpdatePending}scheduleUpdate(){return this.performUpdate()}performUpdate(){if(!this.isUpdatePending)return;if(!this.hasUpdated){if(this.renderRoot??=this.createRenderRoot(),this._$Ep){for(const[i,s]of this._$Ep)this[i]=s;this._$Ep=void 0}const o=this.constructor.elementProperties;if(o.size>0)for(const[i,s]of o){const{wrapped:n}=s,c=this[i];n!==!0||this._$AL.has(i)||c===void 0||this.C(i,void 0,s,c)}}let t=!1;const e=this._$AL;try{t=this.shouldUpdate(e),t?(this.willUpdate(e),this._$EO?.forEach(o=>o.hostUpdate?.()),this.update(e)):this._$EM()}catch(o){throw t=!1,this._$EM(),o}t&&this._$AE(e)}willUpdate(t){}_$AE(t){this._$EO?.forEach(e=>e.hostUpdated?.()),this.hasUpdated||(this.hasUpdated=!0,this.firstUpdated(t)),this.updated(t)}_$EM(){this._$AL=new Map,this.isUpdatePending=!1}get updateComplete(){return this.getUpdateComplete()}getUpdateComplete(){return this._$ES}shouldUpdate(t){return!0}update(t){this._$Eq&&=this._$Eq.forEach(e=>this._$ET(e,this[e])),this._$EM()}updated(t){}firstUpdated(t){}};A.elementStyles=[],A.shadowRootOptions={mode:"open"},A[P("elementProperties")]=new Map,A[P("finalized")]=new Map,Et?.({ReactiveElement:A}),(H.reactiveElementVersions??=[]).push("2.1.2");const W=globalThis,Q=r=>r,U=W.trustedTypes,tt=U?U.createPolicy("lit-html",{createHTML:r=>r}):void 0,ht="$lit$",x=`lit$${Math.random().toFixed(9).slice(2)}$`,pt="?"+x,kt=`<${pt}>`,_=document,L=()=>_.createComment(""),O=r=>r===null||typeof r!="object"&&typeof r!="function",F=Array.isArray,St=r=>F(r)||typeof r?.[Symbol.iterator]=="function",M=`[ 	
\f\r]`,C=/<(?:(!--|\/[^a-zA-Z])|(\/?[a-zA-Z][^>\s]*)|(\/?$))/g,et=/-->/g,ot=/>/g,y=RegExp(`>|${M}(?:([^\\s"'>=/]+)(${M}*=${M}*(?:[^ 	
\f\r"'\`<>=]|("|')|))|$)`,"g"),it=/'/g,rt=/"/g,ut=/^(?:script|style|textarea|title)$/i,zt=r=>(t,...e)=>({_$litType$:r,strings:t,values:e}),m=zt(1),k=Symbol.for("lit-noChange"),p=Symbol.for("lit-nothing"),st=new WeakMap,$=_.createTreeWalker(_,129);function mt(r,t){if(!F(r)||!r.hasOwnProperty("raw"))throw Error("invalid template strings array");return tt!==void 0?tt.createHTML(t):t}const Ct=(r,t)=>{const e=r.length-1,o=[];let i,s=t===2?"<svg>":t===3?"<math>":"",n=C;for(let c=0;c<e;c++){const a=r[c];let d,h,l=-1,u=0;for(;u<a.length&&(n.lastIndex=u,h=n.exec(a),h!==null);)u=n.lastIndex,n===C?h[1]==="!--"?n=et:h[1]!==void 0?n=ot:h[2]!==void 0?(ut.test(h[2])&&(i=RegExp("</"+h[2],"g")),n=y):h[3]!==void 0&&(n=y):n===y?h[0]===">"?(n=i??C,l=-1):h[1]===void 0?l=-2:(l=n.lastIndex-h[2].length,d=h[1],n=h[3]===void 0?y:h[3]==='"'?rt:it):n===rt||n===it?n=y:n===et||n===ot?n=C:(n=y,i=void 0);const b=n===y&&r[c+1].startsWith("/>")?" ":"";s+=n===C?a+kt:l>=0?(o.push(d),a.slice(0,l)+ht+a.slice(l)+x+b):a+x+(l===-2?c:b)}return[mt(r,s+(r[e]||"<?>")+(t===2?"</svg>":t===3?"</math>":"")),o]};class T{constructor({strings:t,_$litType$:e},o){let i;this.parts=[];let s=0,n=0;const c=t.length-1,a=this.parts,[d,h]=Ct(t,e);if(this.el=T.createElement(d,o),$.currentNode=this.el.content,e===2||e===3){const l=this.el.content.firstChild;l.replaceWith(...l.childNodes)}for(;(i=$.nextNode())!==null&&a.length<c;){if(i.nodeType===1){if(i.hasAttributes())for(const l of i.getAttributeNames())if(l.endsWith(ht)){const u=h[n++],b=i.getAttribute(l).split(x),w=/([.?@])?(.*)/.exec(u);a.push({type:1,index:s,name:w[2],strings:b,ctor:w[1]==="."?Lt:w[1]==="?"?Ot:w[1]==="@"?Tt:I}),i.removeAttribute(l)}else l.startsWith(x)&&(a.push({type:6,index:s}),i.removeAttribute(l));if(ut.test(i.tagName)){const l=i.textContent.split(x),u=l.length-1;if(u>0){i.textContent=U?U.emptyScript:"";for(let b=0;b<u;b++)i.append(l[b],L()),$.nextNode(),a.push({type:2,index:++s});i.append(l[u],L())}}}else if(i.nodeType===8)if(i.data===pt)a.push({type:2,index:s});else{let l=-1;for(;(l=i.data.indexOf(x,l+1))!==-1;)a.push({type:7,index:s}),l+=x.length-1}s++}}static createElement(t,e){const o=_.createElement("template");return o.innerHTML=t,o}}function S(r,t,e=r,o){if(t===k)return t;let i=o!==void 0?e._$Co?.[o]:e._$Cl;const s=O(t)?void 0:t._$litDirective$;return i?.constructor!==s&&(i?._$AO?.(!1),s===void 0?i=void 0:(i=new s(r),i._$AT(r,e,o)),o!==void 0?(e._$Co??=[])[o]=i:e._$Cl=i),i!==void 0&&(t=S(r,i._$AS(r,t.values),i,o)),t}class Pt{constructor(t,e){this._$AV=[],this._$AN=void 0,this._$AD=t,this._$AM=e}get parentNode(){return this._$AM.parentNode}get _$AU(){return this._$AM._$AU}u(t){const{el:{content:e},parts:o}=this._$AD,i=(t?.creationScope??_).importNode(e,!0);$.currentNode=i;let s=$.nextNode(),n=0,c=0,a=o[0];for(;a!==void 0;){if(n===a.index){let d;a.type===2?d=new q(s,s.nextSibling,this,t):a.type===1?d=new a.ctor(s,a.name,a.strings,this,t):a.type===6&&(d=new qt(s,this,t)),this._$AV.push(d),a=o[++c]}n!==a?.index&&(s=$.nextNode(),n++)}return $.currentNode=_,i}p(t){let e=0;for(const o of this._$AV)o!==void 0&&(o.strings!==void 0?(o._$AI(t,o,e),e+=o.strings.length-2):o._$AI(t[e])),e++}}class q{get _$AU(){return this._$AM?._$AU??this._$Cv}constructor(t,e,o,i){this.type=2,this._$AH=p,this._$AN=void 0,this._$AA=t,this._$AB=e,this._$AM=o,this.options=i,this._$Cv=i?.isConnected??!0}get parentNode(){let t=this._$AA.parentNode;const e=this._$AM;return e!==void 0&&t?.nodeType===11&&(t=e.parentNode),t}get startNode(){return this._$AA}get endNode(){return this._$AB}_$AI(t,e=this){t=S(this,t,e),O(t)?t===p||t==null||t===""?(this._$AH!==p&&this._$AR(),this._$AH=p):t!==this._$AH&&t!==k&&this._(t):t._$litType$!==void 0?this.$(t):t.nodeType!==void 0?this.T(t):St(t)?this.k(t):this._(t)}O(t){return this._$AA.parentNode.insertBefore(t,this._$AB)}T(t){this._$AH!==t&&(this._$AR(),this._$AH=this.O(t))}_(t){this._$AH!==p&&O(this._$AH)?this._$AA.nextSibling.data=t:this.T(_.createTextNode(t)),this._$AH=t}$(t){const{values:e,_$litType$:o}=t,i=typeof o=="number"?this._$AC(t):(o.el===void 0&&(o.el=T.createElement(mt(o.h,o.h[0]),this.options)),o);if(this._$AH?._$AD===i)this._$AH.p(e);else{const s=new Pt(i,this),n=s.u(this.options);s.p(e),this.T(n),this._$AH=s}}_$AC(t){let e=st.get(t.strings);return e===void 0&&st.set(t.strings,e=new T(t)),e}k(t){F(this._$AH)||(this._$AH=[],this._$AR());const e=this._$AH;let o,i=0;for(const s of t)i===e.length?e.push(o=new q(this.O(L()),this.O(L()),this,this.options)):o=e[i],o._$AI(s),i++;i<e.length&&(this._$AR(o&&o._$AB.nextSibling,i),e.length=i)}_$AR(t=this._$AA.nextSibling,e){for(this._$AP?.(!1,!0,e);t!==this._$AB;){const o=Q(t).nextSibling;Q(t).remove(),t=o}}setConnected(t){this._$AM===void 0&&(this._$Cv=t,this._$AP?.(t))}}class I{get tagName(){return this.element.tagName}get _$AU(){return this._$AM._$AU}constructor(t,e,o,i,s){this.type=1,this._$AH=p,this._$AN=void 0,this.element=t,this.name=e,this._$AM=i,this.options=s,o.length>2||o[0]!==""||o[1]!==""?(this._$AH=Array(o.length-1).fill(new String),this.strings=o):this._$AH=p}_$AI(t,e=this,o,i){const s=this.strings;let n=!1;if(s===void 0)t=S(this,t,e,0),n=!O(t)||t!==this._$AH&&t!==k,n&&(this._$AH=t);else{const c=t;let a,d;for(t=s[0],a=0;a<s.length-1;a++)d=S(this,c[o+a],e,a),d===k&&(d=this._$AH[a]),n||=!O(d)||d!==this._$AH[a],d===p?t=p:t!==p&&(t+=(d??"")+s[a+1]),this._$AH[a]=d}n&&!i&&this.j(t)}j(t){t===p?this.element.removeAttribute(this.name):this.element.setAttribute(this.name,t??"")}}class Lt extends I{constructor(){super(...arguments),this.type=3}j(t){this.element[this.name]=t===p?void 0:t}}class Ot extends I{constructor(){super(...arguments),this.type=4}j(t){this.element.toggleAttribute(this.name,!!t&&t!==p)}}class Tt extends I{constructor(t,e,o,i,s){super(t,e,o,i,s),this.type=5}_$AI(t,e=this){if((t=S(this,t,e,0)??p)===k)return;const o=this._$AH,i=t===p&&o!==p||t.capture!==o.capture||t.once!==o.once||t.passive!==o.passive,s=t!==p&&(o===p||i);i&&this.element.removeEventListener(this.name,this,o),s&&this.element.addEventListener(this.name,this,t),this._$AH=t}handleEvent(t){typeof this._$AH=="function"?this._$AH.call(this.options?.host??this.element,t):this._$AH.handleEvent(t)}}class qt{constructor(t,e,o){this.element=t,this.type=6,this._$AN=void 0,this._$AM=e,this.options=o}get _$AU(){return this._$AM._$AU}_$AI(t){S(this,t)}}const Rt=W.litHtmlPolyfillSupport;Rt?.(T,q),(W.litHtmlVersions??=[]).push("3.3.2");const Ut=(r,t,e)=>{const o=e?.renderBefore??t;let i=o._$litPart$;if(i===void 0){const s=e?.renderBefore??null;o._$litPart$=i=new q(t.insertBefore(L(),s),s,void 0,e??{})}return i._$AI(r),i};const Y=globalThis;class g extends A{constructor(){super(...arguments),this.renderOptions={host:this},this._$Do=void 0}createRenderRoot(){const t=super.createRenderRoot();return this.renderOptions.renderBefore??=t.firstChild,t}update(t){const e=this.render();this.hasUpdated||(this.renderOptions.isConnected=this.isConnected),super.update(t),this._$Do=Ut(e,this.renderRoot,this.renderOptions)}connectedCallback(){super.connectedCallback(),this._$Do?.setConnected(!0)}disconnectedCallback(){super.disconnectedCallback(),this._$Do?.setConnected(!1)}render(){return k}}g._$litElement$=!0,g.finalized=!0,Y.litElementHydrateSupport?.({LitElement:g});const Ht=Y.litElementPolyfillSupport;Ht?.({LitElement:g});(Y.litElementVersions??=[]).push("4.2.2");const It='h1,h2,h3,h4,h5,h6{font-family:var(--font-title),sans-serif;color:var(--color-text);margin:0;padding:0}h1{font-size:var(--font-size-h1);line-height:var(--font-line-height-h1);margin:var(--font-size-h1) 0 calc(var(--font-size-h1)/3) 0;font-weight:600}h1 img{margin-right:calc(var(--font-size-h1)/4)}h1 span{color:var(--color-text)}h2{font-size:var(--font-size-h2);line-height:var(--font-line-height-h2);margin:var(--font-size-h2) 0 calc(var(--font-size-h2)/3) 0;font-weight:400}h2 img{margin-right:calc(var(--font-size-h2)/4)}h3{font-size:var(--font-size-h3);line-height:var(--font-line-height-h3);margin:var(--font-size-h3) 0 calc(var(--font-size-h3)/2) 0;font-weight:400}h3 img{margin-right:calc(var(--font-size-h3)/4)}h4{font-size:var(--font-size-h4);line-height:var(--font-line-height-h4);margin:var(--font-size-h4) 0 calc(var(--font-size-h4)/2) 0;font-weight:400}h4 img{margin-right:calc(var(--font-size-h4)/4)}h5{font-size:var(--font-size-h5);line-height:var(--font-line-height-h5);margin:var(--font-size-h5) 0 calc(var(--font-size-h5)/2) 0;text-transform:uppercase;font-weight:400}h5 img{margin-right:calc(var(--font-size-h5)/4)}h6{font-size:var(--font-size-h6);line-height:var(--font-line-height-h6);margin:var(--font-size-h5) 0 calc(var(--font-size)/2) 0;text-transform:uppercase;font-weight:400}h6 img{margin-right:calc(var(--font-size-h6)/4)}.heading-permalink{margin-right:.2em;-webkit-user-select:none;user-select:none}pre,code,kbd{font-family:var(--font-mono),monospace}pre[data-lang]{padding:1em 1.5em;border:solid 1px var(--color-border);background:var(--color-bg-layer);margin:1.5em 0;overflow:auto}pre[data-lang=mermaid]{border:none;background:none;font-weight:200;display:flex;justify-content:center}code,kbd{background:#ffffff08;padding:.05em .4em}kbd{font-weight:100;border:solid 1px var(--color-border);background:none}pre>code{background:none;padding:0}.tooltip,*[term],tooltip{font-style:italic;position:relative;border-bottom:dashed 1px var(--color-text);cursor:default;white-space:nowrap}.tooltip:hover,*[term]:hover,tooltip:hover{color:var(--color-text-brand)}.tooltip:before,*[term]:before,tooltip:before{display:block;position:absolute;-webkit-user-select:none;user-select:none;pointer-events:none;opacity:0;transform:translateY(10px);transition:.2s ease;color:var(--color-text);font-style:normal;font-size:var(--font-size-secondary);white-space:nowrap}.tooltip:before,*[term]:before,tooltip:before{content:attr(term);background:var(--color-bg-tooltip, #1c212f);border:solid 1px var(--color-border);padding:.2em 1em;right:0;top:28px;z-index:99}.tooltip:hover:before,*[term]:hover:before,tooltip:hover:before{opacity:1;transform:translateY(0)}blockquote{color:var(--color-quote-text);background:var(--color-quote);border-left:solid 8px var(--color-quote-border);margin:1em 0;padding:1em 1.2em;display:block;position:relative}blockquote pre[data-lang]{border:solid 1px var(--color-bg)}blockquote.tip{color:var(--color-quote-tip-text);background:var(--color-quote-tip);border-left:solid 8px var(--color-quote-tip-border)}blockquote.note{color:var(--color-quote-note-text);background:var(--color-quote-note);border-left:solid 8px var(--color-quote-note-border)}blockquote.mac,blockquote.macos,blockquote.linux,blockquote.windows,blockquote.warning{color:var(--color-quote-warning-text);background:var(--color-quote-warning);border-left:solid 8px var(--color-quote-warning-border)}blockquote.mac,blockquote.macos,blockquote.linux,blockquote.windows{padding-left:60px}blockquote.mac:before,blockquote.macos:before,blockquote.linux:before,blockquote.windows:before{content:"";background:var(--color-quote-warning-border) center center no-repeat;background-size:16px 16px;display:block;width:32px;height:32px;position:absolute;left:14px}blockquote.mac:before,blockquote.macos:before{background-image:url(/images/icons/apple.svg)}blockquote.linux:before{background-image:url(/images/icons/linux.svg)}blockquote.windows:before{background-image:url(/images/icons/windows.svg)}blockquote>ul,blockquote>p{margin:0}blockquote>ul>li{margin:.1em 0}table{width:100%;border:solid 1px var(--color-border)}table>thead{background:var(--color-border);font-family:var(--font-title),sans-serif;text-transform:uppercase;text-align:left}table th{font-weight:400;font-size:var(--font-size-secondary);color:var(--color-text-secondary)}table th,table td{padding:10px}table tr:hover td{background:var(--color-bg-hover);transition:.2s ease}a:visited,a{color:inherit;text-decoration:none;position:relative;display:inline-block;line-height:inherit}a:before{content:"";height:.1em;width:100%;display:inline-block;background:var(--color-text-brand);position:absolute;left:0;bottom:0;transform:scaleX(0);transition:transform .2s ease;transform-origin:100% 0}a.active,a:not(.button):hover{color:var(--color-text-brand);text-decoration:none}a.active:before,a:hover:before{transform:scaleX(1);transform-origin:0 0;transition:transform .3s ease}a.external,a.external-link{margin-right:14px!important}a.external:after,a.external-link:after{content:"";width:12px;height:12px;display:block;background:url(https://intellij-icons.jetbrains.design/icons/AllIcons/expui/ide/externalLink_dark.svg) center center no-repeat;background-size:12px 12px;text-decoration:none;position:absolute;top:4px;right:-14px;transform:translate(0) scale(1);transition:transform .2s ease}a.external:hover:after,a.external-link:hover:after{transform:translate(2px,-6px) scale(1.2);transition:transform .3s ease}a img{margin-right:8px;display:inline-block}.emphasis{color:var(--color-text-brand)}ul{list-style:square;padding-inline-start:24px}ul>li{margin:1.3em 0}ul ul{margin-top:.7em}ul ul>li{margin:.3em 0;font-size:var(--font-size-secondary)}ul>li::marker{color:var(--color-text-brand)}p{margin:1em 0}*{box-sizing:border-box}@media(orientation:portrait){h1{font-size:5rem}h2{font-size:clamp(3rem,1vw + 3.5rem,5rem)}h3{font-size:max(2rem,min(2rem + 1vw,5rem))}h4{font-size:max(1.5rem,min(2rem + 1vw,2.25rem))}p{font-size:1.25rem}}::-webkit-scrollbar{width:6px;height:6px}::-webkit-scrollbar-track{background:var(--color-bg-hover)}::-webkit-scrollbar-thumb{background:var(--color-text-brand)}::-webkit-scrollbar-thumb:hover{background:var(--color-text-brand-hover)}',z=v`
  ${ct(It)}

  /* Accessibility: default text contrast & focus */
  :host {
    color: var(--color-text);
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
`;class Mt extends g{static properties={href:{type:String},external:{type:Boolean},type:{type:String},icon:{type:String},iconWidth:{type:String},iconHeight:{type:String},active:{type:Boolean},ariaLabel:{type:String,attribute:"aria-label"}};static styles=[z,v`
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
        `];constructor(){super(),this.href="",this.type="primary",this.icon="",this.iconWidth="",this.iconHeight="",this.external=!1,this.active=!1,this.ariaLabel=""}render(){const t=`button button-${this.type} ${this.active?"button-active":""}`;return this.href?m`
            <a href="${this.href}"
               class="${t}"
               target="${this.external?"_blank":"_self"}"
               rel="${this.external?"noopener noreferrer":p}"
               aria-label="${this.ariaLabel||p}">
                <slot></slot>
                ${this.icon?m`<span class="icon" aria-hidden="true">
                    <img class="img" src="${this.icon}" width="${this.iconWidth}" height="${this.iconHeight}" alt=""/>
                </span>`:p}
            </a>
        `:m`
                <span class="${t}" role="button" aria-label="${this.ariaLabel||p}">
                    <slot></slot>
                    ${this.icon?m`<span class="icon" aria-hidden="true">
                        <img class="img" src="${this.icon}" width="${this.iconWidth}" height="${this.iconHeight}" alt=""/>
                    </span>`:p}
                </span>
            `}}customElements.define("boson-button",Mt);class Nt extends g{static properties={isScrolled:{type:Boolean}};static styles=[v`
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

        ::slotted([mobile="true"]) {
            display: none;
        }

        ::slotted(mobile-header-menu) {
            display: none;
            border-right: none !important;
        }

        @media (orientation: portrait) {
            ::slotted([pc="true"]) {
                display: none;
            }
            ::slotted(.logo) {
                flex: 1;
            }
            ::slotted([mobile="true"]) {
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
    `];constructor(){super(),this.isScrolled=!1,this.handleScroll=this.handleScroll.bind(this)}connectedCallback(){super.connectedCallback(),window.addEventListener("scroll",this.handleScroll),this.handleScroll()}disconnectedCallback(){super.disconnectedCallback(),window.removeEventListener("scroll",this.handleScroll)}handleScroll(){const t=window.pageYOffset||document.documentElement.scrollTop;this.isScrolled=t>0}render(){return m`
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
        `}}customElements.define("boson-header",Nt);class jt extends g{static styles=[v`
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
    `];render(){return m`
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
        `}}customElements.define("boson-footer",jt);class Dt extends g{static properties={decorative:{type:Boolean}};static styles=[z,v`
        .container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .logo-text {
            font-size: clamp(24px, 8vw, 64px);
            font-weight: bold;
            position: relative;
            z-index: 2;
            transition: transform 0.3s ease;
            cursor: pointer;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            white-space: nowrap;
        }

        .logo-text:hover {
            transform: scale(1.05);
        }

        .responsive {
            color: var(--color-text-brand);
        }

        .sk {
            color: var(--color-text);
        }

        .animated-dots {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .floating-dot {
            position: absolute;
            width: 3px;
            height: 3px;
            background: var(--color-text-brand);
            border-radius: 50%;
            opacity: 0;
            animation: float 3s infinite ease-in-out;
        }

        .floating-dot:nth-child(2n) {
            background: var(--color-text, rgba(255, 255, 255, 0.8));
            animation-delay: 0.5s;
        }

        .floating-dot:nth-child(3n) {
            animation-delay: 1s;
        }

        .floating-dot:nth-child(4n) {
            animation-delay: 1.5s;
        }

        .floating-dot:nth-child(5n) {
            animation-delay: 2s;
        }

        @keyframes float {
            0% {
                opacity: 0;
                transform: translateY(20px) scale(0.5);
            }
            50% {
                opacity: 1;
                transform: translateY(-10px) scale(1);
            }
            100% {
                opacity: 0;
                transform: translateY(-40px) scale(0.5);
            }
        }

        @media (max-width: 768px) {
            .logo-text {
                font-size: clamp(18px, 6vw, 32px);
            }
        }

        /* ACCESSIBILITY - Screen reader text */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }
    `];constructor(){super(),this.animationInterval=null,this.decorative=!1}firstUpdated(){this.startFloatingDots()}disconnectedCallback(){super.disconnectedCallback(),this.animationInterval&&clearInterval(this.animationInterval)}startFloatingDots(){const t=this.shadowRoot.querySelector(".animated-dots");t&&(this.animationInterval=setInterval(()=>{this.createFloatingDot(t)},800))}createFloatingDot(t){const e=document.createElement("div");e.className="floating-dot";const o=Math.random()*100,i=Math.random()*100;e.style.left=`${o}%`,e.style.top=`${i}%`,t.appendChild(e),setTimeout(()=>{e.parentNode&&e.parentNode.removeChild(e)},3e3)}render(){return m`
            <div class="container" role="img" aria-label="${this.decorative?"":"responsive.sk logo"}">
                <div class="logo-text" aria-hidden="${this.decorative}">
                    <span class="responsive">responsive</span><span class="sk">.sk</span>
                </div>
                <div class="animated-dots" aria-hidden="true"></div>
                ${this.decorative?"":m`
                    <span class="sr-only">responsive.sk</span>
                `}
            </div>
        `}}customElements.define("boson-logo",Dt);class Bt extends g{static properties={action:{type:String},query:{type:String}};static styles=[z,v`
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
    `];constructor(){super(),this.action="/",this.query=""}render(){return m`
            <form method="get" action="${this.action}">
                <input
                    type="search"
                    name="q"
                    value="${this.query}"
                    placeholder="Search"
                    aria-label="Search"
                />
            </form>
        `}}customElements.define("boson-search-input",Bt);class Vt extends g{static styles=[z,v`
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
    `];render(){return m`
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
                            <boson-logo decorative></boson-logo>
                        </div>
                    </div>
                </div>

                <aside class="bottom">
                    <a href="#nativeness" class="discover" aria-label="Scroll down to discover more about nativeness">
                        <span class="discover-container">
                            <span class="discover-text">
                                <slot name="discovery"></slot>
                            </span>

                            <img class="discover-icon"
                                 src="/images/icons/arrow_down.svg" 
                                 alt="resp-button" 
                                 aria-hidden="true"
                                 width="16" 
                                 height="16"/>
                        </span>
                    </a>
                </aside>
            </section>
        `}}customElements.define("hero-section",Vt);class Wt extends g{static properties={type:{type:String}};static styles=[z,v`
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
    `];constructor(){super(),this.type="horizontal"}render(){return m`
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
        `}}customElements.define("segment-section",Wt);class Ft extends g{static styles=[v`
        .landing-layout {
            display: flex;
            flex-direction: column;
            gap: var(--landing-layout-gap);
        }
    `];render(){return m`
            <main class="landing-layout">
                <slot></slot>
            </main>
        `}}customElements.define("boson-landing-layout",Ft);class Yt extends g{static styles=[v`
        .default-layout {

        }
    `];render(){return m`
            <main class="default-layout">
                <slot></slot>
            </main>
        `}}customElements.define("boson-default-layout",Yt);class Zt extends g{static styles=[z,v`
        .blog-layout {
            display: grid;
            grid-template-columns: 1fr 4fr 1fr;
            margin: 0 auto;
            width: var(--width-content);
            max-width: var(--width-max);
        }

        .empty {
            /* Empty column for left spacing */
        }

        .sidebar {
            margin: 0;
            width: 300px;
            max-width: 300px;
            min-width: 300px;
            border-left: solid 1px var(--color-border);
        }

        .sidebar-content {
            flex: 1;
            width: 100%;
            top: 70px;
            display: flex;
            flex-direction: column;
            position: sticky;
            max-height: calc(100vh - 100px);
        }

        .sidebar-categories {
            width: 100%;
            padding: 2em 0;
            display: flex;
            flex-direction: column;
            gap: .5em;
            position: relative;
        }

        ::slotted(strong),
        ::slotted(a) {
            padding: .3em .5em;
        }

        ::slotted(strong) {
            background: var(--color-primary);
            color: var(--color-bg);
            border-radius: var(--border-radius);
        }

        ::slotted(a) {
            color: var(--color-text);
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: background-color 0.2s ease;
        }

        ::slotted(a:hover) {
            background: var(--color-border);
        }

        .content {
            padding: 0 2em;
            min-height: 100vh;
        }

        .content-wrapper {
            padding: 20px 0;
        }

        @media (max-width: 1024px) {
            .blog-layout {
                grid-template-columns: 1fr;
                gap: 2em;
            }

            .sidebar {
                width: 100%;
                max-width: 100%;
                min-width: 100%;
                border-left: none;
                border-bottom: solid 1px var(--color-border);
            }

            .sidebar-content {
                position: static;
                max-height: none;
            }

            .content {
                padding: 0 1em;
            }
        }
    `];render(){return m`
            <main class="blog-layout">
                <div class="empty"></div>

                <section class="content">
                    <div class="content-wrapper">
                        <slot></slot>
                    </div>
                </section>

                <aside class="sidebar">
                    <div class="sidebar-content">
                        <nav class="sidebar-categories">
                            <slot name="sidebar"></slot>
                        </nav>
                    </div>
                </aside>
            </main>
        `}}customElements.define("boson-blog-layout",Zt);class Jt extends g{static styles=[v`
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
    `];render(){return m`
            <main class="search-layout">
                <slot></slot>

                <section class="search-content">
                    <slot name="content"></slot>
                </section>
            </main>
        `}}customElements.define("boson-search-layout",Jt);let nt=!1;async function Kt(){if(!nt){const r=await f(()=>import("./chunks/mermaid.core-DAah7_uR.js").then(t=>t.bB),[]);return r.default.initialize({startOnLoad:!0,theme:"dark",themeVariables:{primaryColor:"#F93904",primaryTextColor:"#ffffff",primaryBorderColor:"#F93904",lineColor:"#ffffff",secondaryColor:"#0d1119",tertiaryColor:"#1a1f2e"}}),nt=!0,r.default}}const j={"call-to-action-section":()=>f(()=>import("./chunks/call-to-action-section-DI41mknT.js"),[]),"how-it-works-section":()=>f(()=>import("./chunks/how-it-works-section-Btd7aRFx.js"),[]),"mobile-development-section":()=>f(()=>import("./chunks/mobile-development-section-B7cfrodE.js"),[]),"right-choice-section":()=>f(()=>import("./chunks/right-choice-section-DNqVu2Qm.js"),[])},D={"boson-dropdown":()=>f(()=>import("./chunks/dropdown-ZOlUSzcs.js"),[]),"boson-breadcrumbs":()=>f(()=>import("./chunks/breadcrumbs-rb0qHr3N.js"),[]),"mobile-header-menu":()=>f(()=>import("./chunks/mobile-header-menu-CUas7vST.js"),[]),"dots-container":()=>f(()=>import("./chunks/dots-container-Cb0JmjW1.js"),[]),"horizontal-accordion":()=>f(()=>import("./chunks/horizontal-accordion-hoE7DwYX.js"),[]),"boson-subtitle":()=>f(()=>import("./chunks/subtitle-wybRj7KX.js"),[]),"boson-page-title":()=>f(()=>import("./chunks/page-title-CG6UZDMW.js"),[])},E=new IntersectionObserver(r=>{r.forEach(t=>{if(t.isIntersecting){const e=t.target.tagName.toLowerCase();j[e]&&(j[e]().then(()=>{console.log(`Lazy loaded: ${e}`)}),E.unobserve(t.target)),D[e]&&(D[e]().then(()=>{console.log(`Lazy loaded: ${e}`)}),E.unobserve(t.target)),(t.target.classList.contains("mermaid")||t.target.querySelector(".mermaid")||t.target.hasAttribute("data-lang"))&&(Kt(),E.unobserve(t.target))}})},{rootMargin:"10px"});function at(){Object.keys(j).forEach(r=>{document.querySelectorAll(r).forEach(t=>{E.observe(t)})}),Object.keys(D).forEach(r=>{document.querySelectorAll(r).forEach(t=>{E.observe(t)})}),document.querySelectorAll('.mermaid, [data-lang="mermaid"]').forEach(r=>{E.observe(r)})}document.readyState==="loading"?document.addEventListener("DOMContentLoaded",at):at();export{f as _,v as a,m as b,g as i,z as s};
