#Base#
Base is a microframework (more like a mini framework) inspired in Slim that features, Dependency Injection for classes and Closures / anonymous functions, PSR-7 compatible Request / Response, Aura Session, Middleware support, Fast Routing (nikita popov's algorithm), Full controller registration in router, Events, PSR-3 Logger.

##What differentiates ``Base`` from other micro frameworks?##
- Effortless Full Dependency Injection (Auto dependency resolution)
- Swappable Dependency Injection Container (interp/container compatible)
- Closure dependency Resolution / Injection
- PSR-7 Request / Response (Slim 3.0 will support it too)
- Fast Router

##This frameworks uses##
- phly/http (PSR-7 Request / Response)
- aura/session (Session data management)
- phroute/phroute (Wrapper Router around Nikita Popov's fast route)
- interop/container (Through our own base\di package)
- monolog/monolog (PSR-3 Logger)
- league/event


Usage example:

Use as micro with Dependency Injection
``example\example3.php``

Use as micro without dependency injection
``example\example1.php``

Override service definitions, load a controller method
``example\example2.php``

Load a full controller class
``example\example4.php``

Middleware Support:
``example\exampleMw.php``

Event Emitting:
``example\example-event.php``

Logging
``example\example-logger.php``


