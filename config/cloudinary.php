<?php

declare(strict_types=1);

return [
    'cloudinary' => [
        'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '',
        'api_key' => $_ENV['CLOUDINARY_API_KEY'] ?? '',
        'api_secret' => $_ENV['CLOUDINARY_API_SECRET'] ?? '',
        'secure' => true,
    ],

    'image' => [
        'max_size' => (int)($_ENV['IMAGE_MAX_SIZE'] ?? 5242880),
        'allowed_types' => explode(',', $_ENV['IMAGE_ALLOWED_TYPES'] ?? 'image/jpeg,image/png,image/webp'),
        'default_folder' => $_ENV['IMAGE_DEFAULT_FOLDER'] ?? 'blog_uploads',
        'quality' => $_ENV['IMAGE_QUALITY'] ?? 'auto:good',
        'transformations' => [
            'thumbnail' => [
                'width' => 300,
                'height' => 200,
                'crop' => 'fill',
                'gravity' => 'auto',
            ],
            'medium' => [
                'width' => 800,
                'height' => 600,
                'crop' => 'limit',
            ],
            'large' => [
                'width' => 1200,
                'height' => 900,
                'crop' => 'limit',
            ],
            'featured' => [
                'width' => 1600,
                'height' => 900,
                'crop' => 'fill',
                'gravity' => 'auto:faces',
            ],
        ],
    ],
];
