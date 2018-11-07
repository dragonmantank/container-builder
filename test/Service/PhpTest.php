<?php

namespace ContainerBuilderTest\Service;

use PHPUnit\Framework\TestCase;
use ContainerBuilder\Service\Php;

class PhpTest extends TestCase
{
    public function testBasicComposeConfigIsCorrect()
    {
        $request = [
            'php_version' => '7.2',
            'cli' => true,
        ];

        $service = new Php();
        $processedRequest = $service->getConfig($request);

        $this->assertArrayHasKey('php-cli', $processedRequest['docker-compose']['services']);
        $this->assertFalse(array_key_exists('httpd', $processedRequest['docker-compose']['services']));
        $this->assertFalse(array_key_exists('composer', $processedRequest['docker-compose']['services']));
        $this->assertEquals($processedRequest['docker-compose']['services']['php-cli']['build'], './docker/php/');
    }

    public function testAddsCorrectVolumes()
    {
        $request = [
            'php_version' => '7.2',
            'cli' => true,
        ];

        $service = new Php();
        $processedRequest = $service->getConfig($request);

        $this->assertArrayHasKey('php-cli', $processedRequest['docker-compose']['services']);
        $this->assertArrayHasKey('volumes', $processedRequest['docker-compose']['services']['php-cli']);
        $this->assertCount(1, $processedRequest['docker-compose']['services']['php-cli']['volumes']);
        $this->assertSame('./:/var/www/', $processedRequest['docker-compose']['services']['php-cli']['volumes'][0]);
    }
}