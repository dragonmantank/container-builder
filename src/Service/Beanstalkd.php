<?php

namespace ContainerBuilder\Service;

class Beanstalkd extends AbstractService
{
    protected $versions = ['schickling/beanstalkd'];
    protected $config = [
        'volumes' => [],
        'services' => [
            'beanstalkd' => [
                'image' => 'schickling/beanstalkd',
            ]
        ]
    ];
    protected $serviceName = 'beanstalkd';

    protected function processRequest(array $request)
    {
        $requestConfig = $cbConfig = [];

        if ($request['queue']) {
            $requestConfig['beanstalkd'] = ['service' => 'beanstalkd'];
        }

        $this->overrides = ['docker' => $requestConfig, 'commands' => $cbConfig];
    }
}