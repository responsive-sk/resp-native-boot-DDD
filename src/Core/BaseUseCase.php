<?php
// src/Core/BaseUseCase.php
declare(strict_types=1);

namespace Blog\Core;

abstract class BaseUseCase implements UseCaseInterface
{
    /**
     * Execute the use case with validation
     */
    final public function execute(array $input): mixed
    {
        $this->validate($input);
        return $this->handle($input);
    }

    /**
     * Handle the use case logic
     */
    abstract protected function handle(array $input): mixed;

    /**
     * Validate input data
     */
    abstract protected function validate(array $input): void;

    /**
     * Create success response
     */
    protected function success(mixed $data, string $message = 'Success'): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('c'),
        ];
    }

    /**
     * Create error response
     */
    protected function error(string $message, array $errors = [], int $code = 400): array
    {
        return [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'code' => $code,
            'timestamp' => date('c'),
        ];
    }
}