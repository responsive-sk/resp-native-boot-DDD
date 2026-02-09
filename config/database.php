<?php

declare(strict_types=1);

use Blog\Infrastructure\Paths;

return [
    'connections'
    => [
            'app'
            => [
                    'driver' => 'pdo_sqlite',
                    'path' => Paths::dataPath() . '/app.db',
                ],
            'articles'
            => [
                    'driver' => 'pdo_sqlite',
                    'path' => Paths::dataPath() . '/articles.db',
                ],
            'users'
            => [
                    'driver' => 'pdo_sqlite',
                    'path' => Paths::dataPath() . '/users.db',
                ],
            'forms'
            => [
                    'driver' => 'pdo_sqlite',
                    'path' => Paths::dataPath() . '/forms.db',
                ],
        ],
];
