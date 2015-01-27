Base is a microframework (more like a mini framework) inspired in Slim that features, Aura components, Fast Routing (nikita popov's algorithm), PHP FIG PSR7 (mostly) compatible, Dependency Injection for controllers AND methods (Controller methods, Closures, etc), Full controller registration in router, Middleware support ...

This frameworks uses
====================
- phly/http (Request / Response)
- aura/session (Session)
- psr/http-message (PHP-FIG PSR-7)
- phroute/phroute (Router)
- interop/container


Usage:

Use as micro with Dependency Injection
``example\example3.php``

Use as micro without dependency injection
``example\example1.php``

Override service definitions, load a controller method
``example\example2.php``

Load a full controller class
``example\example4.php``




