<?php

declare(strict_types=1);

namespace Blog\Infrastructure\DebugBar;

class BlogDebugBarStyles
{
    public static function getCustomCss(): string
    {
        return <<<CSS
        /* Blog DebugBar Custom Styles */
        .phpdebugbar {
            font-family: system-ui, -apple-system, sans-serif;
        }
        
        .phpdebugbar-header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        }
        
        .phpdebugbar-tab {
            color: #ffffff;
        }
        
        .phpdebugbar-tab:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .phpdebugbar-widgets {
            background: #1e293b;
            color: #f1f5f9;
        }
        
        /* Blog branding */
        .phpdebugbar-brand {
            font-weight: bold;
            color: #f59e0b;
        }
        CSS;
    }
}
