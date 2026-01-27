const gt="modulepreload",ft=function(r){return"/"+r},Z={},g=function(t,e,o){let i=Promise.resolve();if(e&&e.length>0){let a=function(d){return Promise.all(d.map(h=>Promise.resolve(h).then(l=>({status:"fulfilled",value:l}),l=>({status:"rejected",reason:l}))))};document.getElementsByTagName("link");const s=document.querySelector("meta[property=csp-nonce]"),c=s?.nonce||s?.getAttribute("nonce");i=a(e.map(d=>{if(d=ft(d),d in Z)return;Z[d]=!0;const h=d.endsWith(".css"),l=h?'[rel="stylesheet"]':"";if(document.querySelector(`link[href="${d}"]${l}`))return;const p=document.createElement("link");if(p.rel=h?"stylesheet":gt,h||(p.as="script"),p.crossOrigin="",p.href=d,c&&p.setAttribute("nonce",c),document.head.appendChild(p),h)return new Promise((b,k)=>{p.addEventListener("load",b),p.addEventListener("error",()=>k(new Error(`Unable to preload CSS for ${d}`)))})}))}function n(s){const c=new Event("vite:preloadError",{cancelable:!0});if(c.payload=s,window.dispatchEvent(c),!c.defaultPrevented)throw s}return i.then(s=>{for(const c of s||[])c.status==="rejected"&&n(c.reason);return t().catch(n)})};const R=globalThis,B=R.ShadowRoot&&(R.ShadyCSS===void 0||R.ShadyCSS.nativeShadow)&&"adoptedStyleSheets"in Document.prototype&&"replace"in CSSStyleSheet.prototype,V=Symbol(),G=new WeakMap;let lt=class{constructor(t,e,o){if(this._$cssResult$=!0,o!==V)throw Error("CSSResult is not constructable. Use `unsafeCSS` or `css` instead.");this.cssText=t,this.t=e}get styleSheet(){let t=this.o;const e=this.t;if(B&&t===void 0){const o=e!==void 0&&e.length===1;o&&(t=G.get(e)),t===void 0&&((this.o=t=new CSSStyleSheet).replaceSync(this.cssText),o&&G.set(e,t))}return t}toString(){return this.cssText}};const ct=r=>new lt(typeof r=="string"?r:r+"",void 0,V),v=(r,...t)=>{const e=r.length===1?r[0]:t.reduce((o,i,n)=>o+(s=>{if(s._$cssResult$===!0)return s.cssText;if(typeof s=="number")return s;throw Error("Value passed to 'css' function must be a 'css' function result: "+s+". Use 'unsafeCSS' to pass non-literal values, but take care to ensure page security.")})(i)+r[n+1],r[0]);return new lt(e,r,V)},vt=(r,t)=>{if(B)r.adoptedStyleSheets=t.map(e=>e instanceof CSSStyleSheet?e:e.styleSheet);else for(const e of t){const o=document.createElement("style"),i=R.litNonce;i!==void 0&&o.setAttribute("nonce",i),o.textContent=e.cssText,r.appendChild(o)}},J=B?r=>r:r=>r instanceof CSSStyleSheet?(t=>{let e="";for(const o of t.cssRules)e+=o.cssText;return ct(e)})(r):r;const{is:bt,defineProperty:xt,getOwnPropertyDescriptor:yt,getOwnPropertyNames:$t,getOwnPropertySymbols:_t,getPrototypeOf:wt}=Object,U=globalThis,K=U.trustedTypes,kt=K?K.emptyScript:"",At=U.reactiveElementPolyfillSupport,P=(r,t)=>r,I={toAttribute(r,t){switch(t){case Boolean:r=r?kt:null;break;case Object:case Array:r=r==null?r:JSON.stringify(r)}return r},fromAttribute(r,t){let e=r;switch(t){case Boolean:e=r!==null;break;case Number:e=r===null?null:Number(r);break;case Object:case Array:try{e=JSON.parse(r)}catch{e=null}}return e}},dt=(r,t)=>!bt(r,t),X={attribute:!0,type:String,converter:I,reflect:!1,useDefault:!1,hasChanged:dt};Symbol.metadata??=Symbol("metadata"),U.litPropertyMetadata??=new WeakMap;let A=class extends HTMLElement{static addInitializer(t){this._$Ei(),(this.l??=[]).push(t)}static get observedAttributes(){return this.finalize(),this._$Eh&&[...this._$Eh.keys()]}static createProperty(t,e=X){if(e.state&&(e.attribute=!1),this._$Ei(),this.prototype.hasOwnProperty(t)&&((e=Object.create(e)).wrapped=!0),this.elementProperties.set(t,e),!e.noAccessor){const o=Symbol(),i=this.getPropertyDescriptor(t,o,e);i!==void 0&&xt(this.prototype,t,i)}}static getPropertyDescriptor(t,e,o){const{get:i,set:n}=yt(this.prototype,t)??{get(){return this[e]},set(s){this[e]=s}};return{get:i,set(s){const c=i?.call(this);n?.call(this,s),this.requestUpdate(t,c,o)},configurable:!0,enumerable:!0}}static getPropertyOptions(t){return this.elementProperties.get(t)??X}static _$Ei(){if(this.hasOwnProperty(P("elementProperties")))return;const t=wt(this);t.finalize(),t.l!==void 0&&(this.l=[...t.l]),this.elementProperties=new Map(t.elementProperties)}static finalize(){if(this.hasOwnProperty(P("finalized")))return;if(this.finalized=!0,this._$Ei(),this.hasOwnProperty(P("properties"))){const e=this.properties,o=[...$t(e),..._t(e)];for(const i of o)this.createProperty(i,e[i])}const t=this[Symbol.metadata];if(t!==null){const e=litPropertyMetadata.get(t);if(e!==void 0)for(const[o,i]of e)this.elementProperties.set(o,i)}this._$Eh=new Map;for(const[e,o]of this.elementProperties){const i=this._$Eu(e,o);i!==void 0&&this._$Eh.set(i,e)}this.elementStyles=this.finalizeStyles(this.styles)}static finalizeStyles(t){const e=[];if(Array.isArray(t)){const o=new Set(t.flat(1/0).reverse());for(const i of o)e.unshift(J(i))}else t!==void 0&&e.push(J(t));return e}static _$Eu(t,e){const o=e.attribute;return o===!1?void 0:typeof o=="string"?o:typeof t=="string"?t.toLowerCase():void 0}constructor(){super(),this._$Ep=void 0,this.isUpdatePending=!1,this.hasUpdated=!1,this._$Em=null,this._$Ev()}_$Ev(){this._$ES=new Promise(t=>this.enableUpdating=t),this._$AL=new Map,this._$E_(),this.requestUpdate(),this.constructor.l?.forEach(t=>t(this))}addController(t){(this._$EO??=new Set).add(t),this.renderRoot!==void 0&&this.isConnected&&t.hostConnected?.()}removeController(t){this._$EO?.delete(t)}_$E_(){const t=new Map,e=this.constructor.elementProperties;for(const o of e.keys())this.hasOwnProperty(o)&&(t.set(o,this[o]),delete this[o]);t.size>0&&(this._$Ep=t)}createRenderRoot(){const t=this.shadowRoot??this.attachShadow(this.constructor.shadowRootOptions);return vt(t,this.constructor.elementStyles),t}connectedCallback(){this.renderRoot??=this.createRenderRoot(),this.enableUpdating(!0),this._$EO?.forEach(t=>t.hostConnected?.())}enableUpdating(t){}disconnectedCallback(){this._$EO?.forEach(t=>t.hostDisconnected?.())}attributeChangedCallback(t,e,o){this._$AK(t,o)}_$ET(t,e){const o=this.constructor.elementProperties.get(t),i=this.constructor._$Eu(t,o);if(i!==void 0&&o.reflect===!0){const n=(o.converter?.toAttribute!==void 0?o.converter:I).toAttribute(e,o.type);this._$Em=t,n==null?this.removeAttribute(i):this.setAttribute(i,n),this._$Em=null}}_$AK(t,e){const o=this.constructor,i=o._$Eh.get(t);if(i!==void 0&&this._$Em!==i){const n=o.getPropertyOptions(i),s=typeof n.converter=="function"?{fromAttribute:n.converter}:n.converter?.fromAttribute!==void 0?n.converter:I;this._$Em=i;const c=s.fromAttribute(e,n.type);this[i]=c??this._$Ej?.get(i)??c,this._$Em=null}}requestUpdate(t,e,o,i=!1,n){if(t!==void 0){const s=this.constructor;if(i===!1&&(n=this[t]),o??=s.getPropertyOptions(t),!((o.hasChanged??dt)(n,e)||o.useDefault&&o.reflect&&n===this._$Ej?.get(t)&&!this.hasAttribute(s._$Eu(t,o))))return;this.C(t,e,o)}this.isUpdatePending===!1&&(this._$ES=this._$EP())}C(t,e,{useDefault:o,reflect:i,wrapped:n},s){o&&!(this._$Ej??=new Map).has(t)&&(this._$Ej.set(t,s??e??this[t]),n!==!0||s!==void 0)||(this._$AL.has(t)||(this.hasUpdated||o||(e=void 0),this._$AL.set(t,e)),i===!0&&this._$Em!==t&&(this._$Eq??=new Set).add(t))}async _$EP(){this.isUpdatePending=!0;try{await this._$ES}catch(e){Promise.reject(e)}const t=this.scheduleUpdate();return t!=null&&await t,!this.isUpdatePending}scheduleUpdate(){return this.performUpdate()}performUpdate(){if(!this.isUpdatePending)return;if(!this.hasUpdated){if(this.renderRoot??=this.createRenderRoot(),this._$Ep){for(const[i,n]of this._$Ep)this[i]=n;this._$Ep=void 0}const o=this.constructor.elementProperties;if(o.size>0)for(const[i,n]of o){const{wrapped:s}=n,c=this[i];s!==!0||this._$AL.has(i)||c===void 0||this.C(i,void 0,n,c)}}let t=!1;const e=this._$AL;try{t=this.shouldUpdate(e),t?(this.willUpdate(e),this._$EO?.forEach(o=>o.hostUpdate?.()),this.update(e)):this._$EM()}catch(o){throw t=!1,this._$EM(),o}t&&this._$AE(e)}willUpdate(t){}_$AE(t){this._$EO?.forEach(e=>e.hostUpdated?.()),this.hasUpdated||(this.hasUpdated=!0,this.firstUpdated(t)),this.updated(t)}_$EM(){this._$AL=new Map,this.isUpdatePending=!1}get updateComplete(){return this.getUpdateComplete()}getUpdateComplete(){return this._$ES}shouldUpdate(t){return!0}update(t){this._$Eq&&=this._$Eq.forEach(e=>this._$ET(e,this[e])),this._$EM()}updated(t){}firstUpdated(t){}};A.elementStyles=[],A.shadowRootOptions={mode:"open"},A[P("elementProperties")]=new Map,A[P("finalized")]=new Map,At?.({ReactiveElement:A}),(U.reactiveElementVersions??=[]).push("2.1.2");const W=globalThis,Q=r=>r,H=W.trustedTypes,tt=H?H.createPolicy("lit-html",{createHTML:r=>r}):void 0,ht="$lit$",x=`lit$${Math.random().toFixed(9).slice(2)}$`,pt="?"+x,Et=`<${pt}>`,_=document,L=()=>_.createComment(""),O=r=>r===null||typeof r!="object"&&typeof r!="function",F=Array.isArray,St=r=>F(r)||typeof r?.[Symbol.iterator]=="function",M=`[ 	
\f\r]`,C=/<(?:(!--|\/[^a-zA-Z])|(\/?[a-zA-Z][^>\s]*)|(\/?$))/g,et=/-->/g,ot=/>/g,y=RegExp(`>|${M}(?:([^\\s"'>=/]+)(${M}*=${M}*(?:[^ 	
\f\r"'\`<>=]|("|')|))|$)`,"g"),it=/'/g,rt=/"/g,mt=/^(?:script|style|textarea|title)$/i,zt=r=>(t,...e)=>({_$litType$:r,strings:t,values:e}),f=zt(1),S=Symbol.for("lit-noChange"),m=Symbol.for("lit-nothing"),nt=new WeakMap,$=_.createTreeWalker(_,129);function ut(r,t){if(!F(r)||!r.hasOwnProperty("raw"))throw Error("invalid template strings array");return tt!==void 0?tt.createHTML(t):t}const Ct=(r,t)=>{const e=r.length-1,o=[];let i,n=t===2?"<svg>":t===3?"<math>":"",s=C;for(let c=0;c<e;c++){const a=r[c];let d,h,l=-1,p=0;for(;p<a.length&&(s.lastIndex=p,h=s.exec(a),h!==null);)p=s.lastIndex,s===C?h[1]==="!--"?s=et:h[1]!==void 0?s=ot:h[2]!==void 0?(mt.test(h[2])&&(i=RegExp("</"+h[2],"g")),s=y):h[3]!==void 0&&(s=y):s===y?h[0]===">"?(s=i??C,l=-1):h[1]===void 0?l=-2:(l=s.lastIndex-h[2].length,d=h[1],s=h[3]===void 0?y:h[3]==='"'?rt:it):s===rt||s===it?s=y:s===et||s===ot?s=C:(s=y,i=void 0);const b=s===y&&r[c+1].startsWith("/>")?" ":"";n+=s===C?a+Et:l>=0?(o.push(d),a.slice(0,l)+ht+a.slice(l)+x+b):a+x+(l===-2?c:b)}return[ut(r,n+(r[e]||"<?>")+(t===2?"</svg>":t===3?"</math>":"")),o]};class T{constructor({strings:t,_$litType$:e},o){let i;this.parts=[];let n=0,s=0;const c=t.length-1,a=this.parts,[d,h]=Ct(t,e);if(this.el=T.createElement(d,o),$.currentNode=this.el.content,e===2||e===3){const l=this.el.content.firstChild;l.replaceWith(...l.childNodes)}for(;(i=$.nextNode())!==null&&a.length<c;){if(i.nodeType===1){if(i.hasAttributes())for(const l of i.getAttributeNames())if(l.endsWith(ht)){const p=h[s++],b=i.getAttribute(l).split(x),k=/([.?@])?(.*)/.exec(p);a.push({type:1,index:n,name:k[2],strings:b,ctor:k[1]==="."?Lt:k[1]==="?"?Ot:k[1]==="@"?Tt:N}),i.removeAttribute(l)}else l.startsWith(x)&&(a.push({type:6,index:n}),i.removeAttribute(l));if(mt.test(i.tagName)){const l=i.textContent.split(x),p=l.length-1;if(p>0){i.textContent=H?H.emptyScript:"";for(let b=0;b<p;b++)i.append(l[b],L()),$.nextNode(),a.push({type:2,index:++n});i.append(l[p],L())}}}else if(i.nodeType===8)if(i.data===pt)a.push({type:2,index:n});else{let l=-1;for(;(l=i.data.indexOf(x,l+1))!==-1;)a.push({type:7,index:n}),l+=x.length-1}n++}}static createElement(t,e){const o=_.createElement("template");return o.innerHTML=t,o}}function z(r,t,e=r,o){if(t===S)return t;let i=o!==void 0?e._$Co?.[o]:e._$Cl;const n=O(t)?void 0:t._$litDirective$;return i?.constructor!==n&&(i?._$AO?.(!1),n===void 0?i=void 0:(i=new n(r),i._$AT(r,e,o)),o!==void 0?(e._$Co??=[])[o]=i:e._$Cl=i),i!==void 0&&(t=z(r,i._$AS(r,t.values),i,o)),t}class Pt{constructor(t,e){this._$AV=[],this._$AN=void 0,this._$AD=t,this._$AM=e}get parentNode(){return this._$AM.parentNode}get _$AU(){return this._$AM._$AU}u(t){const{el:{content:e},parts:o}=this._$AD,i=(t?.creationScope??_).importNode(e,!0);$.currentNode=i;let n=$.nextNode(),s=0,c=0,a=o[0];for(;a!==void 0;){if(s===a.index){let d;a.type===2?d=new q(n,n.nextSibling,this,t):a.type===1?d=new a.ctor(n,a.name,a.strings,this,t):a.type===6&&(d=new qt(n,this,t)),this._$AV.push(d),a=o[++c]}s!==a?.index&&(n=$.nextNode(),s++)}return $.currentNode=_,i}p(t){let e=0;for(const o of this._$AV)o!==void 0&&(o.strings!==void 0?(o._$AI(t,o,e),e+=o.strings.length-2):o._$AI(t[e])),e++}}class q{get _$AU(){return this._$AM?._$AU??this._$Cv}constructor(t,e,o,i){this.type=2,this._$AH=m,this._$AN=void 0,this._$AA=t,this._$AB=e,this._$AM=o,this.options=i,this._$Cv=i?.isConnected??!0}get parentNode(){let t=this._$AA.parentNode;const e=this._$AM;return e!==void 0&&t?.nodeType===11&&(t=e.parentNode),t}get startNode(){return this._$AA}get endNode(){return this._$AB}_$AI(t,e=this){t=z(this,t,e),O(t)?t===m||t==null||t===""?(this._$AH!==m&&this._$AR(),this._$AH=m):t!==this._$AH&&t!==S&&this._(t):t._$litType$!==void 0?this.$(t):t.nodeType!==void 0?this.T(t):St(t)?this.k(t):this._(t)}O(t){return this._$AA.parentNode.insertBefore(t,this._$AB)}T(t){this._$AH!==t&&(this._$AR(),this._$AH=this.O(t))}_(t){this._$AH!==m&&O(this._$AH)?this._$AA.nextSibling.data=t:this.T(_.createTextNode(t)),this._$AH=t}$(t){const{values:e,_$litType$:o}=t,i=typeof o=="number"?this._$AC(t):(o.el===void 0&&(o.el=T.createElement(ut(o.h,o.h[0]),this.options)),o);if(this._$AH?._$AD===i)this._$AH.p(e);else{const n=new Pt(i,this),s=n.u(this.options);n.p(e),this.T(s),this._$AH=n}}_$AC(t){let e=nt.get(t.strings);return e===void 0&&nt.set(t.strings,e=new T(t)),e}k(t){F(this._$AH)||(this._$AH=[],this._$AR());const e=this._$AH;let o,i=0;for(const n of t)i===e.length?e.push(o=new q(this.O(L()),this.O(L()),this,this.options)):o=e[i],o._$AI(n),i++;i<e.length&&(this._$AR(o&&o._$AB.nextSibling,i),e.length=i)}_$AR(t=this._$AA.nextSibling,e){for(this._$AP?.(!1,!0,e);t!==this._$AB;){const o=Q(t).nextSibling;Q(t).remove(),t=o}}setConnected(t){this._$AM===void 0&&(this._$Cv=t,this._$AP?.(t))}}class N{get tagName(){return this.element.tagName}get _$AU(){return this._$AM._$AU}constructor(t,e,o,i,n){this.type=1,this._$AH=m,this._$AN=void 0,this.element=t,this.name=e,this._$AM=i,this.options=n,o.length>2||o[0]!==""||o[1]!==""?(this._$AH=Array(o.length-1).fill(new String),this.strings=o):this._$AH=m}_$AI(t,e=this,o,i){const n=this.strings;let s=!1;if(n===void 0)t=z(this,t,e,0),s=!O(t)||t!==this._$AH&&t!==S,s&&(this._$AH=t);else{const c=t;let a,d;for(t=n[0],a=0;a<n.length-1;a++)d=z(this,c[o+a],e,a),d===S&&(d=this._$AH[a]),s||=!O(d)||d!==this._$AH[a],d===m?t=m:t!==m&&(t+=(d??"")+n[a+1]),this._$AH[a]=d}s&&!i&&this.j(t)}j(t){t===m?this.element.removeAttribute(this.name):this.element.setAttribute(this.name,t??"")}}class Lt extends N{constructor(){super(...arguments),this.type=3}j(t){this.element[this.name]=t===m?void 0:t}}class Ot extends N{constructor(){super(...arguments),this.type=4}j(t){this.element.toggleAttribute(this.name,!!t&&t!==m)}}class Tt extends N{constructor(t,e,o,i,n){super(t,e,o,i,n),this.type=5}_$AI(t,e=this){if((t=z(this,t,e,0)??m)===S)return;const o=this._$AH,i=t===m&&o!==m||t.capture!==o.capture||t.once!==o.once||t.passive!==o.passive,n=t!==m&&(o===m||i);i&&this.element.removeEventListener(this.name,this,o),n&&this.element.addEventListener(this.name,this,t),this._$AH=t}handleEvent(t){typeof this._$AH=="function"?this._$AH.call(this.options?.host??this.element,t):this._$AH.handleEvent(t)}}class qt{constructor(t,e,o){this.element=t,this.type=6,this._$AN=void 0,this._$AM=e,this.options=o}get _$AU(){return this._$AM._$AU}_$AI(t){z(this,t)}}const Rt=W.litHtmlPolyfillSupport;Rt?.(T,q),(W.litHtmlVersions??=[]).push("3.3.2");const Ht=(r,t,e)=>{const o=e?.renderBefore??t;let i=o._$litPart$;if(i===void 0){const n=e?.renderBefore??null;o._$litPart$=i=new q(t.insertBefore(L(),n),n,void 0,e??{})}return i._$AI(r),i};const Y=globalThis;class u extends A{constructor(){super(...arguments),this.renderOptions={host:this},this._$Do=void 0}createRenderRoot(){const t=super.createRenderRoot();return this.renderOptions.renderBefore??=t.firstChild,t}update(t){const e=this.render();this.hasUpdated||(this.renderOptions.isConnected=this.isConnected),super.update(t),this._$Do=Ht(e,this.renderRoot,this.renderOptions)}connectedCallback(){super.connectedCallback(),this._$Do?.setConnected(!0)}disconnectedCallback(){super.disconnectedCallback(),this._$Do?.setConnected(!1)}render(){return S}}u._$litElement$=!0,u.finalized=!0,Y.litElementHydrateSupport?.({LitElement:u});const Ut=Y.litElementPolyfillSupport;Ut?.({LitElement:u});(Y.litElementVersions??=[]).push("4.2.2");const Nt='h1,h2,h3,h4,h5,h6{font-family:var(--font-title),sans-serif;color:var(--color-text);margin:0;padding:0}h1{font-size:var(--font-size-h1);line-height:var(--font-line-height-h1);margin:var(--font-size-h1) 0 calc(var(--font-size-h1)/3) 0;font-weight:600}h1 img{margin-right:calc(var(--font-size-h1)/4)}h2{font-size:var(--font-size-h2);line-height:var(--font-line-height-h2);margin:var(--font-size-h2) 0 calc(var(--font-size-h2)/3) 0;font-weight:500}h2 img{margin-right:calc(var(--font-size-h2)/4)}h3{font-size:var(--font-size-h3);line-height:var(--font-line-height-h3);margin:var(--font-size-h3) 0 calc(var(--font-size-h3)/2) 0;font-weight:400}h3 img{margin-right:calc(var(--font-size-h3)/4)}h4{font-size:var(--font-size-h4);line-height:var(--font-line-height-h4);margin:var(--font-size-h4) 0 calc(var(--font-size-h4)/2) 0;font-weight:400}h4 img{margin-right:calc(var(--font-size-h4)/4)}h5{font-size:var(--font-size-h5);line-height:var(--font-line-height-h5);margin:var(--font-size-h5) 0 calc(var(--font-size-h5)/2) 0;text-transform:uppercase;font-weight:400}h5 img{margin-right:calc(var(--font-size-h5)/4)}h6{font-size:var(--font-size-h6);line-height:var(--font-line-height-h6);margin:var(--font-size-h5) 0 calc(var(--font-size)/2) 0;text-transform:uppercase;font-weight:400}h6 img{margin-right:calc(var(--font-size-h6)/4)}.heading-permalink{margin-right:.2em;-webkit-user-select:none;user-select:none}pre,code,kbd{font-family:var(--font-mono),monospace}pre[data-lang]{padding:1em 1.5em;border:solid 1px var(--color-border);background:var(--color-bg-layer);margin:1.5em 0;overflow:auto}pre[data-lang=mermaid]{border:none;background:none;font-weight:200;display:flex;justify-content:center}code,kbd{background:#ffffff08;padding:.05em .4em}kbd{font-weight:100;border:solid 1px var(--color-border);background:none}pre>code{background:none;padding:0}.tooltip,*[term],tooltip{font-style:italic;position:relative;border-bottom:dashed 1px var(--color-text);cursor:default;white-space:nowrap}.tooltip:hover,*[term]:hover,tooltip:hover{color:var(--color-text-brand)}.tooltip:before,*[term]:before,tooltip:before{display:block;position:absolute;-webkit-user-select:none;user-select:none;pointer-events:none;opacity:0;transform:translateY(10px);transition:.2s ease;color:var(--color-text);font-style:normal;font-size:var(--font-size-secondary);white-space:nowrap}.tooltip:before,*[term]:before,tooltip:before{content:attr(term);background:#1c212f;border:solid 1px var(--color-border);padding:.2em 1em;right:0;top:28px;z-index:99}.tooltip:hover:before,*[term]:hover:before,tooltip:hover:before{opacity:1;transform:translateY(0)}blockquote{color:var(--color-quote-text);background:var(--color-quote);border-left:solid 8px var(--color-quote-border);margin:1em 0;padding:1em 1.2em;display:block;position:relative}blockquote pre[data-lang]{border:solid 1px var(--color-bg)}blockquote.tip{color:var(--color-quote-tip-text);background:var(--color-quote-tip);border-left:solid 8px var(--color-quote-tip-border)}blockquote.note{color:var(--color-quote-note-text);background:var(--color-quote-note);border-left:solid 8px var(--color-quote-note-border)}blockquote.mac,blockquote.macos,blockquote.linux,blockquote.windows,blockquote.warning{color:var(--color-quote-warning-text);background:var(--color-quote-warning);border-left:solid 8px var(--color-quote-warning-border)}blockquote.mac,blockquote.macos,blockquote.linux,blockquote.windows{padding-left:60px}blockquote.mac:before,blockquote.macos:before,blockquote.linux:before,blockquote.windows:before{content:"";background:var(--color-quote-warning-border) center center no-repeat;background-size:16px 16px;display:block;width:32px;height:32px;position:absolute;left:14px}blockquote.mac:before,blockquote.macos:before{background-image:url(/images/icons/apple.svg)}blockquote.linux:before{background-image:url(/images/icons/linux.svg)}blockquote.windows:before{background-image:url(/images/icons/windows.svg)}blockquote>ul,blockquote>p{margin:0}blockquote>ul>li{margin:.1em 0}table{width:100%;border:solid 1px var(--color-border)}table>thead{background:var(--color-border);font-family:var(--font-title),sans-serif;text-transform:uppercase;text-align:left}table th{font-weight:400;font-size:var(--font-size-secondary);color:var(--color-text-secondary)}table th,table td{padding:10px}table tr:hover td{background:var(--color-bg-hover);transition:.2s ease}a:visited,a{color:inherit;text-decoration:none;position:relative;display:inline-block;line-height:inherit}a:before{content:"";height:.1em;width:100%;display:inline-block;background:#da2f2e;position:absolute;left:0;bottom:0;transform:scaleX(0);transition:transform .2s ease;transform-origin:100% 0}a.active,a:not(.button):hover{color:var(--color-text-brand);text-decoration:none}a.active:before,a:hover:before{transform:scaleX(1);transform-origin:0 0;transition:transform .3s ease}a.external,a.external-link{margin-right:14px!important}a.external:after,a.external-link:after{content:"";width:12px;height:12px;display:block;background:url(https://intellij-icons.jetbrains.design/icons/AllIcons/expui/ide/externalLink_dark.svg) center center no-repeat;background-size:12px 12px;text-decoration:none;position:absolute;top:4px;right:-14px;transform:translate(0) scale(1);transition:transform .2s ease}a.external:hover:after,a.external-link:hover:after{transform:translate(2px,-6px) scale(1.2);transition:transform .3s ease}a img{margin-right:8px;display:inline-block}.emphasis{color:var(--color-text-brand)}ul{list-style:square;padding-inline-start:24px}ul>li{margin:1.3em 0}ul ul{margin-top:.7em}ul ul>li{margin:.3em 0;font-size:var(--font-size-secondary)}ul>li::marker{color:var(--color-text-brand)}p{margin:1em 0}*{box-sizing:border-box}@media(orientation:portrait){h1{font-size:5rem}h2{font-size:clamp(3rem,1vw + 3.5rem,5rem)}h3{font-size:max(2rem,min(2rem + 1vw,5rem))}h4{font-size:max(1.5rem,min(2rem + 1vw,2.25rem))}p{font-size:1.25rem}}::-webkit-scrollbar{width:6px;height:6px}::-webkit-scrollbar-track{background:var(--color-bg-hover)}::-webkit-scrollbar-thumb{background:var(--color-text-brand)}::-webkit-scrollbar-thumb:hover{background:var(--color-text-brand-hover)}',w=ct(Nt);class Mt extends u{static properties={href:{type:String},external:{type:Boolean},type:{type:String},icon:{type:String},iconWidth:{type:String},iconHeight:{type:String},active:{type:Boolean}};static styles=[w,v`
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
    `];constructor(){super(),this.href="",this.type="primary",this.icon="",this.iconWidth="",this.iconHeight="",this.external=!1,this.active=!1}render(){return this.href===""?f`
                <span class="button button-${this.type} ${this.active?"button-active":""}">
                    <slot></slot>

                    <span class="icon" style="${this.icon===""?"display:none":""}">
                        <img class="img" src="${this.icon}" alt="arrow" width="${this.iconWidth}" height="${this.iconHeight}" />
                    </span>
                </span>
            `:f`
            <a href="${this.href}"
               class="button button-${this.type} ${this.active?"button-active":""}"
               target="${this.external?"_blank":"_self"}">
                <slot></slot>

                <span class="icon" style="${this.icon===""?"display:none":""}">
                    <img class="img" src="${this.icon}" alt="arrow" width="${this.iconWidth}" height="${this.iconHeight}" />
                </span>
            </a>
        `}}customElements.define("boson-button",Mt);class It extends u{static properties={isScrolled:{type:Boolean}};static styles=[v`
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
    `];constructor(){super(),this.isScrolled=!1,this.handleScroll=this.handleScroll.bind(this)}connectedCallback(){super.connectedCallback(),window.addEventListener("scroll",this.handleScroll),this.handleScroll()}disconnectedCallback(){super.disconnectedCallback(),window.removeEventListener("scroll",this.handleScroll)}handleScroll(){const t=window.pageYOffset||document.documentElement.scrollTop;this.isScrolled=t>0}render(){return f`
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
        `}}customElements.define("boson-header",It);class Dt extends u{static styles=[v`
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
    `];render(){return f`
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
                <div class="credits">
                    <a target="_blank" href="https://responsive.sk" class="credits-link">
<!--                        <img src="/images/credits.png" alt="credits" width="120" height="24"/>-->
                    </a>
                </div>
            </footer>
        `}}customElements.define("boson-footer",Dt);class jt extends u{static styles=[w,v`
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
            color: #F93904;
        }

        .sk {
            color: white;
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
            background: #F93904;
            border-radius: 50%;
            opacity: 0;
            animation: float 3s infinite ease-in-out;
        }

        .floating-dot:nth-child(2n) {
            background: rgba(255, 255, 255, 0.8);
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




    `];constructor(){super(),this.animationInterval=null}firstUpdated(){this.startFloatingDots()}disconnectedCallback(){super.disconnectedCallback(),this.animationInterval&&clearInterval(this.animationInterval)}startFloatingDots(){const t=this.shadowRoot.querySelector(".animated-dots");t&&(this.animationInterval=setInterval(()=>{this.createFloatingDot(t)},800))}createFloatingDot(t){const e=document.createElement("div");e.className="floating-dot";const o=Math.random()*100,i=Math.random()*100;e.style.left=`${o}%`,e.style.top=`${i}%`,t.appendChild(e),setTimeout(()=>{e.parentNode&&e.parentNode.removeChild(e)},3e3)}render(){return f`
            <div class="container">
                <div class="logo-text">
                    <span class="responsive">responsive</span><span class="sk">.sk</span>
                </div>
                <div class="animated-dots"></div>
            </div>
        `}}customElements.define("boson-logo",jt);class Bt extends u{static properties={action:{type:String},query:{type:String}};static styles=[w,v`
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
    `];constructor(){super(),this.action="/",this.query=""}render(){return f`
            <form method="get" action="${this.action}">
                <input
                    type="search"
                    name="q"
                    value="${this.query}"
                    placeholder="Search"
                    aria-label="Search"
                />
            </form>
        `}}customElements.define("boson-search-input",Bt);class Vt extends u{static styles=[w,v`
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
    `];render(){return f`
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
                            <boson-logo></boson-logo>
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
                                 src="/images/icons/arrow_down.svg" alt="down arrow" width="16" height="16"/>
                        </span>
                    </a>
                </aside>
            </section>
        `}}customElements.define("hero-section",Vt);class Wt extends u{static properties={type:{type:String}};static styles=[w,v`
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
    `];constructor(){super(),this.type="horizontal"}render(){return f`
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
        `}}customElements.define("segment-section",Wt);class Ft extends u{static styles=[v`
        .landing-layout {
            display: flex;
            flex-direction: column;
            gap: var(--landing-layout-gap);
        }
    `];render(){return f`
            <main class="landing-layout">
                <slot></slot>
            </main>
        `}}customElements.define("boson-landing-layout",Ft);class Yt extends u{static styles=[v`
        .default-layout {

        }
    `];render(){return f`
            <main class="default-layout">
                <slot></slot>
            </main>
        `}}customElements.define("boson-default-layout",Yt);class Zt extends u{static styles=[w,v`
        .docs-layout {
            display: grid;
            grid-template-columns: 1fr 4fr 1fr;
            margin: 0 auto;
            width: var(--width-content);
            max-width: var(--width-max);
        }

        .menu {
            margin: 0;
            width: 300px;
            max-width: 300px;
            min-width: 300px;
            border-right: solid 1px var(--color-border);
        }

        .menu-content {
            flex: 1;
            width: 100%;
            top: 70px;
            display: flex;
            flex-direction: column;
            position: sticky;
            max-height: calc(100vh - 100px);
        }

        .menu-pages,
        .menu-categories {
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

        [name="menu"]::slotted(strong) {
            background: var(--color-bg-button);
            color: var(--color-text);
            font-weight: unset;
        }

        .menu-pages {
            margin-top: -1px;
            border-top: solid 1px var(--color-border);
            background: var(--color-bg-layer);
        }

        .menu-pages::before {
            content: '';
            width: 100vw;
            height: 100%;
            user-select: none;
            position: absolute;
            background: var(--color-bg-layer);
            border-top: solid 1px var(--color-border);
            border-bottom: solid 1px var(--color-border);
            bottom: -1px;
            right: 300px;
        }

        .menu-categories {
            position: relative;
            border-top: solid 1px var(--color-border);
            font-size: var(--font-size-secondary);
            overflow-y: auto;
            overflow-x: visible;
        }

        .content {
            padding: 2em;
            overflow: auto;
        }

        [name="category"]::slotted(strong) {
            color: var(--color-text-brand);
        }

        /** RIGHT COLUMN */

        .navigation {
            padding-top: 1em;
            width: 200px;
            max-width: 200px;
            min-width: 200px;
            font-size: var(--font-size-secondary);
        }

        .navigation-content {
            max-height: calc(100vh - 100px);
            overflow: auto;
            width: 100%;
            max-width: 100%;
            position: sticky;
            display: flex;
            flex-direction: column;
            top: 100px;
            padding: 2em 0 2em 16px;
            border-left: solid 1px var(--color-border);
        }

        .navigation-content a {
            line-height: 1.2;
            position: relative;
            display: inline-block;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .navigation-content a::before {
            content: '';
            width: 2px;
            height: 100%;
            transform-origin: 0 100%;
            transform: scaleY(0);
            top: 0;
            left: -17px;
            position: absolute;
        }

        .navigation-content a.active::before {
            width: 2px;
            height: 100%;
            background: var(--color-text-brand);
            transform-origin: 0 0;
            transform: scaleY(1);
        }

        .navigation-item-2 {
            padding: .5em 0 .3em 0;
        }

        .navigation-item-3 {
            font-size: 90%;
            padding: .3em 0 .2em .8em;
            color: var(--color-text-secondary);
        }

        @media (max-width: 1200px) {
            .docs-layout {
                grid-template-columns: 1fr 4fr;
            }

            .navigation {
                display: none;
            }
        }

        @media (max-width: 900px) {
            .menu-pages::before,
            .menu-categories {
                display: none;
            }

            .docs-layout {
                display: flex;
                flex-direction: column;
                margin: 0;
                width: 100%;
            }

            .menu {
                width: 100%;
                min-width: 100%;
                position: relative;
                border: none;
            }

            .menu-pages {
                border-bottom: solid 1px var(--color-border);
            }
        }
    `];constructor(){super(),this.onScroll=this.onScroll.bind(this)}get headings(){const t=this.querySelectorAll("h2, h3");let e=0;return Array.from(t).map(o=>({id:e++,level:o.tagName.slice(1)|0,title:o.innerText.slice(1),href:o.childNodes[0]?.getAttribute("href")??"#",node:o}))}renderNavigationItem(t){return f`
            <a href="${t.href}"
               data-navigation-item="${t.id}"
               class="navigation-item-${t.level}"
               title="${t.title}">${t.title}</a>
        `}connectedCallback(){super.connectedCallback(),window.addEventListener("scroll",this.onScroll),setTimeout(()=>this.onScroll(),100)}disconnectedCallback(){super.disconnectedCallback(),window.removeEventListener("scroll",this.onScroll)}onScroll(){let t=!1,e=null;for(let o of this.headings.reverse()){let i=o.node.getBoundingClientRect();e=this.shadowRoot.querySelector(`[data-navigation-item="${o.id}"]`),t===!1&&i.top-120<0?(e.classList.add("active"),t=!0):e.classList.remove("active")}t===!1&&e?.classList.add("active")}render(){return f`
            <main class="docs-layout">
                <aside class="menu">
                    <div class="menu-content">
                        <nav class="menu-pages">
                            <slot name="menu"></slot>
                        </nav>

                        <nav class="menu-categories">
                            <slot name="category"></slot>
                        </nav>
                    </div>
                </aside>

                <section class="content" data-id="content">
                    <slot></slot>
                </section>

                <aside class="navigation" style="${this.headings.length===0?"display:none":""}">
                    <div class="navigation-content">
                        ${this.headings.map(t=>this.renderNavigationItem(t))}
                    </div>
                </aside>
            </main>
        `}}customElements.define("boson-docs-layout",Zt);class Gt extends u{static styles=[w,v`
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
    `];render(){return f`
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
        `}}customElements.define("boson-blog-layout",Gt);class Jt extends u{static styles=[v`
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
    `];render(){return f`
            <main class="search-layout">
                <slot></slot>

                <section class="search-content">
                    <slot name="content"></slot>
                </section>
            </main>
        `}}customElements.define("boson-search-layout",Jt);let st=!1;async function Kt(){st||((await g(()=>import("./chunks/mermaid.esm-DX4K9IIW.js").then(t=>t.bG),[])).default.initialize({startOnLoad:!0,theme:"dark",themeVariables:{primaryColor:"#F93904",primaryTextColor:"#ffffff",primaryBorderColor:"#F93904",lineColor:"#ffffff",secondaryColor:"#0d1119",tertiaryColor:"#1a1f2e"}}),st=!0)}const D={"call-to-action-section":()=>g(()=>import("./chunks/call-to-action-section-DI41mknT.js"),[]),"how-it-works-section":()=>g(()=>import("./chunks/how-it-works-section-Btd7aRFx.js"),[]),"mobile-development-section":()=>g(()=>import("./chunks/mobile-development-section-B7cfrodE.js"),[]),"right-choice-section":()=>g(()=>import("./chunks/right-choice-section-DNqVu2Qm.js"),[]),"testimonials-section":()=>g(()=>import("./chunks/testimonials-section-oY91S9b4.js"),[])},j={"boson-dropdown":()=>g(()=>import("./chunks/dropdown-ZOlUSzcs.js"),[]),"boson-breadcrumbs":()=>g(()=>import("./chunks/breadcrumbs-rb0qHr3N.js"),[]),"mobile-header-menu":()=>g(()=>import("./chunks/mobile-header-menu-GU8EdhQl.js"),[]),"dots-container":()=>g(()=>import("./chunks/dots-container-Cb0JmjW1.js"),[]),"horizontal-accordion":()=>g(()=>import("./chunks/horizontal-accordion-hoE7DwYX.js"),[]),"boson-slider":()=>g(()=>import("./chunks/slider-7asU-qUF.js"),[]),"boson-subtitle":()=>g(()=>import("./chunks/subtitle-wybRj7KX.js"),[]),"boson-page-title":()=>g(()=>import("./chunks/page-title-CG6UZDMW.js"),[])},E=new IntersectionObserver(r=>{r.forEach(t=>{if(t.isIntersecting){const e=t.target.tagName.toLowerCase();D[e]&&(D[e]().then(()=>{console.log(`Lazy loaded: ${e}`)}),E.unobserve(t.target)),j[e]&&(j[e]().then(()=>{console.log(`Lazy loaded: ${e}`)}),E.unobserve(t.target)),(t.target.classList.contains("mermaid")||t.target.querySelector(".mermaid")||t.target.hasAttribute("data-lang"))&&(Kt(),E.unobserve(t.target))}})},{rootMargin:"10px"});function at(){Object.keys(D).forEach(r=>{document.querySelectorAll(r).forEach(t=>{E.observe(t)})}),Object.keys(j).forEach(r=>{document.querySelectorAll(r).forEach(t=>{E.observe(t)})}),document.querySelectorAll('.mermaid, [data-lang="mermaid"]').forEach(r=>{E.observe(r)})}document.readyState==="loading"?document.addEventListener("DOMContentLoaded",at):at();export{g as _,v as a,f as b,u as i,w as s};
