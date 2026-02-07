<?php

declare(strict_types=1);

use Blog\Application\Image\UploadImage;
use Blog\Infrastructure\Image\CloudinaryImageProcessor;
use Blog\Infrastructure\Image\CloudinaryImageUploader;
use Blog\Infrastructure\Storage\CloudinaryStorage;
use Cloudinary\Cloudinary;

return [
    'cloudinary' => [
        'factory' => function ($container) {
            $config = $container->get('config')['cloudinary'] ?? [];

            return new Cloudinary($config);
        },
    ],

    'image_storage' => [
        'class' => CloudinaryStorage::class,
        'arguments' => ['@cloudinary', '%config.image%'],
    ],

    'image_processor' => [
        'class' => CloudinaryImageProcessor::class,
        'arguments' => ['@cloudinary', '%config.image.transformations%'],
    ],

    'image_uploader' => [
        'class' => CloudinaryImageUploader::class,
        'arguments' => ['@image_storage', '%config.image%'],
    ],

    'image_factory' => [
        'class' => Blog\Domain\Image\Factory\ImageFactory::class,
    ],

    // Image repository implementation would go here
    'image_repository' => [
        'class' => Blog\Infrastructure\Persistence\ImageRepository::class,
        'arguments' => ['@database'],
    ],

    UploadImage::class => [
        'arguments' => [
            '@image_uploader',
            '@user_repository',
            '@image_factory',
            '@image_repository',
        ],
    ],
];
