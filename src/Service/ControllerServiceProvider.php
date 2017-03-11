<?php

namespace ApiMaster\Service;

use ApiMaster\Controller\BeerController;
use ApiMaster\Controller\UserController;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ControllerServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['beers'] = function(Container $app){
            return new BeerController($app);
        };

        $app['users'] = function(Container $app){
            return new UserController($app);
        };
    }
}
