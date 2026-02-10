<?php

declare(strict_types=1);

use Blog\Application\Image\UploadImage;
use Blog\Domain\Image\Factory\ImageFactory;
use Blog\Infrastructure\Image\CloudinaryImageProcessor;
use Blog\Infrastructure\Image\CloudinaryImageUploader;
use Blog\Infrastructure\Persistence\Doctrine\DoctrineImageRepository;
use Blog\Infrastructure\Storage\CloudinaryStorage;
use Cloudinary\Cloudinary;
use Psr\Container\ContainerInterface;

return [
    // Backwards-compatible aliases for image services
    'cloudinary' => fn(ContainerInterface $c) => $c->get(Cloudinary::class),

    'image_storage' => fn(ContainerInterface $c) => $c->get(CloudinaryStorage::class),

    'image_processor' => fn(ContainerInterface $c) => $c->get(CloudinaryImageProcessor::class),

    'image_uploader' => fn(ContainerInterface $c) => $c->get(CloudinaryImageUploader::class),

    'image_factory' => fn(ContainerInterface $c) => $c->get(ImageFactory::class),

    'image_repository' => fn(ContainerInterface $c) => $c->get(DoctrineImageRepository::class),

    // Application use case wiring can still use string-based aliases if needed
    UploadImage::class => fn(ContainerInterface $c) => new UploadImage(
        $c->get(CloudinaryImageUploader::class),
        $c->get('user_repository'),
        $c->get(ImageFactory::class),
        $c->get(DoctrineImageRepository::class)
    ),
];
