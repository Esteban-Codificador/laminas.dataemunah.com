<?php

declare(strict_types=1);

namespace Auth;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'auth' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/auth[/:action]',
                    'defaults' => [
                        'controller' => Controller\AuthenticationController::class,
                        'action'     => 'login',
                    ],
                ],
            ],

        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\AuthenticationController::class => Controller\Factory\AuthenticationControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Model\UsuarioMapper::class => Model\Factory\UsuarioMapperFactory::class,
            Middleware\AuthMiddleware::class => function($container) { return new Middleware\AuthMiddleware('/auth/login');},
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];