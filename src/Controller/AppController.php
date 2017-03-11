<?php

namespace ApiMaster\Controller;

use DateTime;
use DateTimeZone;
use Silex\Application;

class AppController
{
    protected $app;
    protected $createdAt;
    protected $updatedAt;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getDateTimeNow()
    {
        return $this->updatedAt = new DateTime("now", new DateTimeZone("America/Sao_Paulo"));
    }

    public function getDoctrineService()
    {
        return $getDoctrineService = $this->app['orm.em'];
    }
}