<?php

declare(strict_types=1);

namespace Blog\Core;

abstract class BaseUseCase implements UseCaseInterface
{
    /**
     * Create success response
     */
    protected function success(array $data = []): array
    {
        return [
            'success' => true,
            'data' => $data,
        ];
    }

    /**
     * Create error response
     */
    protected function error(string $message, array $errors = []): array
    {
        return [
            'success' => false,
            'error' => $message,
            'errors' => $errors,
        ];
    }

    /**
     * Validate input data - override in child classes
     */
    protected function validate(array $input): void
    {
        // Default validation - override in specific use cases
    }
}
