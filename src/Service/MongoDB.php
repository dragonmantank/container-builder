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

    protected function processRequest(array $request)
    {
        $requestConfig = $cbConfig = [];

        if ($request['database_mongodb']) {
            $requestConfig['mongodb'] = [
                'service' => 'mongodb',
                'services' => ['mongodb' => [
                    'image' => 'mongo:' . $request['database_mongodb_version'],
                ]],
                'build-options' => ['image' => 'mongo:' . $request['database_mongodb_version']],
            ];
            $cbConfig['commands'][] = '"mongocli") docker-compose run --rm mongodb mongo mongodb://mongodb ${ARGS};;';
        }

        $this->overrides = ['docker' => $requestConfig, 'commands' => $cbConfig];
    }
}