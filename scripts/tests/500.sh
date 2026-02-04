# Spusti priamo
php -r "
require 'boot.php';
\$containerFactory = require 'config/container.php';
\$container = \$containerFactory();

echo '1. Controller class exists? ';
echo class_exists('Blog\Infrastructure\Http\Controller\Mark\ArticlesController') ? 'YES' : 'NO';
echo '\n';

if (class_exists('Blog\Infrastructure\Http\Controller\Mark\ArticlesController')) {
    \$reflection = new ReflectionClass('Blog\Infrastructure\Http\Controller\Mark\ArticlesController');
    echo '2. Available public methods:\n';
    foreach (\$reflection->getMethods(ReflectionMethod::IS_PUBLIC) as \$method) {
        echo '   - ' . \$method->getName() . '()\n';
    }
    
    echo '3. Has delete() method? ';
    echo \$reflection->hasMethod('delete') ? 'YES' : 'NO';
    echo '\n';
}
"