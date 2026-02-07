<?php

declare(strict_types=1);

return [
    // Shared Markdown Parser
    Domain\Shared\Markdown\MarkdownParserInterface::class =>
        fn () => new Infrastructure\Shared\Markdown\ParsedownAdapter(),

    // Shared Image Uploader
    Domain\Shared\Media\ImageUploaderInterface::class =>
        fn ($c) => new Infrastructure\Shared\Media\CloudinaryImageUploader(
            $_ENV['CLOUDINARY_CLOUD_NAME'],
            $_ENV['CLOUDINARY_API_KEY'],
            $_ENV['CLOUDINARY_API_SECRET'],
            [
                'folder' => $_ENV['IMAGE_DEFAULT_FOLDER'] ?? 'shared_uploads',
                'quality' => $_ENV['IMAGE_QUALITY'] ?? 'auto:good',
            ]
        ),

    // Shared Markdown Preview Service
    // Domain\Shared\Markdown\MarkdownPreviewService::class =>
    //     fn ($c) => new Domain\Shared\Markdown\MarkdownPreviewService(
    //         $c->get(Domain\Shared\Markdown\MarkdownParserInterface::class)
    //     ),

    Domain\Shared\Markdown\MarkdownParserInterface::class => 
        fn() => new \Infrastructure\Shared\Markdown\CommonMarkParser(), // Alebo SafeParsedownParser


    // Shared Image Optimizer
    Infrastructure\Shared\Media\ImageOptimizer::class =>
        fn () => new Infrastructure\Shared\Media\ImageOptimizer(),

    // Shared File Storage (fallback)
    Infrastructure\Shared\Media\FileStorage::class =>
        fn () => new Infrastructure\Shared\Media\FileStorage(
            __DIR__ . '/../public/uploads',
            (int) ($_ENV['IMAGE_MAX_SIZE'] ?? 5242880) // 5MB
        ),
];
