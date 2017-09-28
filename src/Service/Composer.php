<?php

namespace ContainerBuilder\Service;

class Composer extends AbstractService
{
    protected $versions = ['composer/composer'];
    protected $config = [
        'volumes' => [],
        'services' => [
            'composer' => [
                'image' => 'composer/composer',
                'volumes' => ['./:/var/www/', '~/.ssh/:/root/.ssh'],
                'tty' => true,
                'working_dir' => '/var/www/',
                'command' => 'composer install',
            ]
        ]
    ];
    protected $serviceName = 'composer';
}