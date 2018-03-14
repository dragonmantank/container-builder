<?php

namespace ContainerBuilder\Service;

class Redis extends AbstractService
{
    protected $versions = ['redis'];
    protected $config = [
        'volumes' => [],
        'services' => [
            'cache' => [
                'image' => 'redis',
            ]
        ]
    ];
    protected $serviceName = 'cache';
}