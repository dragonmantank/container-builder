<?php

namespace ContainerBuilder\Service;

class Redis extends AbstractService
{
    protected $versions = ['redis'];
    protected $config = [
        'volumes' => [],
        'services' => [
            'redis' => [
                'image' => 'redis',
            ]
        ]
    ];
    protected $serviceName = 'redis';

    protected function processRequest(array $request)
    {
        $requestConfig = $cbConfig = [];

        if ($request['cache']) {
            $requestConfig['redis'] = ['service' => 'redis'];
        }

        $this->overrides = ['docker' => $requestConfig, 'commands' => $cbConfig];
    }
}