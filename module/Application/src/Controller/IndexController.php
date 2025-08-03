<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Session\Container;


class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel([
            'message' => 'Welcome to Laminas MVC!'
        ]);
    }
}