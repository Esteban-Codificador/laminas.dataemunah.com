<?php

namespace Auth\Controller\Factory;

use Auth\Controller\AuthenticationController;
use Auth\Model\UsuarioMapper;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AuthenticationControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $usuarioMapper = $container->get(UsuarioMapper::class);
        
        return new AuthenticationController($usuarioMapper);
    }
}