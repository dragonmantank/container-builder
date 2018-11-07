<?php

namespace ContainerBuilder\Service;

class Composer extends AbstractService
{
    protected $versions = ['composer', 'composer/composer'];
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

    public function processRequest(array $request)
    {
        $requestConfig = $cbConfig = [];

        if (!empty($request['php_version'])) {
            if ($request['composer']) {
                $requestConfig['composer'] = [
                    'service' => 'composer',
                    'services' => ['composer' => [
                        'image' => ($request['composer_official'] == 'true') ? 'composer' : 'composer/composer',
                    ]],
                ];
                $cbConfig['commands'][] = '"composer") docker-compose run --rm -u $UID composer ${ARGS};;';
            }
        }

        $this->overrides = ['docker' => $requestConfig, 'commands' => $cbConfig];
    }
}