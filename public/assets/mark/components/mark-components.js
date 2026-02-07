import{i as c,n,a as d,b as l,t as p,d as U,r as _}from"../../chunks/vendor-lit-CeIZiaZY.js";import{C as L,r as W}from"../../chunks/vendor-D4WeIGRu.js";var B=Object.defineProperty,I=Object.getOwnPropertyDescriptor,A=(r,e,s,a)=>{for(var t=a>1?void 0:a?I(e,s):e,o=r.length-1,i;o>=0;o--)(i=r[o])&&(t=(a?i(e,s,t):i(t))||t);return a&&t&&B(e,s,t),t};let P=class extends d{constructor(){super(...arguments),this.title="Mark Dashboard",this.activeMenu="dashboard"}connectedCallback(){super.connectedCallback(),document.documentElement.setAttribute("data-theme","dark")}render(){return l`
      <div class="layout">
        <slot></slot>
      </div>
    `}};P.styles=c`
    :host {
      display: block;
      --sl-color-primary-50: #f0f9ff;
      --sl-color-primary-100: #e0f2fe;
      --sl-color-primary-200: #bae6fd;
      --sl-color-primary-300: #7dd3fc;
      --sl-color-primary-400: #38bdf8;
      --sl-color-primary-500: #0ea5e9;
      --sl-color-primary-600: #0284c7;
      --sl-color-primary-700: #0369a1;
      --sl-color-primary-800: #075985;
      --sl-color-primary-900: #0c4a6e;

      /* Dark theme as default */
      --sl-color-neutral-0: #0f172a;
      --sl-color-neutral-50: #1e293b;
      --sl-color-neutral-100: #334155;
      --sl-color-neutral-200: #475569;
      --sl-color-neutral-300: #64748b;
      --sl-color-neutral-400: #94a3b8;
      --sl-color-neutral-500: #cbd5e1;
      --sl-color-neutral-600: #e2e8f0;
      --sl-color-neutral-700: #f1f5f9;
      --sl-color-neutral-800: #f8fafc;
      --sl-color-neutral-900: #ffffff;

      color-scheme: dark;
    }

    .layout {
      min-height: 100vh;
      background: var(--sl-color-neutral-0);
      color: var(--sl-color-neutral-900);
      transition:
        background-color 0.3s ease,
        color 0.3s ease;
    }
  `;A([n({type:String})],P.prototype,"title",2);A([n({type:String})],P.prototype,"activeMenu",2);P=A([p("mark-layout")],P);var N=Object.getOwnPropertyDescriptor,q=(r,e,s,a)=>{for(var t=a>1?void 0:a?N(e,s):e,o=r.length-1,i;o>=0;o--)(i=r[o])&&(t=i(t)||t);return t};let M=class extends d{render(){return l`
      <div class="logo-area">
        <slot name="logo">Mark Panel</slot>
      </div>
      <nav class="nav-area">
        <slot></slot>
      </nav>
    `}};M.styles=c`
    :host {
      display: flex;
      flex-direction: column;
      height: 100%;
      background-color: var(--admin-sidebar-bg, #1e1e2d);
      color: var(--admin-sidebar-text, #a1a5b7);
      border-right: 1px solid var(--admin-border-color, #2b2b40);
    }

    .logo-area {
      height: var(--admin-header-height, 70px);
      display: flex;
      align-items: center;
      padding: 0 1.5rem;
      color: #fff;
      font-weight: 700;
      font-size: 1.25rem;
      letter-spacing: 0.5px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .nav-area {
      flex: 1;
      padding: 1rem 0;
      overflow-y: auto;
    }

    ::slotted(.nav-label) {
      padding: 0.5rem 1.5rem;
      font-size: 0.75rem;
      text-transform: uppercase;
      color: rgba(255, 255, 255, 0.3);
      font-weight: 600;
      letter-spacing: 0.5px;
      margin-top: 1rem;
      display: block;
    }

    ::slotted(a) {
      display: flex;
      align-items: center;
      padding: 0.75rem 1.5rem;
      color: var(--admin-sidebar-text, #a1a5b7);
      text-decoration: none;
      transition: all 0.2s;
      font-size: 0.95rem;
      font-weight: 500;
      border-left: 3px solid transparent;
      cursor: pointer;
    }

    ::slotted(a:hover) {
      color: var(--admin-sidebar-active, #ffffff);
      background: rgba(255, 255, 255, 0.03);
    }

    ::slotted(a.active) {
      color: var(--admin-sidebar-active, #ffffff);
      background: var(--admin-sidebar-active-bg, #1b1b28);
      border-left-color: #007bff;
    }
    
    ::slotted(a) .nav-icon {
        margin-right: 0.75rem;
        width: 20px;
        text-align: center;
    }
  `;M=q([p("mark-sidebar")],M);var H=Object.getOwnPropertyDescriptor,Y=(r,e,s,a)=>{for(var t=a>1?void 0:a?H(e,s):e,o=r.length-1,i;o>=0;o--)(i=r[o])&&(t=i(t)||t);return t};let z=class extends d{render(){return l`<slot></slot>`}};z.styles=c`
    :host {
      display: block;
      background: var(--admin-card-bg, #1e1e2d);
      border: 1px solid var(--admin-border-color, #2b2b40);
      border-radius: 0.85rem;
      /* padding needs to be configurable or default */
      padding: 1.5rem; 
      box-shadow: 0 0.1rem 1rem 0.25rem rgba(0,0,0,0.03);
      color: var(--admin-text-primary, #ffffff);
    }
    
    :host([no-padding]) {
        padding: 0;
    }
  `;z=Y([p("mark-card")],z);var F=Object.defineProperty,G=Object.getOwnPropertyDescriptor,$=(r,e,s,a)=>{for(var t=a>1?void 0:a?G(e,s):e,o=r.length-1,i;o>=0;o--)(i=r[o])&&(t=(a?i(e,s,t):i(t))||t);return a&&t&&F(e,s,t),t};let f=class extends d{constructor(){super(...arguments),this.title="",this.value="0",this.trend="",this.icon="📊",this.iconBg="#e1f0ff"}render(){return l`
      <mark-card>
        <div class="stat-header">
            <div class="icon-box" style="background: ${this.iconBg}">
                ${this.icon}
            </div>
            <div>
                <h3 class="stat-title">${this.title}</h3>
            </div>
        </div>
        <div class="stat-value">${this.value}</div>
        <div class="stat-trend" style="color: ${this.trend.startsWith("+")?"#50cd89":"#f1416c"}">
            ${this.trend}
        </div>
      </mark-card>
    `}};f.styles=c`
    :host {
      display: block;
    }

    .stat-header {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;
    }

    .icon-box {
      width: 40px;
      height: 40px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
      font-size: 1.25rem;
      /* default light mode bg, override inline for specific colors if needed, 
         but ideally we map variants */
    }

    .stat-title {
      margin: 0;
      font-size: 0.9rem;
      color: var(--admin-text-secondary, #a1a5b7);
      font-weight: 600;
    }

    .stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: var(--admin-text-primary, #ffffff);
    }
    
    .stat-trend {
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }
  `;$([n({type:String})],f.prototype,"title",2);$([n({type:String})],f.prototype,"value",2);$([n({type:String})],f.prototype,"trend",2);$([n({type:String})],f.prototype,"icon",2);$([n({type:String})],f.prototype,"iconBg",2);f=$([p("mark-stats-card")],f);var V=Object.defineProperty,X=Object.getOwnPropertyDescriptor,O=(r,e,s,a)=>{for(var t=a>1?void 0:a?X(e,s):e,o=r.length-1,i;o>=0;o--)(i=r[o])&&(t=(a?i(e,s,t):i(t))||t);return a&&t&&V(e,s,t),t};L.register(...W);let b=class extends d{constructor(){super(...arguments),this.type="line",this.data={},this.options={},this.height="300px",this.chart=null}firstUpdated(){this.initChart()}updated(r){this.chart&&(r.has("data")&&(this.chart.data=this.data,this.chart.update()),r.has("type")&&(this.chart.destroy(),this.initChart()))}initChart(){const r=this.canvas.getContext("2d");if(!r)return;const e={responsive:!0,maintainAspectRatio:!1,plugins:{legend:{labels:{color:"#a1a5b7",font:{family:"Inter"}}}},scales:{x:{grid:{color:"rgba(255, 255, 255, 0.05)"},ticks:{color:"#a1a5b7",font:{family:"Inter"}}},y:{grid:{color:"rgba(255, 255, 255, 0.05)"},ticks:{color:"#a1a5b7",font:{family:"Inter"}}}}},s={type:this.type,data:this.data,options:{...e,...this.options}};this.chart=new L(r,s)}render(){return l`
            <div class="chart-container" style="height: ${this.height}">
                <canvas></canvas>
            </div>
        `}};b.styles=c`
        :host {
            display: block;
            width: 100%;
            position: relative;
        }
        .chart-container {
            position: relative;
            width: 100%;
        }
    `;O([n({type:String})],b.prototype,"type",2);O([n({type:Object})],b.prototype,"data",2);O([n({type:Object})],b.prototype,"options",2);O([n({type:String})],b.prototype,"height",2);O([U("canvas")],b.prototype,"canvas",2);b=O([p("mark-chart")],b);var J=Object.defineProperty,K=Object.getOwnPropertyDescriptor,R=(r,e,s,a)=>{for(var t=a>1?void 0:a?K(e,s):e,o=r.length-1,i;o>=0;o--)(i=r[o])&&(t=(a?i(e,s,t):i(t))||t);return a&&t&&J(e,s,t),t};let D=class extends d{constructor(){super(...arguments),this.striped=!1,this.hover=!0}createRenderRoot(){return this}render(){return l`
        <style>
            mark-table {
                display: block;
                width: 100%;
                overflow-x: auto;
            }
            mark-table table {
                width: 100%;
                border-collapse: collapse;
                color: var(--admin-text-primary, #ffffff);
            }
            
            mark-table thead th {
                text-align: left; 
                padding: 1.25rem 1.5rem; 
                color: var(--admin-text-secondary, #6c757d); 
                font-weight: 600; 
                font-size: 0.85rem; 
                text-transform: uppercase; 
                letter-spacing: 0.05em;
                border-bottom: 1px solid var(--admin-border-color, #2b2b40);
            }

            mark-table tbody tr {
                border-bottom: 1px solid var(--admin-border-color, #2b2b40); /* Dashed or solid */
                transition: background 0.2s;
            }
            
            mark-table tbody tr:last-child {
                border-bottom: none;
            }

            mark-table tbody tr:hover {
                background: var(--admin-bg-hover, rgba(255,255,255,0.02));
            }

            mark-table td {
                padding: 1rem 1.5rem;
                vertical-align: middle;
            }
        </style>
        <slot></slot>
        `}};D.styles=c`
    :host {
      display: block;
      width: 100%;
      overflow-x: auto;
    }

    .table-container {
      width: 100%;
      border-radius: 8px; /* Assuming card handles outer radius usually */
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.95rem;
    }

    /* Header Styles */
    ::slotted(thead) {
      background: transparent;
      border-bottom: 1px solid var(--admin-border-color, #2b2b40);
    }
    
    /* Using deep selectors or assuming standardized HTML structure because Shadow DOM blocks table styling */
    /* Best practice for wrapped tables is often Light DOM or very specific slotting */
    /* Since we want convenience, we will style standard elements if they are slotted? 
       No, ::slotted only targets top level. 
       
       Solution: Lit component that wraps the table logic often creates the table itself from data,
       OR it acts as a style wrapper helper.
       
       Given PHP renders specific columns, using <mark-table> as a WRAPPER that injects styles 
       into standard <table> is tricky with Shadow DOM isolation.
       
       Option A: Render table in Light DOM (createRenderRoot).
       Option B: Provide specific components like <mark-tr>, <mark-td>. Too verbose.
       Option C: Use Light DOM.
    */
  `;R([n({type:Boolean})],D.prototype,"striped",2);R([n({type:Boolean})],D.prototype,"hover",2);D=R([p("mark-table")],D);var Q=Object.defineProperty,Z=Object.getOwnPropertyDescriptor,u=(r,e,s,a)=>{for(var t=a>1?void 0:a?Z(e,s):e,o=r.length-1,i;o>=0;o--)(i=r[o])&&(t=(a?i(e,s,t):i(t))||t);return a&&t&&Q(e,s,t),t};let h=class extends d{constructor(){super(...arguments),this.activeMenu="dashboard",this.stats={},this.recentLogs=[],this.recentArticles=[],this.recentUsers=[],this.isLoading=!0,this.timeRange="today",this.chartType="bar"}connectedCallback(){super.connectedCallback(),this.loadDashboardData()}async loadDashboardData(){this.isLoading=!0;try{const r=await fetch("/api/mark/dashboard/stats");this.stats=await r.json();const e=await fetch("/api/mark/audit-logs/recent");this.recentLogs=await e.json();const s=await fetch("/api/mark/articles/recent");this.recentArticles=await s.json();const a=await fetch("/api/mark/users/recent");this.recentUsers=await a.json()}catch(r){console.error("Failed to load dashboard data:",r),this.showToast("Error loading dashboard data","danger")}finally{this.isLoading=!1}}showToast(r,e="success"){const s=new CustomEvent("mark-toast",{detail:{message:r,variant:e},bubbles:!0,composed:!0});this.dispatchEvent(s)}formatNumber(r){return new Intl.NumberFormat().format(r)}getEventColor(r){const e={login:"success",logout:"neutral",article:"primary",user:"warning",image:"purple",system:"danger"};for(const[s,a]of Object.entries(e))if(r.includes(s))return a;return"neutral"}render(){return this.isLoading?l`
        <div class="dashboard-container">
          <mark-sidebar .activeMenu=${this.activeMenu}></mark-sidebar>
          <div class="dashboard-main">
            <div class="loading">
              <div class="loading-spinner"></div>
            </div>
          </div>
        </div>
      `:l`
      <div class="dashboard-container">
        <mark-sidebar .activeMenu=${this.activeMenu}></mark-sidebar>

        <main class="dashboard-main">
          <header class="dashboard-header">
            <h1 class="dashboard-title">Dashboard Overview</h1>
            <div class="dashboard-actions">
              <sl-select
                size="small"
                value=${this.timeRange}
                @sl-change=${r=>this.timeRange=r.target.value}
              >
                <sl-option value="today">Today</sl-option>
                <sl-option value="week">This Week</sl-option>
                <sl-option value="month">This Month</sl-option>
                <sl-option value="year">This Year</sl-option>
              </sl-select>

              <sl-button size="small" variant="neutral" @click=${this.loadDashboardData}>
                <sl-icon name="arrow-clockwise"></sl-icon> Refresh
              </sl-button>
            </div>
          </header>

          <!-- Statistics Grid -->
          <section class="stats-grid">
            <mark-stats-card
              title="Total Articles"
              value=${this.formatNumber(this.stats.totalArticles||0)}
              icon="newspaper"
              trend=${this.stats.articlesTrend||0}
              color="primary"
            ></mark-stats-card>

            <mark-stats-card
              title="Total Users"
              value=${this.formatNumber(this.stats.totalUsers||0)}
              icon="people"
              trend=${this.stats.usersTrend||0}
              color="success"
            ></mark-stats-card>

            <mark-stats-card
              title="Today's Activity"
              value=${this.formatNumber(this.stats.todayActivity||0)}
              icon="activity"
              trend=${this.stats.activityTrend||0}
              color="warning"
            ></mark-stats-card>

            <mark-stats-card
              title="Storage Used"
              value="${((this.stats.storageUsed||0)/1024).toFixed(1)} GB"
              icon="hdd"
              trend=${this.stats.storageTrend||0}
              color="danger"
              subtitle="of ${this.stats.storageTotal||10} GB total"
            ></mark-stats-card>
          </section>

          <!-- Charts Section -->
          <section class="charts-container">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Activity Overview</h3>
                <div class="card-actions">
                  <sl-button-group>
                    <sl-button
                      size="small"
                      ?outline=${this.chartType!=="bar"}
                      @click=${()=>this.chartType="bar"}
                    >
                      Bar
                    </sl-button>
                    <sl-button
                      size="small"
                      ?outline=${this.chartType!=="line"}
                      @click=${()=>this.chartType="line"}
                    >
                      Line
                    </sl-button>
                  </sl-button-group>
                </div>
              </div>
              <mark-chart
                type=${this.chartType}
                .data=${this.stats.chartData||{}}
                height="300"
              ></mark-chart>
            </div>

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Event Distribution</h3>
              </div>
              <mark-chart
                type="pie"
                .data=${this.stats.eventDistribution||{}}
                height="300"
              ></mark-chart>
            </div>
          </section>

          <!-- Recent Activity Section -->
          <section class="recent-activity">
            <!-- Recent Audit Logs -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Recent Activity</h3>
                <sl-button size="small" variant="text" href="/mark/audit-logs">
                  View All
                </sl-button>
              </div>

              ${this.recentLogs.length>0?l`
                    <mark-table
                      .data=${this.recentLogs}
                      .columns=${[{key:"event",header:"Event",width:"40%"},{key:"user",header:"User",width:"30%"},{key:"time",header:"Time",width:"30%"}]}
                      .renderRow=${r=>l`
                        <div style="display: flex; align-items: center; gap: 8px;">
                          <sl-badge variant=${this.getEventColor(r.eventType)} pill>
                            <sl-icon name=${this.getEventIcon(r.eventType)}></sl-icon>
                          </sl-badge>
                          <div>
                            <div style="font-weight: 500;">${r.description}</div>
                            <small style="color: var(--sl-color-neutral-500);"
                              >${r.eventType}</small
                            >
                          </div>
                        </div>
                      `}
                    ></mark-table>
                  `:l`
                    <div class="empty-state">
                      <div class="empty-state-icon">
                        <sl-icon name="clipboard"></sl-icon>
                      </div>
                      <p>No recent activity</p>
                    </div>
                  `}
            </div>

            <!-- Recent Articles -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Recent Articles</h3>
                <sl-button size="small" variant="text" href="/mark/articles"> View All </sl-button>
              </div>

              ${this.recentArticles.length>0?l`
                    <mark-table
                      .data=${this.recentArticles}
                      .columns=${[{key:"article",header:"Article",width:"60%"},{key:"status",header:"Status",width:"40%"}]}
                      .renderRow=${r=>l`
                        <div>
                          <div style="font-weight: 500; margin-bottom: 4px;">${r.title}</div>
                          <small style="color: var(--sl-color-neutral-500);">
                            ${r.excerpt.substring(0,60)}...
                          </small>
                        </div>
                      `}
                    ></mark-table>
                  `:l`
                    <div class="empty-state">
                      <div class="empty-state-icon">
                        <sl-icon name="file-text"></sl-icon>
                      </div>
                      <p>No articles yet</p>
                    </div>
                  `}
            </div>
          </section>
        </main>
      </div>

      <!-- Toast Container -->
      <mark-toast></mark-toast>
    `}getEventIcon(r){const e={login:"box-arrow-in-right",logout:"box-arrow-right",article:"file-text",user:"person",image:"image",system:"gear"};for(const[s,a]of Object.entries(e))if(r.includes(s))return a;return"info-circle"}};h.styles=c`
    :host {
      display: block;
      min-height: 100vh;
      background: var(--sl-color-neutral-50);
      color: var(--sl-color-neutral-900);
    }

    .dashboard-container {
      display: grid;
      grid-template-columns: 250px 1fr;
      min-height: 100vh;
      transition: all 0.3s ease;
    }

    .dashboard-main {
      padding: var(--sl-spacing-large);
      overflow-y: auto;
      max-height: calc(100vh - var(--header-height, 60px));
    }

    .dashboard-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: var(--sl-spacing-x-large);
      padding-bottom: var(--sl-spacing-medium);
      border-bottom: 1px solid var(--sl-color-neutral-200);
    }

    .dashboard-title {
      font-size: var(--sl-font-size-2x-large);
      font-weight: 600;
      color: var(--sl-color-neutral-900);
      margin: 0;
    }

    .dashboard-actions {
      display: flex;
      gap: var(--sl-spacing-small);
      align-items: center;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: var(--sl-spacing-large);
      margin-bottom: var(--sl-spacing-x-large);
    }

    .charts-container {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: var(--sl-spacing-x-large);
      margin-bottom: var(--sl-spacing-x-large);
    }

    .recent-activity {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: var(--sl-spacing-x-large);
      margin-bottom: var(--sl-spacing-x-large);
    }

    .card {
      background: var(--sl-color-neutral-0);
      border-radius: var(--sl-border-radius-large);
      padding: var(--sl-spacing-large);
      box-shadow: var(--sl-shadow-medium);
      border: 1px solid var(--sl-color-neutral-200);
      transition: all 0.3s ease;
    }

    .card:hover {
      box-shadow: var(--sl-shadow-large);
      transform: translateY(-2px);
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: var(--sl-spacing-medium);
    }

    .card-title {
      font-size: var(--sl-font-size-large);
      font-weight: 600;
      color: var(--sl-color-neutral-900);
      margin: 0;
    }

    .card-actions {
      display: flex;
      gap: var(--sl-spacing-x-small);
    }

    .empty-state {
      text-align: center;
      padding: var(--sl-spacing-x-large);
      color: var(--sl-color-neutral-500);
    }

    .empty-state-icon {
      font-size: 3rem;
      margin-bottom: var(--sl-spacing-medium);
      opacity: 0.5;
    }

    .loading {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 400px;
    }

    .loading-spinner {
      width: 40px;
      height: 40px;
      border: 3px solid var(--sl-color-neutral-200);
      border-top-color: var(--sl-color-primary-600);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    @media (max-width: 1024px) {
      .dashboard-container {
        grid-template-columns: 1fr;
      }

      .charts-container {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 768px) {
      .stats-grid {
        grid-template-columns: 1fr;
      }

      .recent-activity {
        grid-template-columns: 1fr;
      }
    }
  `;u([n({type:String})],h.prototype,"activeMenu",2);u([n({type:Object})],h.prototype,"stats",2);u([n({type:Array})],h.prototype,"recentLogs",2);u([n({type:Array})],h.prototype,"recentArticles",2);u([n({type:Array})],h.prototype,"recentUsers",2);u([_()],h.prototype,"isLoading",2);u([_()],h.prototype,"timeRange",2);u([_()],h.prototype,"chartType",2);h=u([p("mark-dashboard")],h);var tt=Object.defineProperty,et=Object.getOwnPropertyDescriptor,S=(r,e,s,a)=>{for(var t=a>1?void 0:a?et(e,s):e,o=r.length-1,i;o>=0;o--)(i=r[o])&&(t=(a?i(e,s,t):i(t))||t);return a&&t&&tt(e,s,t),t};let y=class extends d{constructor(){super(...arguments),this.type="info",this.duration=3e3,this._visible=!1,this.message=""}connectedCallback(){super.connectedCallback(),requestAnimationFrame(()=>{this._visible=!0}),this.duration>0&&(this._timer=setTimeout(()=>{this.close()},this.duration))}close(){this._visible=!1,setTimeout(()=>{this.dispatchEvent(new CustomEvent("closed",{bubbles:!0,composed:!0})),this.remove()},300)}render(){let r="";switch(this.type){case"success":r="✅";break;case"error":r="❌";break;case"warning":r="⚠️";break;case"info":r="ℹ️";break}return l`
      <div class="toast type-${this.type} ${this._visible?"visible":""}">
        <div class="toast-icon">${r}</div>
        <div class="toast-content">${this.message}</div>
        <button class="close-btn" @click="${this.close}">×</button>
      </div>
    `}};y.styles=c`
    :host {
      display: block;
      width: 100%;
      pointer-events: auto;
    }

    .toast {
      background: var(--admin-card-bg, #1e1e2d);
      color: var(--admin-text-primary, #ffffff);
      padding: 1rem 1.5rem;
      border-radius: 0.6rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 300px;
      max-width: 400px;
      border-left: 4px solid var(--toast-color, #009ef7);
      
      opacity: 0;
      transform: translateX(50px);
      transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
      pointer-events: auto;
    }

    .toast.visible {
      opacity: 1;
      transform: translateX(0);
    }

    .toast-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
    }

    .toast-content {
        flex: 1;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .close-btn {
        background: transparent;
        border: none;
        color: var(--admin-text-secondary, #a1a5b7);
        cursor: pointer;
        padding: 0;
        display: flex;
        align-items: center;
        font-size: 1.2rem;
        opacity: 0.7;
    }
    .close-btn:hover {
        opacity: 1;
    }

    /* Types */
    .type-success { --toast-color: var(--admin-success, #50cd89); }
    .type-error { --toast-color: var(--admin-danger, #f1416c); }
    .type-warning { --toast-color: #ffc700; }
    .type-info { --toast-color: #009ef7; }
  `;S([n({type:String})],y.prototype,"type",2);S([n({type:Number})],y.prototype,"duration",2);S([_()],y.prototype,"_visible",2);S([n({type:String})],y.prototype,"message",2);y=S([p("mark-toast")],y);class rt{static init(){if(!document.getElementById("mark-toast-container")){const e=document.createElement("div");e.id="mark-toast-container",Object.assign(e.style,{position:"fixed",top:"20px",right:"20px",zIndex:"9999",display:"flex",flexDirection:"column",gap:"10px",pointerEvents:"none"}),document.body.appendChild(e)}}static show(e){this.init();const s=document.getElementById("mark-toast-container");if(s){const a=document.createElement("mark-toast");a.message=e.message,a.type=e.type||"info",a.duration=e.duration||3e3,s.appendChild(a)}}static success(e){this.show({message:e,type:"success"})}static error(e){this.show({message:e,type:"error"})}}window.MarkNotify=rt;var at=Object.defineProperty,st=Object.getOwnPropertyDescriptor,E=(r,e,s,a)=>{for(var t=a>1?void 0:a?st(e,s):e,o=r.length-1,i;o>=0;o--)(i=r[o])&&(t=(a?i(e,s,t):i(t))||t);return a&&t&&at(e,s,t),t};let j=class extends d{constructor(){super(...arguments),this.variant="secondary"}render(){return l`<slot></slot>`}};j.styles=c`
    :host {
      display: inline-flex;
      align-items: center;
      padding: 0.35rem 0.6rem;
      border-radius: 6px;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      line-height: 1;
    }

    /* Dark mode optimized colors (using semi-transparent backgrounds) */
    :host([variant="primary"]) { background: rgba(0, 158, 247, 0.15); color: #009ef7; }
    :host([variant="success"]) { background: rgba(80, 205, 137, 0.15); color: #50cd89; }
    :host([variant="danger"]) { background: rgba(241, 65, 108, 0.15); color: #f1416c; }
    :host([variant="warning"]) { background: rgba(255, 199, 0, 0.15); color: #ffc700; }
    :host([variant="secondary"]) { background: rgba(255, 255, 255, 0.1); color: #a1a5b7; }
  `;E([n({type:String})],j.prototype,"variant",2);j=E([p("mark-badge")],j);var ot=Object.defineProperty,it=Object.getOwnPropertyDescriptor,C=(r,e,s,a)=>{for(var t=a>1?void 0:a?it(e,s):e,o=r.length-1,i;o>=0;o--)(i=r[o])&&(t=(a?i(e,s,t):i(t))||t);return a&&t&&ot(e,s,t),t};let w=class extends d{constructor(){super(...arguments),this.variant="primary",this.href="",this.type="button"}render(){const r=`btn btn-${this.variant.replace("icon-","icon ")}`;return this.href?l`<a href="${this.href}" class="${r}"><slot></slot></a>`:l`
        <button type="${this.type}" class="${r}">
            <slot></slot>
        </button>
    `}};w.styles=c`
    :host {
      display: inline-block;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      padding: 0.75rem 1.5rem;
      border-radius: 0.6rem;
      font-weight: 600;
      font-size: 0.9rem;
      cursor: pointer;
      border: none;
      text-decoration: none;
      transition: filter 0.2s, background 0.2s;
      font-family: inherit;
      line-height: normal;
    }

    .btn:hover {
        filter: brightness(1.1);
    }

    /* Variants */
    .btn-primary {
        background: #009ef7;
        color: white;
    }

    .btn-secondary {
        background: #f5f8fa; /* Light mode */
        color: #7e8299;
    }
    
    /* Dark mode override for secondary */
    :host-context(mark-layout) .btn-secondary, 
    :host-context([theme="dark"]) .btn-secondary {
        background: #323248;
        color: #fff;
    }

    .btn-danger {
        background: #f1416c; /* or transparent with red text/border depending on style */
        color: white;
    }
    
    /* Icon Button Variant */
    .btn-icon {
        padding: 0;
        width: 36px;
        height: 36px;
        background: #323248; /* Dark default */
        color: #a1a5b7;
    }
    .btn-icon:hover {
        color: #fff;
    }
    
    .btn-icon.danger {
        background: rgba(241, 65, 108, 0.1);
        color: #f1416c;
    }
    .btn-icon.danger:hover {
        background: rgba(241, 65, 108, 0.2);
    }

  `;C([n({type:String})],w.prototype,"variant",2);C([n({type:String})],w.prototype,"href",2);C([n({type:String})],w.prototype,"type",2);w=C([p("mark-button")],w);var nt=Object.defineProperty,lt=Object.getOwnPropertyDescriptor,T=(r,e,s,a)=>{for(var t=a>1?void 0:a?lt(e,s):e,o=r.length-1,i;o>=0;o--)(i=r[o])&&(t=(a?i(e,s,t):i(t))||t);return a&&t&&nt(e,s,t),t};let k=class extends d{constructor(){super(...arguments),this.title="Dashboard",this.user="Mark User",this.role="Manager"}render(){return l`
      <div class="header-breadcrumbs">
        <h1>${this.title}</h1>
      </div>

      <div class="user-profile">
        <div class="user-info">
            <div class="user-name">${this.user}</div>
            <div class="user-role">${this.role}</div>
        </div>
        <div class="user-avatar">
            ${this.user.substring(0,2).toUpperCase()}
        </div>
      </div>
    `}};k.styles=c`
    :host {
      display: flex;
      height: 100%;
      align-items: center;
      justify-content: space-between;
      padding: 0 2rem;
      background: var(--admin-card-bg, #1e1e2d);
      color: var(--admin-text-primary, #ffffff);
    }

    h1 {
      margin: 0;
      font-size: 1.2rem;
      font-weight: 700;
    }

    .user-profile {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .user-info {
        text-align: right; 
        margin-right: 0.5rem;
    }

    .user-name {
        font-weight: 600; 
        font-size: 0.9rem;
        color: var(--admin-text-primary, #fff);
    }

    .user-role {
        font-size: 0.8rem; 
        color: var(--admin-text-secondary, #6c757d);
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      background: #323248;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      color: #fff;
    }
  `;T([n({type:String})],k.prototype,"title",2);T([n({type:String})],k.prototype,"user",2);T([n({type:String})],k.prototype,"role",2);k=T([p("mark-header")],k);var ct=Object.defineProperty,dt=Object.getOwnPropertyDescriptor,x=(r,e,s,a)=>{for(var t=a>1?void 0:a?dt(e,s):e,o=r.length-1,i;o>=0;o--)(i=r[o])&&(t=(a?i(e,s,t):i(t))||t);return a&&t&&ct(e,s,t),t};let g=class extends d{constructor(){super(...arguments),this.type="text",this.label="",this.name="",this.value="",this.placeholder="",this.required=!1}render(){return l`
      ${this.label?l`<label>${this.label} ${this.required?l`<span style="color: #f1416c">*</span>`:""}</label>`:""}
      <slot></slot>
    `}};g.styles=c`
    :host {
      display: block;
      margin-bottom: 1.5rem;
    }

    label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: var(--admin-text-secondary, #a1a5b7);
      font-size: 0.9rem;
    }

    /* We use ::slotted or assume normal input for now, but to wrap native input: */
    
    input, textarea, select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--admin-border-color, #2b2b40);
        background-color: var(--admin-input-bg, #151521);
        color: var(--admin-text-primary, #ffffff);
        border-radius: 0.6rem;
        font-size: 1rem;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
        font-family: inherit;
    }

    input:focus, textarea:focus, select:focus {
        border-color: #009ef7;
    }
  `;x([n({type:String})],g.prototype,"type",2);x([n({type:String})],g.prototype,"label",2);x([n({type:String})],g.prototype,"name",2);x([n({type:String})],g.prototype,"value",2);x([n({type:String})],g.prototype,"placeholder",2);x([n({type:Boolean})],g.prototype,"required",2);g=x([p("mark-input")],g);var pt=Object.defineProperty,ht=Object.getOwnPropertyDescriptor,v=(r,e,s,a)=>{for(var t=a>1?void 0:a?ht(e,s):e,o=r.length-1,i;o>=0;o--)(i=r[o])&&(t=(a?i(e,s,t):i(t))||t);return a&&t&&pt(e,s,t),t};let m=class extends d{constructor(){super(...arguments),this.timeout=1800,this.warning=300,this.ignoreRoutes="/login,/register",this.pingUrl="/api/session/ping",this.logoutUrl="/logout",this._timeLeft=0,this._showWarning=!1,this._lastActivity=Date.now()}connectedCallback(){super.connectedCallback();const r=window.location.pathname;this.ignoreRoutes.split(",").some(e=>r.startsWith(e))||this.startTracking()}startTracking(){["mousemove","mousedown","keydown","scroll","touchstart"].forEach(r=>{window.addEventListener(r,()=>this.resetTimer(),{passive:!0})}),this.resetTimer(),this._counter=setInterval(()=>{const e=(Date.now()-this._lastActivity)/1e3,s=this.timeout-e;this._timeLeft=Math.max(0,Math.floor(s)),s<=0?(window.location.href=this.logoutUrl+"?reason=timeout",clearInterval(this._counter)):s<=this.warning?this._showWarning||(this._showWarning=!0):this._showWarning&&(this._showWarning=!1)},1e3)}resetTimer(){this._lastActivity=Date.now(),this._showWarning&&(this._showWarning=!1,this.ping())}async ping(){try{await fetch(this.pingUrl,{method:"POST",headers:{"Content-Type":"application/json","X-Requested-With":"XMLHttpRequest"}}),this._lastActivity=Date.now(),this._showWarning=!1}catch(r){console.error("Session ping failed",r)}}render(){const r=Math.floor(this._timeLeft/60),e=this._timeLeft%60,s=`${r}:${e.toString().padStart(2,"0")}`;return l`
            <div class="modal-overlay ${this._showWarning?"visible":""}">
                <div class="modal-card">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">⏳</div>
                    <h2>Session Timeout Warning</h2>
                    <p>Your session is about to expire due to inactivity.<br>Please choose an action to continue.</p>
                    
                    <div class="timer">${s}</div>
                    
                    <div class="actions">
                        <button class="btn-logout" @click="${()=>window.location.href=this.logoutUrl}">Logout</button>
                        <button class="btn-stay" @click="${()=>this.ping()}">Stay Logged In</button>
                    </div>
                </div>
            </div>
        `}};m.styles=c`
        :host {
            display: block;
            font-family: 'Inter', sans-serif;
        }
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .modal-overlay.visible {
            opacity: 1;
            pointer-events: auto;
        }
        .modal-card {
            background: #1e1e2d;
            border: 1px solid #2b2b40;
            border-radius: 12px;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            text-align: center;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }
        .modal-overlay.visible .modal-card {
            transform: translateY(0);
        }
        h2 {
            color: #ffffff;
            margin: 0 0 1rem 0;
            font-size: 1.5rem;
        }
        p {
            color: #a1a5b7;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }
        .timer {
            font-size: 2.5rem;
            font-weight: 700;
            color: #f1416c;
            margin-bottom: 1.5rem;
            font-variant-numeric: tabular-nums;
        }
        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        button {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: filter 0.2s;
        }
        .btn-stay {
            background: #009ef7;
            color: white;
        }
        .btn-logout {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }
        button:hover {
            filter: brightness(1.1);
        }
    `;v([n({type:Number})],m.prototype,"timeout",2);v([n({type:Number})],m.prototype,"warning",2);v([n({type:String})],m.prototype,"ignoreRoutes",2);v([n({type:String})],m.prototype,"pingUrl",2);v([n({type:String})],m.prototype,"logoutUrl",2);v([_()],m.prototype,"_timeLeft",2);v([_()],m.prototype,"_showWarning",2);m=v([p("mark-session-warning")],m);
