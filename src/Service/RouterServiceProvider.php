<?php

namespace ApiMaster\Service;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RouterServiceProvider implements ServiceProviderInterface
{
  public function register(Container $app)
  {
    $app->get($app['api_version'] . '/beers', 'beers:index');
    $app->get($app['api_version'] . '/beers/{id}', 'beers:getBeers');

  }
}
