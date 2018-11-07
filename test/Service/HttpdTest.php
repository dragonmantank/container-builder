<?php

namespace ContainerBuilderTest\Service;

use PHPUnit\Framework\TestCase;
use ContainerBuilder\Service\Httpd;

class HttpdTest extends TestCase
{
    public function testBasicComposeConfigIsCorrect()
    {
        $request = [
            'php_version' => '7.2',
            'php_webserver' => true,
        ];

        $service = new Httpd();
        $processedRequest = $service->getConfig($request);

        $this->assertArrayHasKey('httpd', $processedRequest['docker-compose']['services']);
        $this->assertEquals($processedRequest['docker-compose']['services']['httpd']['build'], './docker/httpd');
    }

    public function testAddsCorrectVolumes()
    {
        $request = [
            'php_version' => '7.2',
            'php_webserver' => true,
            'webserver_mountpoints' => [
                ['localPath' => './', 'containerPath' => '/var/www/']
            ],
        ];

        $service = new Httpd();
        $processedRequest = $service->getConfig($request);

        $this->assertArrayHasKey('httpd', $processedRequest['docker-compose']['services']);
        $this->assertArrayHasKey('volumes', $processedRequest['docker-compose']['services']['httpd']);
        $this->assertCount(1, $processedRequest['docker-compose']['services']['httpd']['volumes']);
        $this->assertSame('./:/var/www/', $processedRequest['docker-compose']['services']['httpd']['volumes'][0]);
    }
}