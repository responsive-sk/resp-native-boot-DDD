<?php

declare(strict_types=1);

namespace Application\Shared;

use Domain\Shared\Media\Image;
use Domain\Shared\Media\ImageUploaderInterface;
use Domain\Shared\Media\UploadedFile;

class UploadImage
{
    private ImageUploaderInterface $uploader;

    public function __construct(ImageUploaderInterface $uploader)
    {
        $this->uploader = $uploader;
    }

    public function execute(array $file, string $context): Image
    {
        $uploadedFile = new UploadedFile(
            $file['tmp_name'],
            $file['name'],
            $file['type'],
            $file['size']
        );

        $image = $this->uploader->upload($uploadedFile, [
            'context' => $context,
        ]);

        $image->addContext($context);

        return $image;
    }

    public function executeWithVariants(array $file, string $context, array $variants = []): Image
    {
        $uploadedFile = new UploadedFile(
            $file['tmp_name'],
            $file['name'],
            $file['type'],
            $file['size']
        );

        $image = $this->uploader->upload($uploadedFile, [
            'context' => $context,
        ]);

        $image->addContext($context);

        // Create variants if specified
        if (!empty($variants)) {
            $this->uploader->createVariants($image, $variants);
        } else {
            // Create default variants
            if (method_exists($this->uploader, 'generateDefaultVariants')) {
                $this->uploader->generateDefaultVariants($image);
            }
        }

        return $image;
    }

    public function validateFile(array $file): bool
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false;
        }

        $uploadedFile = new UploadedFile(
            $file['tmp_name'],
            $file['name'],
            $file['type'],
            $file['size']
        );

        return $uploadedFile->isValid() && $uploadedFile->isImage();
    }

    public function getFileInfo(array $file): array
    {
        $uploadedFile = new UploadedFile(
            $file['tmp_name'],
            $file['name'],
            $file['type'],
            $file['size']
        );

        return [
            'original_name' => $uploadedFile->getClientOriginalName(),
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => $uploadedFile->getSize(),
            'extension' => $uploadedFile->getExtension(),
            'basename' => $uploadedFile->getBasename(),
            'is_valid' => $uploadedFile->isValid(),
            'is_image' => $uploadedFile->isImage(),
        ];
    }
}
