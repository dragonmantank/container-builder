<?php

namespace ContainerBuilder\Service;

class MongoDB extends AbstractService
{
    protected $config = [
        'volumes' => ['mongodb_data' => ['driver' => 'local']],
        'services' => [
            'mongodb' => [
                'volumes' => ['mongodb_data:/data/db'],
            ]
        ]
    ];
    protected $serviceName = 'mongodb';
}