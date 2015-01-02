Base is a microframework (more like a mini framework) inspired in Slim that features, Aura components, Fast Routing (nikita popov's algorithm), PHP FIG PSR7 (mostly) compatible, Dependency Injection for controllers AND methods (Controller methods, Closures, etc), Full controller registration in router, Middleware support ...

This frameworks uses
====================
- aura/web (Request / Response)
- aura/session (Session)
- rdlowrey/auryn (Dependency Injection)
- psr/http-message (PHP-FIG PSR-7)
- phroute/phroute (Router)

----> Extra packages available but not included here:
- league/plates (View)
- (array) Module Loader
- (array) Route Loader
- Spot2 (Data Mapper (Doctrine))
- aura/input (form builder)
- vlucas/dotenv (env variable loading for development)

Usage:

Use as micro with Dependency Injection

```
$autoloader = require('../vendor/autoload.php');

use \Base\InjectorBuilder as Builder
use \Psr\Http\Message\IncomingRequestInterface as Request;
use \Base\DefaultServiceRegisterer as Services;

$app = (new Builder)
        ->register(new Services([], $autoloader))
        ->getDi()
        ->make('\Base\Interfaces\AppInterface');

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
use \Base\DefaultServiceRegisterer as Services

$app = (new Builder)
        ->register(new Services([], $autoloader))
        ->getDi()
        ->make('\Base\Interfaces\AppInterface');

$app->addRoute('GET', '/test/{id:i}', function ($id) {
    return 'id: ' . $id;
});

$app->run();
```


Override service definitions, Inject 2 services

```
$autoloader = require('../vendor/autoload.php');

use \Base\InjectorBuilder as Builder
use \Psr\Http\Message\IncomingRequestInterface as Request;
use \Base\DefaultServiceRegisterer as DefaultServices;
use \ExampleCo\Example\CustomServiceRegisterer as CustomServices;
use \Base\Interfaces\SessionInterface as Session;

$app = (new Builder)
        ->register(new DefaultServices($autoloader))
        ->register(new CustomServices)
        ->getDi()
        ->make('\Base\Interfaces\AppInterface');

$app->addRoute('GET', 'test/{id:i}', function ($id, Request $request, Session $session) {
    return 'route parameter id: ' . $id . '  via:' . $request->getMethod() . ' foo:' . $session->get('foo');
});
```



