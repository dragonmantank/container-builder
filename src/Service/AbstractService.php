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

    public function __construct($overrides)
    {
        $this->overrides = $overrides;

        if (isset($this->overrides['service-name'])) {
            $this->serviceName = $this->overrides['service-name'];
        }
    }

    /**
     * Generates the Docker Compose config YAML block for a service
     *
     * @return array
     */
    public function getConfig()
    {
        $yamlConfig = [
            'volumes' => $this->config['volumes'],
            'services' => [$this->serviceName => $this->config['services'][$this->serviceName]]
        ];

        if (isset($this->overrides['volumes']) && count($this->overrides['volumes'])) {
            $yamlConfig['volumes'] = array_replace_recursive($yamlConfig['volumes'], $this->overrides['volumes']);
        }
        if (isset($this->overrides['services']) && count($this->overrides['services'])) {
            $yamlConfig['services'] = array_replace_recursive($yamlConfig['services'], $this->overrides['services']);
        }

        if (isset($this->overrides['ports']) && count($this->overrides['ports']) == 0) {
            unset($this->overrides['ports']);
        }

        return $yamlConfig;
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

            if (isset($this->overrides['build-options'])) {
                if (isset($this->overrides['build-options']['image'])) {
                    $contents = str_replace('{{ image }}', $this->overrides['build-options']['image'], $contents);
                }
            }

            $files[$zipPath] = $contents;
        }

        return $files;
    }
}