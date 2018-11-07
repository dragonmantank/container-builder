<?php

namespace ContainerBuilderTest\Service;

use PHPUnit\Framework\TestCase;
use ContainerBuilder\Service\Composer;

class ComposerTest extends TestCase
{
    public function testBasicComposeConfigIsCorrect()
    {
        $request = [
            'php_version' => '7.2',
            'composer' => true,
        ];

        $service = new Composer();
        $processedRequest = $service->getConfig($request);

        $this->assertArrayHasKey('composer', $processedRequest['docker-compose']['services']);
    }
}