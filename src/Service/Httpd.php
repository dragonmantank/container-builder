<?php

namespace ContainerBuilder\Service;

class Httpd extends Php
{
    protected $versions = [
		'php:5.6-apache',
		'php:7.0-apache',
		'php:7.1-apache',
		'php:7.2-apache',
		'php:7.3-apache',
	];

    protected $config = [
        'volumes' => [],
        'services' => [
            'httpd' => [
                'build' => './docker/httpd',
            ]
        ]
    ];

    protected $files = [
        __DIR__ . '/../../data/templates/httpd/Dockerfile' => 'docker/httpd/Dockerfile',
    ];

    protected $serviceName = 'httpd';

    public function getFiles()
    {
        $files = parent::getFiles();

        $docroot = $this->overrides['build-options']['docroot'];
        $docrootEscaped = str_replace('/', '\/', $docroot);
        $files['docker/httpd/Dockerfile'] = str_replace(
            ['{{ extensions }}', '{{ docroot }}', '{{ docroot_escaped }}'],
            [$extensions, $docroot, $docrootEscaped],
            $files['docker/httpd/Dockerfile']
        );
        
        return $files;
    }

    protected function processRequest(array $request)
    {
        $requestConfig = $cbConfig = [];

        if (!empty($request['php_version'])) {
            if ($request['php_webserver']) {
                $requestConfig['httpd'] = [
                    'service' => 'httpd',
                    'build-options' => [
                        'image' => 'php:' . $request['php_version'] . '-apache',
                        'extensions' => $request['php_extensions'],
                        'docroot' => $request['webserver_docroot'],
                    ],
                    'services' => ['httpd' => []
                ]];
        
                if (array_key_exists('webserver_ports', $request)) {
                    foreach ($request['webserver_ports'] as $ports) {
                        $webserverPorts[] = $ports['hostPort'] . ':' . $ports['srcPort'];
                    }
                }

                if (array_key_exists('webserver_mountpoints', $request)) {
                    foreach ($request['webserver_mountpoints'] as $volume) {
                        $webserverVolumes[] = $volume['localPath'] . ':' . $volume['containerPath'];
                    }
                }

                if (!empty($webserverPorts)) {
                    $requestConfig['httpd']['services']['httpd']['ports'] = $webserverPorts;
                }
        
                if (!empty($webserverVolumes)) {
                    $requestConfig['httpd']['services']['httpd']['volumes'] = $webserverVolumes;
                }
            }
        }

        $this->overrides = ['docker' => $requestConfig, 'commands' => $cbConfig];
    }
}
