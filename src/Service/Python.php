<?php

namespace ContainerBuilder\Service;

class Python extends AbstractService
{
    protected $versions = ['python:3.6', 'python:3.5', 'python:3.4', 'python:2.7'];

    protected $config = [
        'volumes' => [],
        'services' => [
            'python' => [
                'image' => 'python:3.6',
                'working_dir' => '/app/',
            ]
        ]
    ];

    protected $files = [];

    protected $serviceName = 'python';
}