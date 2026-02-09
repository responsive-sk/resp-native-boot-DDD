<?php
// src/Core/UseCaseInterface.php - GENERICKÁ VERZE
declare(strict_types=1);

namespace Blog\Core;

/**
 * Generic Use Case Interface
 * 
 * @template TInput of array
 * @template TOutput of array|object
 */
interface UseCaseInterface
{
    /**
     * Execute the use case with input data
     * 
     * @param TInput $input
     * @return TOutput
     */
    public function execute(array $input): mixed;
}