<?php

namespace ContainerBuilder\Service;

/**
 * Shared base logic for generating Docker Compose files and Dockerfiles from service definitions
 *
 * @package ContainerBuilder\Service
 */
abstract class AbstractService
{
    /**
     * Built in default config for a service
     * @var array
     */
    protected $config = [];

    /**
     * Files that need to be included by default, like the Dockerfile
     * @var array
     */
    protected $files = [];

    /**
     * Overrides for the basic config, like additional ports or volumes
     * @var array
     */
    protected $overrides = [];

    /**
     * Name of this service
     * @var string
     */
    protected $serviceName;

    /**
     * Generates the Docker Compose config YAML block for a service
     *
     * @return array
     */
    public function getConfig(array $request)
    {
        $this->processRequest($request);

        if (isset($this->overrides['docker'][$this->serviceName])) {
            $yamlConfig = [
                'volumes' => $this->config['volumes'],
                'services' => [$this->serviceName => $this->config['services'][$this->serviceName]]
            ];
    
            if (isset($this->overrides['docker'][$this->serviceName]['volumes']) && count($this->overrides['docker'][$this->serviceName]['volumes'])) {
                $yamlConfig['volumes'] = array_replace_recursive($yamlConfig['volumes'], $this->overrides['docker'][$this->serviceName]['volumes']);
            }
            if (isset($this->overrides['docker'][$this->serviceName]['services']) && count($this->overrides['docker'][$this->serviceName]['services'])) {
                $yamlConfig['services'] = array_replace_recursive($yamlConfig['services'], $this->overrides['docker'][$this->serviceName]['services']);
            }
    
            if (isset($this->overrides['docker'][$this->serviceName]['ports']) && count($this->overrides['docker'][$this->serviceName]['ports']) == 0) {
                unset($this->overrides['docker'][$this->serviceName]['ports']);
            }
    
            return ['docker-compose' => $yamlConfig, 'commands' => $this->overrides['commands']];
        }
        
        return [];
    }

    /**
     * Returns the files and text that need to be bundled with the configuration
     *
     * @return array
     */
    public function getFiles()
    {
        $files = [];
        foreach ($this->files as $fsPath => $zipPath) {
            $contents = file_get_contents($fsPath);

            if (isset($this->overrides['docker']['build-options'])) {
                if (isset($this->overrides['docker']['build-options']['image'])) {
                    $contents = str_replace('{{ image }}', $this->overrides['docker']['build-options']['image'], $contents);
                }
            }

            $files[$zipPath] = $contents;
        }

        return $files;
    }

    abstract protected function processRequest(array $request);
}