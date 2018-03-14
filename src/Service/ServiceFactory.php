<?php

namespace ContainerBuilder\Service;

class ServiceFactory
{
    static protected $services = [
        'httpd' => 'ContainerBuilder\Service\Httpd',
        'composer' => 'ContainerBuilder\Service\Composer',
        'mysql' => 'ContainerBuilder\Service\Mysql',
        'php' => 'ContainerBuilder\Service\Php',
        'queue' => Beanstalkd::class,
        'cache' => Redis::class,
        'mailhog' => Mailhog::class,
    ];

    static public function create($service, $config = [])
    {
        $class = static::$services[$config['service']];
        return new $class($config);
    }
}