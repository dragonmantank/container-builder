<?php

namespace ContainerBuilder\Service;

class Mysql extends AbstractService
{
    protected $config = [
        'volumes' => ['mysql_data' => ['driver' => 'local']],
        'services' => [
            'mysql' => [
                'volumes' => ['mysql_data:/var/lib/mysql'],
                'environment' => [
                    'MYSQL_ROOT_PASSWORD' => 'rootpassword',
                    'MYSQL_USER' => 'dbuser',
                    'MYSQL_PASSWORD' => 'dbuser',
                    'MYSQL_DATABASE' => 'testdb',
                ]

            ]
        ]
    ];
    protected $serviceName = 'mysql';
}