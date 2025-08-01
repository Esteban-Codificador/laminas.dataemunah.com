<?php
/**
 * Configuración de Navegación para Laminas
 * Archivo: config/autoload/navigation.global.php
 */

return [
    'navigation' => [
        'default' => [
            [
                'label' => 'Inicio',
                'route' => 'home',
                'resource' => 'home',
                'privilege' => 'view'
            ],
            [
                'label' => 'Capital Humano',
                'route' => 'capital-humano',
                'resource' => 'capital-humano',
                'privilege' => 'view',
                'pages' => [
                    [
                        'label' => 'Empleados',
                        'route' => 'empleados',
                        'resource' => 'empleados',
                        'privilege' => 'view'
                    ],
                    [
                        'label' => 'Nómina',
                        'route' => 'nomina',
                        'resource' => 'nomina',
                        'privilege' => 'view'
                    ],
                    [
                        'label' => 'Contratos',
                        'route' => 'contratos',
                        'resource' => 'contratos',
                        'privilege' => 'view'
                    ]
                ]
            ],
            [
                'label' => 'Turnos',
                'route' => 'turnos',
                'resource' => 'turnos',
                'privilege' => 'view'
            ],
            [
                'label' => 'Informes',
                'route' => 'informes',
                'resource' => 'informes',
                'privilege' => 'view',
                'pages' => [
                    [
                        'label' => 'Reportes',
                        'route' => 'reportes',
                        'resource' => 'reportes',
                        'privilege' => 'view'
                    ],
                    [
                        'label' => 'Estadísticas',
                        'route' => 'estadisticas',
                        'resource' => 'estadisticas',
                        'privilege' => 'view'
                    ]
                ]
            ]
        ]
    ]
];
