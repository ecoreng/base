Use as micro with Dependency Injection

```
$autoloader = require('../vendor/autoload.php');

use \Base\InjectorBuilder as Builder
use \Psr\Http\Message\IncomingRequestInterface as Request;

$app = (new Builder)
        ->register(new \Base\DefaultServiceRegisterer([], $autoloader))
        ->getDi()
        ->make('\Base\App');

$app->addRoute('GET', '/test/{id:i}', function ($id, Request $request) {
    return 'id: ' . $id . '; via:' . $request->getMethod();
});

// run app
$app->run();

```




Use as micro without dependency injection
```
$autoloader = require('../vendor/autoload.php');

use \Base\InjectorBuilder as Builder
use \Base\DefaultServiceRegisterer as Registerer

$app = (new Builder)
        ->register(new Registerer([], $autoloader))
        ->getDi()
        ->make('\Base\App');

$app->addRoute('GET', '/test/{id:i}', function ($id) {
    return 'id: ' . $id;
});

$app->run();
```









Override service definitions and use the App defined as AppInterface alias

```
$autoloader = require('../vendor/autoload.php');

use \Base\InjectorBuilder as Builder
use \Psr\Http\Message\IncomingRequestInterface as Request;

$app = (new Builder)
        ->register(new \Base\DefaultServiceRegisterer(['environment' => ['base-url' => '/base/example/index.php']], $autoloader))
        ->register(new \ExampleCo\Example\CustomServiceRegisterer)
        ->getDi()
        ->make('\Base\Interfaces\AppInterface');

$app->addRoute('GET', 'test/{id:i}', function ($id, Request $request) {
    return 'route parameter id: ' . $id . '  via:' . $request->getMethod();
});
```








