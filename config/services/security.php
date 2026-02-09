<?php

declare(strict_types=1);

use Blog\Security\AuthorizationService;
use Blog\Security\Authorization;
use Blog\Security\CsrfProtection;
use Psr\Container\ContainerInterface;
use ResponsiveSk\Slim4Session\SessionInterface;

return [
    AuthorizationService::class => fn(ContainerInterface $c) => new AuthorizationService(
        $c->get(SessionInterface::class)
    ),

    Authorization::class => function (ContainerInterface $c) {
        Authorization::setContainer($c);

        return new Authorization();
    },

    CsrfProtection::class => fn(ContainerInterface $c) => new CsrfProtection(
        $c->get(SessionInterface::class)
    ),
];
