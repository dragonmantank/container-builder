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

    protected function processRequest(array $request)
    {
        $requestConfig = $cbConfig = [];

        if ($request['python_version']) {
            $pythonPorts = $pythonVolumes = [];

            if (!empty($request['python_ports'])) {
                foreach ($request['python_ports'] as $ports) {
                    $pythonPorts[] = $ports['hostPort'] . ':' . $ports['srcPort'];
                }
            }            

            if (!empty($request['python_mountpoints'])) {
                foreach ($request['python_mountpoints'] as $volume) {
                    $pythonVolumes[] = $volume['localPath'] . ':' . $volume['containerPath'];
                }
            }

            $cbConfig['commands'][] = '"node") docker-compose run --rm -u $UID python ${ARGS};;';

            $requestConfig['python'] = [
                'service' => 'python',
                'services' => ['python' => [
                    'image' => 'node:' . $request['python_version'],
                    'volumes' => $pythonVolumes,
                ]],
            ];

            if (!empty($pythonPorts)) {
                $requestConfig['python']['services']['python']['ports'] = $pythonPorts;
            }
        }

        $this->overrides = ['docker' => $requestConfig, 'commands' => $cbConfig];
    }
}