<?php
// Archivo: module/Application/src/View/Helper/BootstrapNavigation.php

namespace Application\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Laminas\Navigation\AbstractContainer;

class BootstrapNavigation extends AbstractHelper
{
   public function __invoke($container = null)
    {
        if (is_string($container)) {
            // Obtener el contenedor de navegación registrado con ese nombre
            $container = $this->getView()->navigation($container)->getContainer();
        }

        if (!$container instanceof \Laminas\Navigation\AbstractContainer) {
            // Si sigue sin ser un contenedor válido, usar el predeterminado
            $container = $this->getView()->navigation()->getContainer();
        }

        return $this->renderMenu($container);
    }

    
    protected function renderMenu(AbstractContainer $container)
    {
        $html = '<ul class="nav nav-pills context-nav-pills">';
        
        foreach ($container as $page) {
            if (!$page->isVisible()) {
                continue;
            }
            
            $html .= $this->renderPage($page);
        }
        
        $html .= '</ul>';
        return $html;
    }
    
    protected function renderPage($page)
    {
        $hasChildren = $page->hasPages();
        $label = $this->getView()->escapeHtml($page->getLabel());
        $href = $page->getHref();
        $icon = $page->get('icon');
        $class = $page->get('class');
        
        // Caso especial para el botón home
        if ($page->get('route') === 'home') {
            return sprintf(
                '<li class="nav-item">
                    <a class="%s" href="%s" type="button">
                        <i class="%s"></i>
                    </a>
                </li>',
                $class ?: 'btn btn-outline-light me-2',
                $href,
                $icon ?: ''
            );
        }
        
        if ($hasChildren) {
            // Elemento con dropdown
            $dropdownId = strtolower(str_replace(' ', '', $label)) . 'Dropdown';
            
            $html = sprintf(
                '<li class="nav-item dropdown">
                    <a class="%s" href="%s" id="%s" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="%s"></i>
                        %s
                    </a>
                    <ul class="dropdown-menu context-dropdown">',
                $class ?: 'nav-link dropdown-toggle',
                $href,
                $dropdownId,
                $icon ?: '',
                $label
            );
            
            $childrenCount = 0;
            foreach ($page->getPages() as $child) {
                if (!$child->isVisible()) {
                    continue;
                }
                
                $childrenCount++;
                $childLabel = $this->getView()->escapeHtml($child->getLabel());
                $childHref = $child->getHref();
                $childIcon = $child->get('icon');
                $childClass = $child->get('class');
                
                // Agregar separador antes del último elemento si es "Cerrar sesión"
                if ($child->get('order') == 100 && $childrenCount > 1) {
                    $html .= '<li><hr class="dropdown-divider"></li>';
                }
                
                $html .= sprintf(
                    '<li><a class="dropdown-item %s" href="%s"><i class="%s"></i>%s</a></li>',
                    $childClass ?: '',
                    $childHref,
                    $childIcon ?: '',
                    $childLabel
                );
            }
            
            $html .= '</ul></li>';
            
            return $html;
        } else {
            // Elemento simple
            return sprintf(
                '<li class="nav-item">
                    <a class="%s" href="%s">
                        <i class="%s"></i>
                        %s
                    </a>
                </li>',
                $class ?: 'nav-link',
                $href,
                $icon ?: '',
                $label
            );
        }
    }
}