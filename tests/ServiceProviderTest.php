<?php

namespace Tests\Mducharme\DateFormatter;

use Mducharme\DateFormatter\ServiceProvider;

class ServiceProviderTest extends \PhpUnit\Framework\TestCase
{
    public function testProperties()
    {
        $container = new \Pimple\Container();
        $container->register(new ServiceProvider());
        $this->assertEquals('atom', $container['date/default-format']);
        $this->assertEquals([], $container['date/custom-formats']);
    }

    public function testServices()
    {
        $container = new \Pimple\Container();
        $container->register(new ServiceProvider());
        $this->assertInstanceOf(\Mducharme\DateFormatter\Parser::class, $container['date/parser']);
        $this->assertInstanceOf(\Mducharme\DateFormatter\Formatter::class, $container['date/formatter']);
    }
}
