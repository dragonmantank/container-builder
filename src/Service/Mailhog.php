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

    protected function processRequest(array $request)
    {
        $requestConfig = $cbConfig = [];

        if ($request['mailhog']) {
            $requestConfig['mailhog'] = ['service' => 'mailhog'];
        }

        $this->overrides = ['docker' => $requestConfig, 'commands' => $cbConfig];
    }
}