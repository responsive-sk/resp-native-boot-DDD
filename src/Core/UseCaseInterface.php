<?php
declare(strict_types=1);

namespace Blog\Core;

interface UseCaseInterface
{
    /**
     * Execute the use case with input data
     */
    public function execute(array $input): array;
}
