<?php

namespace ContainerBuilderTest\Service;

use PHPUnit\Framework\TestCase;
use ContainerBuilder\Service\Mailhog;

class MailhogTest extends TestCase
{
    public function testBasicComposeConfigIsCorrect()
    {
        $request = [
            'mailhog' => true,
        ];

        $service = new Mailhog();
        $processedRequest = $service->getConfig($request);

        $this->assertArrayHasKey('mailhog', $processedRequest['docker-compose']['services']);
    }
}