<?php

declare(strict_types=1);

namespace Application;

use Laminas\Mvc\MvcEvent;
use Laminas\Session\Container;
use Laminas\Http\PhpEnvironment\Response;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    public function onBootstrap(MvcEvent $e): void
    {
        $application   = $e->getApplication();
        $eventManager  = $application->getEventManager();
        $sharedManager = $eventManager->getSharedManager();

        // Interceptar despachos del módulo 'Application'
        $sharedManager->attach(
            __NAMESPACE__, // Solo afecta a controladores del módulo Application
            'dispatch',
            function (MvcEvent $e) {
                $controller = $e->getTarget();
                $routeMatch = $e->getRouteMatch();
                $routeName  = $routeMatch ? $routeMatch->getMatchedRouteName() : '';

                // Rutas públicas o no protegidas
                $excluidas = ['auth', 'hello-world'];

                if (!in_array($routeName, $excluidas)) {
                    $session = new Container('auth');

                    if (!isset($session->usuario_id) || $session->usuario_id <= 0) {
                        // Redirigir al login si no hay sesión
                        $router   = $e->getRouter();
                        $url      = $router->assemble([], ['name' => 'auth']); // nombre de la ruta a /auth/login

                       $response = new Response();
                        $response->getHeaders()->addHeaderLine('Location', $url);
                        $response->setStatusCode(302);
                        $response->sendHeaders();
                        exit;
                    }
                }
            },
            100
        );
    }
}
