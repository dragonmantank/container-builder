<?php

namespace ContainerBuilder;

use ContainerBuilder\Service\AbstractService;
use ContainerBuilder\Service\ServiceFactory;

class ConfigBuilder
{
    public function generateConfig(array $request = [])
    {
        $dockerCompose = ['version' => '3.4', 'volumes' => [], 'services' => []];

        $files = [];

        /**
         * @var string $serviceName
         * @var AbstractService $service
         */
        foreach (ServiceFactory::getServices() as $serviceName => $serviceClass) {
            $service = ServiceFactory::create($serviceName);
            $config = $service->getConfig($request);
            if (!empty($config)) {
                $dockerCompose = array_replace_recursive($dockerCompose, $config['docker-compose']);

                $files += $service->getFiles();
            }
        }

        return ['files' => $files, 'config' => $dockerCompose, 'commands' => $config['commands']];
    }
}