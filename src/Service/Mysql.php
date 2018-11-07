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

    protected function processRequest(array $request)
    {
        $requestConfig = $cbConfig = $databaseEnvvars = [];

        if ($request['database_mysql']) {
            foreach ($request['database_envvars'] as $dbEnvvar) {
                $databaseEnvvars[$dbEnvvar['name']] =  $dbEnvvar['value'];
            }

            $requestConfig['mysql'] = [
                'service' => 'mysql',
                'services' => ['mysql' => [
                    'image' => 'mysql:' . $request['database_mysql_version'],
                    'environment' => $databaseEnvvars
                ]],
                'build-options' => ['image' => 'mysql:' . $request['database_mysql_version']],
            ];
            $cbConfig['commands'][] = '"mysqlcli") docker-compose run --rm mysql mysql -h mysql ${ARGS};;';
        }

        $this->overrides = ['docker' => $requestConfig, 'commands' => $cbConfig];
    }
}