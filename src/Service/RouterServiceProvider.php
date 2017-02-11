<?php

namespace ApiMaster\Service;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RouterServiceProvider implements ServiceProviderInterface
{
  public function register(Container $app)
  {
    $app->get('/', 'home:index');
    $app->get('/post/{slug}', 'home:show');
  }
}
