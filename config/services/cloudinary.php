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
    Cloudinary::class => fn(ContainerInterface $c) => new Cloudinary(
        $c->get('config')['cloudinary'] ?? []
    ),

    // Storage for original images
    CloudinaryStorage::class => fn(ContainerInterface $c) => new CloudinaryStorage(
        $c->get(Cloudinary::class),
        $c->get('config')['image'] ?? []
    ),

    // Image transformations
    CloudinaryImageProcessor::class => fn(ContainerInterface $c) => new CloudinaryImageProcessor(
        $c->get(Cloudinary::class),
        $c->get('config')['image']['transformations'] ?? []
    ),

    // High-level upload service
    CloudinaryImageUploader::class => fn(ContainerInterface $c) => new CloudinaryImageUploader(
        $c->get(CloudinaryStorage::class),
        $c->get('config')['image'] ?? []
    ),

    // Domain image factory
    ImageFactory::class => fn() => new ImageFactory(),

    // Image repository
    DoctrineImageRepository::class => fn(ContainerInterface $c) => new DoctrineImageRepository(
        $c->get(Database::class)
    ),
];
