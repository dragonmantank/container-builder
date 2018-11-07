<?php

namespace ContainerBuilder\Service;

class NodeJS extends AbstractService
{
    protected $versions = ['node:9.10', 'node:8.11', 'node:6.14'];

    protected $config = [
        'volumes' => [],
        'services' => [
            'nodejs' => [
                'image' => 'node',
                'working_dir' => '/var/www/',
                'volumes' => ['./:/var/www/']
            ]
        ]
    ];

    protected $files = [
        __DIR__ . '/../../data/templates/php/Dockerfile' => 'docker/php/Dockerfile',
    ];

    protected $serviceName = 'nodejs';

    protected function processRequest(array $request)
    {
        $requestConfig = $cbConfig = [];

        if ($request['nodejs_version']) {
            $nodejsPorts = $nodejsVolumes = [];

            if (!empty($request['nodejs_ports'])) {
                foreach ($request['nodejs_ports'] as $ports) {
                    $nodejsPorts[] = $ports['hostPort'] . ':' . $ports['srcPort'];
                }
            }            

            if (!empty($request['nodejs_mountpoints'])) {
                foreach ($request['nodejs_mountpoints'] as $volume) {
                    $nodejsVolumes[] = $volume['localPath'] . ':' . $volume['containerPath'];
                }
            }

            $cbConfig['commands'][] = '"node") docker-compose run --rm -u $UID nodejs ${ARGS};;';

            $requestConfig['nodejs'] = [
                'service' => 'nodejs',
                'services' => ['nodejs' => [
                    'image' => 'node:' . $request['nodejs_version'],
                    'volumes' => $nodejsVolumes,
                ]],
            ];

            if (!empty($nodejsPorts)) {
                $requestConfig['nodejs']['services']['nodejs']['ports'] = $nodejsPorts;
            }
        }

        $this->overrides = ['docker' => $requestConfig, 'commands' => $cbConfig];
    }
}