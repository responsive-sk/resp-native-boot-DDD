<?php

declare(strict_types=1);

use Blog\Domain\Shared\Markdown\MarkdownParserInterface;
use Blog\Domain\Shared\Markdown\MarkdownPreviewService;
use Blog\Domain\Shared\Media\ImageUploaderInterface;
use Blog\Infrastructure\Shared\Markdown\CommonMarkParser;
use Blog\Infrastructure\Shared\Media\CloudinaryImageUploader;
use Blog\Infrastructure\Shared\Media\FileStorage;
use Blog\Infrastructure\Shared\Media\ImageOptimizer;
use Psr\Container\ContainerInterface;

return [
    // Shared Markdown Parser (CommonMark-based)
    MarkdownParserInterface::class => fn() => new CommonMarkParser(),

    // Shared Markdown Preview Service
    MarkdownPreviewService::class => fn(ContainerInterface $c) => new MarkdownPreviewService(
        $c->get(MarkdownParserInterface::class)
    ),

    // Shared Image Uploader (Cloudinary)
    ImageUploaderInterface::class => function (ContainerInterface $c) {
        $config = $c->get('config')['cloudinary'] ?? [];
        $cloud = $config['cloudinary'] ?? [];
        $image = $config['image'] ?? [];

        return new CloudinaryImageUploader(
            $cloud['cloud_name'] ?? '',
            $cloud['api_key'] ?? '',
            $cloud['api_secret'] ?? '',
            [
                'folder' => $image['default_folder'] ?? 'shared_uploads',
                'quality' => $image['quality'] ?? 'auto:good',
            ]
        );
    },

    // Shared Image Optimizer
    ImageOptimizer::class => fn() => new ImageOptimizer(),

    // Shared File Storage (fallback)
    FileStorage::class => function (ContainerInterface $c) {
        $imageConfig = $c->get('config')['cloudinary']['image'] ?? [];

        return new FileStorage(
            __DIR__ . '/../public/uploads',
            (int) ($imageConfig['max_size'] ?? ($_ENV['IMAGE_MAX_SIZE'] ?? 5242880)) // 5MB default
        );
    },
];
