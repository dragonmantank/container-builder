<?php

namespace ContainerBuilderTest\Service;

use PHPUnit\Framework\TestCase;
use ContainerBuilder\Service\Beanstalkd;

class BeanstalkdTest extends TestCase
{
    public function testBasicComposeConfigIsCorrect()
    {
        $request = [
            'queue' => true,
        ];

        $service = new Beanstalkd();
        $processedRequest = $service->getConfig($request);

        $this->assertArrayHasKey('beanstalkd', $processedRequest['docker-compose']['services']);
    }
}