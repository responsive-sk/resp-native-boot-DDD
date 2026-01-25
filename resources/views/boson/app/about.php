<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->escapeHtml($title); ?></title>
    <meta name="description" content="<?php echo $this->escapeHtml($description); ?>">
    <link rel="stylesheet" href="<?php echo $this->asset('css/app.css'); ?>">
</head>
<body>
    <div class="min-h-screen bg-gray-50">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900"><?php echo $this->escapeHtml($title); ?></h1>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="border-4 border-dashed border-gray-200 rounded-lg p-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">O projekte ChubbyBlog</h2>
                    <p class="text-gray-600 mb-4">
                        ChubbyBlog je moderný blog postavený s DDD (Domain Driven Design) + Hexagonal Architecture.
                    </p>
                    <p class="text-gray-600 mb-4">
                        Projekt demonštruje osvedčené postupy v PHP vývoji vrátane:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Domain Driven Design</li>
                        <li>Hexagonal Architecture (Ports & Adapters)</li>
                        <li>Dependency Injection Container</li>
                        <li>Repository pattern</li>
                        <li>Command/Query separation</li>
                        <li>Modern PHP features (PHP 8.1+)</li>
                        <li>Tailwind CSS pre štýlovanie</li>
                        <li>Plates templating engine</li>
                    </ul>
                </div>
            </div>
        </main>
    </div>
</body>
</html>