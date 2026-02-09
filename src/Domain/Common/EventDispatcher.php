<?php
// src/Domain/Common/EventDispatcher.php
declare(strict_types=1);

namespace Blog\Domain\Common;

interface EventDispatcher
{
    public function dispatch(DomainEvent $event): void;
    public function subscribe(string $eventClass, callable $handler): void;
}
