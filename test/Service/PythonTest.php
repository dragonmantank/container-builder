<?php

namespace ContainerBuilderTest\Service;

use PHPUnit\Framework\TestCase;
use ContainerBuilder\Service\Python;

class PythonTest extends TestCase
{
    public function testBasicComposeConfigIsCorrect()
    {
        $request = [
            'python_version' => '7.2',
        ];

        $service = new Python();
        $processedRequest = $service->getConfig($request);

        $this->assertArrayHasKey('python', $processedRequest['docker-compose']['services']);
    }

    public function testAddsCorrectVolumes()
    {
        $request = [
            'python_version' => '7.2',
            'python_mountpoints' => [
                ['localPath' => './', 'containerPath' => '/var/www/']
            ],
        ];

        $service = new Python();
        $processedRequest = $service->getConfig($request);

        $this->assertArrayHasKey('python', $processedRequest['docker-compose']['services']);
        $this->assertArrayHasKey('volumes', $processedRequest['docker-compose']['services']['python']);
        $this->assertCount(1, $processedRequest['docker-compose']['services']['python']['volumes']);
        $this->assertSame('./:/var/www/', $processedRequest['docker-compose']['services']['python']['volumes'][0]);
    }
}