<?php

namespace ApiMaster\Service;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use ApiMaster\Controller\HomeController;

class ControllerServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
      $app['home'] = function(Container $app){
        return new HomeController($app);
      };
    }
}
