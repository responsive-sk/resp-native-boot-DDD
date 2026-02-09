<?php

declare(strict_types=1);

// Application Entry Point
// This file bootstraps the application by calling Blog\Core\Boot::boot().

require_once __DIR__ . '/vendor/autoload.php';

use Blog\Core\Boot;

// Execute the bootstrap process
return Boot::boot();
