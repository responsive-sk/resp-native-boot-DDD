<?php

declare(strict_types=1);

namespace Blog\Infrastructure\DebugBar;

/**
 * Custom styles for DebugBar with Blog application branding
 */
class BlogDebugBarStyles
{
    /**
     * Get custom CSS to override DebugBar logo with blog branding
     */
    public static function getCustomCss(): string
    {
        // Blog logo SVG - simple "B" letter with blog theme
        $logoSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">' .
                   '<rect width="32" height="32" rx="6" fill="#2563eb"/>' .
                   '<path d="M8 8h16v2H8V8zm0 4h16v2H8v-2zm0 4h16v2H8v-2zm0 4h16v2H8v-2z" fill="white"/>' .
                   '</svg>';

        // URL encode the SVG
        $encodedLogo = rawurlencode($logoSvg);

        return <<<CSS
/* Blog application branding for DebugBar */

/* Custom restore button with blog logo */
a.phpdebugbar-restore-btn {
    width: 32px !important;
    height: 32px !important;
    background: transparent !important;
    padding: 0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

a.phpdebugbar-restore-btn:after {
    background: transparent url("data:image/svg+xml,{$encodedLogo}") no-repeat center center !important;
    background-size: 28px 28px !important;
    background-color: transparent !important;
    width: 28px !important;
    height: 28px !important;
    position: static !important;
    left: auto !important;
    top: auto !important;
}

/* Custom header with blog theme colors */
div.phpdebugbar-header {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 95%, rgba(37, 64, 235, 0.1) 100%) !important;
    border-bottom: 1px solid #1e40af !important;
}

/* Custom active tab color */
a.phpdebugbar-tab.phpdebugbar-active {
    border-bottom-color: #2563eb !important;
    background: linear-gradient(to bottom, #2563eb, #1e40af) !important;
}

/* Custom important badge color */
a.phpdebugbar-tab span.phpdebugbar-badge.phpdebugbar-important {
    background: #f59e0b !important;
    color: white !important;
}

/* Custom text colors */
div.phpdebugbar-header {
    color: white !important;
    font-weight: 600 !important;
}

/* Custom request info section */
div.phpdebugbar-request-info {
    background: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 6px !important;
    margin: 10px 0 !important;
}

/* Custom tab styling */
a.phpdebugbar-tab {
    background: #f1f5f9 !important;
    border: 1px solid #e2e8f0 !important;
    color: #374151 !important;
}

a.phpdebugbar-tab:hover {
    background: #e5e7eb !important;
    color: #1f2937 !important;
}

/* Custom button styling */
.phpdebugbar-btn {
    background: #2563eb !important;
    color: white !important;
    border: 1px solid #1e40af !important;
    border-radius: 4px !important;
}

.phpdebugbar-btn:hover {
    background: #1e40af !important;
}

/* Custom panel styling */
.phpdebugbar-panel {
    background: white !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 6px !important;
}

/* Custom table styling */
.phpdebugbar-table {
    background: white !important;
    border-collapse: collapse !important;
    width: 100% !important;
}

.phpdebugbar-table th {
    background: #f8fafc !important;
    color: #374151 !important;
    border: 1px solid #e2e8f0 !important;
    font-weight: 600 !important;
}

.phpdebugbar-table td {
    border: 1px solid #e2e8f0 !important;
    padding: 8px !important;
}

/* Custom code styling */
.phpdebugbar-code {
    background: #1f2937 !important;
    color: #e5e7eb !important;
    border-radius: 4px !important;
    padding: 2px 6px !important;
}

/* Custom success/error styling */
.phpdebugbar-success {
    background: #10b981 !important;
    color: white !important;
}

.phpdebugbar-error {
    background: #ef4444 !important;
    color: white !important;
}

/* Custom warning styling */
.phpdebugbar-warning {
    background: #f59e0b !important;
    color: white !important;
}

/* Responsive design for mobile */
@media (max-width: 768px) {
    a.phpdebugbar-restore-btn {
        width: 24px !important;
        height: 24px !important;
    }
    
    a.phpdebugbar-restore-btn:after {
        background-size: 20px 20px !important;
        width: 20px !important;
        height: 20px !important;
    }
    
    div.phpdebugbar-header {
        padding: 8px 12px !important;
    }
    
    .phpdebugbar-tab {
        padding: 6px 12px !important;
        font-size: 14px !important;
    }
}
CSS;
    }
}
