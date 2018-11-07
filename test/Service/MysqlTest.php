<?php

namespace ContainerBuilderTest\Service;

use PHPUnit\Framework\TestCase;
use ContainerBuilder\Service\Mysql;

class MysqlTest extends TestCase
{
    public function testBasicComposeConfigIsCorrect()
    {
        $request = [
            'database_mysql' => true,
            'database_envvars' => [
                ['id' => 1, 'name' => 'MYSQL_ROOT_PASSWORD', 'value' => 'test' ]
            ]
        ];

        $service = new Mysql();
        $processedRequest = $service->getConfig($request);

        $this->assertArrayHasKey('mysql', $processedRequest['docker-compose']['services']);
        $this->assertArrayHasKey('MYSQL_ROOT_PASSWORD', $processedRequest['docker-compose']['services']['mysql']['environment']);
        $this->assertSame('test', $processedRequest['docker-compose']['services']['mysql']['environment']['MYSQL_ROOT_PASSWORD']);
    }
}