<?php

namespace ContainerBuilder;

use ContainerBuilder\Service\AbstractService;
use ContainerBuilder\Service\ServiceFactory;

class ConfigBuilder
{
    public function generateConfig($config = [])
    {
        $dockerCompose = ['version' => '3.4', 'volumes' => [], 'services' => []];

        $files = [];
        /**
         * @var string $serviceName
         * @var AbstractService $service
         */
        foreach ($config as $serviceName => $serviceConfig) {
            $service = ServiceFactory::create($serviceName, $serviceConfig);
            $dockerCompose = array_replace_recursive($dockerCompose, $service->getConfig($serviceConfig));

            $files += $service->getFiles();
        }

        return ['files' => $files, 'config' => $dockerCompose];
    }
}