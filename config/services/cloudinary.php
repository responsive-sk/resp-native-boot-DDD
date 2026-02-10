<?php

declare(strict_types=1);

use Blog\Database\Database;
use Blog\Domain\Image\Factory\ImageFactory;
use Blog\Infrastructure\Image\CloudinaryImageProcessor;
use Blog\Infrastructure\Image\CloudinaryImageUploader;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineImageRepository;
use Blog\Infrastructure\Storage\CloudinaryStorage;
use Cloudinary\Cloudinary;
use Psr\Container\ContainerInterface;

return [
    // Core Cloudinary client
    Cloudinary::class => function (ContainerInterface $c) {
        $config = $c->get('config')['cloudinary']['cloudinary'] ?? [];

        return new Cloudinary([
            'cloud' => [
                'cloud_name' => $config['cloud_name'] ?? '',
                'api_key' => $config['api_key'] ?? '',
                'api_secret' => $config['api_secret'] ?? '',
            ],
            'url' => [
                'secure' => $config['secure'] ?? true,
            ],
        ]);
    },

    // Storage for original images
    CloudinaryStorage::class => function (ContainerInterface $c) {
        $imageConfig = $c->get('config')['cloudinary']['image'] ?? [];
        $cloudConfig = $c->get('config')['cloudinary']['cloudinary'] ?? [];

        // Propagate secure flag to image config for URL generation
        $imageConfig['secure'] = $cloudConfig['secure'] ?? true;

        return new CloudinaryStorage(
            $c->get(Cloudinary::class),
            $imageConfig
        );
    },

    // Image transformations
    CloudinaryImageProcessor::class => fn(ContainerInterface $c) => new CloudinaryImageProcessor(
        $c->get(Cloudinary::class),
        $c->get('config')['cloudinary']['image']['transformations'] ?? []
    ),

    // High-level upload service
    CloudinaryImageUploader::class => fn(ContainerInterface $c) => new CloudinaryImageUploader(
        $c->get(CloudinaryStorage::class),
        $c->get('config')['cloudinary']['image'] ?? []
    ),

    // Domain image factory
    ImageFactory::class => fn() => new ImageFactory(),

    // Image repository
    DoctrineImageRepository::class => fn(ContainerInterface $c) => new DoctrineImageRepository(
        $c->get(Database::class)
    ),
];
