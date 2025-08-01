<?php

/**
 * También puedes crear un Factory para manejar la navegación dinámica
 * Archivo: src/Navigation/NavigationFactory.php
 */

namespace App\Navigation;

use Laminas\Navigation\Navigation;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Laminas\Session\Container;

class NavigationFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Obtener la sesión
        $sessionContainer = new Container('caudataGlobal');
        
        // Determinar qué menú cargar basado en la sesión
        $menuType = $sessionContainer->menu ?? 1;
        
        // Cargar configuración según el tipo de menú
        $config = $this->getMenuConfig($menuType);
        
        return new Navigation($config);
    }
    
    private function getMenuConfig($menuType)
    {
        switch ($menuType) {
            case '7':
                return $this->getDocumentosMenu();
            case '9':
                return $this->getInventarioMenu();
            case '10':
                return $this->getInterventoriaMenu();
            case '11':
                return $this->getHelpdeskMenu();
            default:
                return $this->getPrincipalMenu();
        }
    }
    
    private function getPrincipalMenu()
    {
        return [
            [
                'label' => 'Inicio',
                'route' => 'home',
                'resource' => 'home'
            ],
            [
                'label' => 'Capital Humano',
                'route' => 'capital-humano',
                'resource' => 'capital-humano',
                'pages' => [
                    [
                        'label' => 'Empleados',
                        'route' => 'empleados',
                        'resource' => 'empleados'
                    ]
                ]
            ]
        ];
    }
    
    private function getDocumentosMenu()
    {
        return [
            [
                'label' => 'Documentos',
                'route' => 'documentos',
                'resource' => 'documentos'
            ]
        ];
    }
    
    private function getInventarioMenu()
    {
        return [
            [
                'label' => 'Inventario',
                'route' => 'inventario',
                'resource' => 'inventario'
            ]
        ];
    }
    
    private function getInterventoriaMenu()
    {
        return [
            [
                'label' => 'Interventoría',
                'route' => 'interventoria',
                'resource' => 'interventoria'
            ]
        ];
    }
    
    private function getHelpdeskMenu()
    {
        return [
            [
                'label' => 'Help Desk',
                'route' => 'helpdesk',
                'resource' => 'helpdesk'
            ]
        ];
    }
}