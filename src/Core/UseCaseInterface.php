<?php
declare(strict_types=1);

namespace Blog\Core;

interface UseCaseInterface
{
    /**
     * Execute the use case with the given input
     *
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     * @throws \Exception
     */
    public function execute(array $input): array;
}
