<?php

declare(strict_types=1);

return [
    'min_length' => (int) ($_ENV['PASSWORD_MIN_LENGTH'] ?? 8),
    'require_uppercase' => filter_var($_ENV['PASSWORD_REQUIRE_UPPERCASE'] ?? 'false', FILTER_VALIDATE_BOOLEAN),
    'require_lowercase' => filter_var($_ENV['PASSWORD_REQUIRE_LOWERCASE'] ?? 'false', FILTER_VALIDATE_BOOLEAN),
    'require_number' => filter_var($_ENV['PASSWORD_REQUIRE_NUMBER'] ?? 'false', FILTER_VALIDATE_BOOLEAN),
    'require_special_char' => filter_var($_ENV['PASSWORD_REQUIRE_SPECIAL_CHAR'] ?? 'false', FILTER_VALIDATE_BOOLEAN),
];