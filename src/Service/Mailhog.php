<?php

namespace ContainerBuilder\Service;

class Mailhog extends AbstractService
{
    protected $versions = ['mailhog/mailhog'];
    protected $config = [
        'volumes' => [],
        'services' => [
            'mailhog' => [
                'image' => 'mailhog/mailhog',
                'ports' => ['8025:8025']
            ]
        ]
    ];
    protected $serviceName = 'mailhog';
}