<?php

namespace Auth\Model\Factory;

use Auth\Model\UsuarioMapper;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class UsuarioMapperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get('Laminas\Db\Adapter\Adapter');
        
        return new UsuarioMapper($dbAdapter);
    }
}