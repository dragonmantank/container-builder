<?php

namespace ContainerBuilderTest\Service;

use PHPUnit\Framework\TestCase;
use ContainerBuilder\Service\NodeJS;

class NodeJSTest extends TestCase
{
    public function testBasicComposeConfigIsCorrect()
    {
        $request = [
            'nodejs_version' => '7.2',
        ];

        $service = new NodeJS();
        $processedRequest = $service->getConfig($request);

        $this->assertArrayHasKey('nodejs', $processedRequest['docker-compose']['services']);
    }

    public function testAddsCorrectVolumes()
    {
        $request = [
            'nodejs_version' => '7.2',
            'nodejs_mountpoints' => [
                ['localPath' => './', 'containerPath' => '/var/www/']
            ],
        ];

        $service = new NodeJS();
        $processedRequest = $service->getConfig($request);

        $this->assertArrayHasKey('nodejs', $processedRequest['docker-compose']['services']);
        $this->assertArrayHasKey('volumes', $processedRequest['docker-compose']['services']['nodejs']);
        $this->assertCount(1, $processedRequest['docker-compose']['services']['nodejs']['volumes']);
        $this->assertSame('./:/var/www/', $processedRequest['docker-compose']['services']['nodejs']['volumes'][0]);
    }
}