<?php

namespace ContainerBuilder\Service;

use ContainerBuilder\Service\Php;
use ContainerBuilder\Service\Httpd;
use ContainerBuilder\Service\Mysql;
use ContainerBuilder\Service\Redis;
use ContainerBuilder\Service\NodeJS;
use ContainerBuilder\Service\Python;
use ContainerBuilder\Service\Mailhog;
use ContainerBuilder\Service\MongoDB;
use ContainerBuilder\Service\Composer;
use ContainerBuilder\Service\Beanstalkd;

class ServiceFactory
{
    static protected $services = [
        'httpd' => Httpd::class,
        'composer' => Composer::class,
        'mysql' => Mysql::class,
        'php' => Php::class,
        'queue' => Beanstalkd::class,
        'cache' => Redis::class,
        'mailhog' => Mailhog::class,
        'nodejs' => NodeJS::class,
        'mongodb' => MongoDB::class,
        'python' => Python::class,
    ];

    static public function create($service)
    {
        $class = static::$services[$service];
        return new $class();
    }

    static public function getServices()
    {
        return static::$services;
    }
}