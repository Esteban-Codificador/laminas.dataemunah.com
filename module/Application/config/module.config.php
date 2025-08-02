<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;


return [
    'router' => [
        'routes' => [
            'hello-world' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/hello/world',
                    'defaults' => [
                        'controller' => Controller\HelloController::class,
                        'action'     => 'world',
                    ],
                ],
            ],
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
       'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\HelloController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'navigation' => Laminas\View\Helper\Navigation\HelperFactory::class,
        ],
    ],
    // 'navigation' => [
    //     'default' => [
    //         'home' => [
    //             'label' => '',
    //             'route' => 'home',
    //             'order' => -100,
    //             'class' => 'btn btn-outline-light me-2',
    //             'icon'  => 'fa-solid fa-house',
    //         ],
    //         'capital-humano' => [
    //             'label' => 'Capital Humano',
    //             'uri'   => '#',
    //             'order' => 5,
    //             'class' => 'nav-link dropdown-toggle',
    //             'icon'  => 'fas fa-users me-2',
    //             'pages' => [
    //                 'trabajadores' => [
    //                     'label' => 'Trabajadores',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-user-plus me-2',
    //                     // 'resource' => 'clientes:asociado',     // Comentado por ahora
    //                     // 'privilege' => 'index',               // Comentado por ahora
    //                 ],
    //                 'contratos' => [
    //                     'label' => 'Contratos',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-file-contract me-2',
    //                     // 'resource' => 'clientes:contratoasociado',  // Comentado por ahora
    //                     // 'privilege' => 'allcontratos',              // Comentado por ahora
    //                 ],
    //                 'verificar-novedades' => [
    //                     'label' => 'Verificar Novedades',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-bell me-2',
    //                 ],
    //                 'reportes' => [
    //                     'label' => 'Reportes',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-chart-bar me-2',
    //                 ],
    //             ],
    //         ],
    //         'turnos' => [
    //             'label' => 'Turnos',
    //             'uri'   => '#',
    //             'order' => 10,
    //             'class' => 'nav-link dropdown-toggle',
    //             'icon'  => 'fas fa-clock me-2',
    //             'pages' => [
    //                 'programacion' => [
    //                     'label' => 'Programación',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-calendar-alt me-2',
    //                 ],
    //                 'intercambios' => [
    //                     'label' => 'Intercambios',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-exchange-alt me-2',
    //                 ],
    //                 'horarios' => [
    //                     'label' => 'Horarios',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-clock me-2',
    //                 ],
    //             ],
    //         ],
    //         'informes' => [
    //             'label' => 'Informes',
    //             'uri'   => '#',
    //             'order' => 15,
    //             'class' => 'nav-link dropdown-toggle',
    //             'icon'  => 'fas fa-chart-line me-2',
    //             'pages' => [
    //                 'asistencia' => [
    //                     'label' => 'Asistencia',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-user-check me-2',
    //                 ],
    //                 'productividad' => [
    //                     'label' => 'Productividad',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-trending-up me-2',
    //                 ],
    //                 'tiempo-trabajado' => [
    //                     'label' => 'Tiempo trabajado',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-calendar-week me-2',
    //                 ],
    //             ],
    //         ],
    //         'gestion' => [
    //             'label' => 'Gestión',
    //             'uri'   => '#',
    //             'order' => 20,
    //             'class' => 'nav-link dropdown-toggle',
    //             'icon'  => 'fas fa-cogs me-2',
    //             'pages' => [
    //                 'configuracion' => [
    //                     'label' => 'Configuración',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-users-cog me-2',
    //                 ],
    //                 'usuarios' => [
    //                     'label' => 'Usuarios',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-user-shield me-2',
    //                 ],
    //                 'empresas' => [
    //                     'label' => 'Empresas',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-building me-2',
    //                 ],
    //             ],
    //         ],
    //         'documentos' => [
    //             'label' => 'Documentos',
    //             'uri'   => '#',
    //             'order' => 25,
    //             'class' => 'nav-link dropdown-toggle',
    //             'icon'  => 'fas fa-folder me-2',
    //             'pages' => [
    //                 'politicas' => [
    //                     'label' => 'Políticas',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-file-alt me-2',
    //                 ],
    //                 'manuales' => [
    //                     'label' => 'Manuales',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-book me-2',
    //                 ],
    //                 'certificados' => [
    //                     'label' => 'Certificados',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-certificate me-2',
    //                 ],
    //             ],
    //         ],
    //         'mi-perfil' => [
    //             'label' => 'Mi perfil',
    //             'uri'   => '#',
    //             'order' => 30,
    //             'class' => 'nav-link dropdown-toggle',
    //             'icon'  => 'fas fa-user me-2',
    //             // 'resource' => 'default:aclusuarios',    // Comentado por ahora
    //             // 'privilege' => 'perfil',               // Comentado por ahora
    //             'pages' => [
    //                 'ver-perfil' => [
    //                     'label' => 'Ver perfil',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-id-card me-2',
    //                     // 'resource' => 'default:aclusuarios',   // Comentado por ahora
    //                     // 'privilege' => 'perfil',              // Comentado por ahora
    //                 ],
    //                 'editar-perfil' => [
    //                     'label' => 'Editar perfil',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-edit me-2',
    //                 ],
    //                 'cambiar-password' => [
    //                     'label' => 'Cambiar contraseña',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-key me-2',
    //                     // 'resource' => 'default:aclusuarios',   // Comentado por ahora
    //                     // 'privilege' => 'pass',                // Comentado por ahora
    //                 ],
    //                 'logout' => [
    //                     'label' => 'Cerrar sesión',
    //                     'uri'   => '#',
    //                     'icon'  => 'fas fa-sign-out-alt me-2',
    //                     'class' => 'text-danger',
    //                     'order' => 100,
    //                 ],
    //             ],
    //         ],
    //     ],
    // ],
    'navigation' => [
        'default' => [
            'home' => [
                'label' => 'Inicio',
                'route' => 'home',
            ],
            'capital-humano' => [
                'label' => 'Capital Humano',
                'uri'   => '#',
                'pages' => [
                    'trabajadores' => [
                        'label' => 'Trabajadores',
                        'uri'   => '#',
                    ],
                    'contratos' => [
                        'label' => 'Contratos',
                        'uri'   => '#',
                    ],
                ],
            ],
        ],
    ],
];