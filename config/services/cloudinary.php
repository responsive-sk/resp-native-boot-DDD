<?php

declare(strict_types=1);

use Blog\Database\Database;
use Psr\Container\ContainerInterface;

return [
    'cloudinary' => fn (ContainerInterface $c) => new \Cloudinary\Cloudinary($c->get('config')['cloudinary'] ?? []),

    'image_storage' => fn (ContainerInterface $c) => new \Blog\Infrastructure\Storage\CloudinaryStorage(
        $c->get('cloudinary'),
        $c->get('config')['image'] ?? []
    ),

    'image_processor' => fn (ContainerInterface $c) => new \Blog\Infrastructure\Image\CloudinaryImageProcessor(
        $c->get('cloudinary'),
        $c->get('config')['image']['transformations'] ?? []
    ),

    'image_uploader' => fn (ContainerInterface $c) => new \Blog\Infrastructure\Image\CloudinaryImageUploader(
        $c->get('image_storage'),
        $c->get('config')['image'] ?? []
    ),

    'image_factory' => fn () => new \Blog\Domain\Image\Factory\ImageFactory(),

    'image_repository' => fn (ContainerInterface $c) => new \Blog\Infrastructure\Persistence\Doctrine\DoctrineImageRepository(
        $c->get(Database::class)
    ),
];
