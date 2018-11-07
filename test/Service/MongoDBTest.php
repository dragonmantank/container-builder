<?php

namespace ContainerBuilderTest\Service;

use PHPUnit\Framework\TestCase;
use ContainerBuilder\Service\MongoDB;

class MongoDBTest extends TestCase
{
    public function testBasicComposeConfigIsCorrect()
    {
        $request = [
            'database_mongodb' => true,
        ];

        $service = new MongoDB();
        $processedRequest = $service->getConfig($request);

        $this->assertArrayHasKey('mongodb', $processedRequest['docker-compose']['services']);
    }
}