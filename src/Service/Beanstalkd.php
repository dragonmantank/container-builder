<?php

namespace ContainerBuilder\Service;

class Beanstalkd extends AbstractService
{
    protected $versions = ['schickling/beanstalkd'];
    protected $config = [
        'volumes' => [],
        'services' => [
            'queue' => [
                'image' => 'schickling/beanstalkd',
            ]
        ]
    ];
    protected $serviceName = 'queue';
}