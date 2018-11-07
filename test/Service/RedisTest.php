<?php

namespace ContainerBuilderTest\Service;

use PHPUnit\Framework\TestCase;
use ContainerBuilder\Service\Redis;

class RedisTest extends TestCase
{
    public function testBasicComposeConfigIsCorrect()
    {
        $request = [
            'cache' => true,
        ];

        $service = new Redis();
        $processedRequest = $service->getConfig($request);

        $this->assertArrayHasKey('cache', $processedRequest['docker-compose']['services']);
    }
}