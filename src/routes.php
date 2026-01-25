<?php

declare(strict_types=1);

use Blog\Core\Application;

return function (Application $app, Psr\Container\ContainerInterface $container): void {
    // Routes are already registered in Router service
    // This is just a placeholder for future route registrations
    
    // Example: You could register routes here if needed
    // $app->get('/custom', function ($request, $response) {
    //     $response->getBody()->write('Custom route');
    //     return $response;
    // });
};
