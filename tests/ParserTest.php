<?php

namespace Tests\Mducharme\DateFormatter;

use Mducharme\DateFormatter\Parser;

class ParserTest extends \PHPUnit\Framework\TestCase
{
    public function testValidStringReturnsDateTime()
    {
        $parser = new Parser();
        $res = $parser('January 5th, 2010');
        $this->assertInstanceOf(\DateTimeInterface::class, $res);
        $this->assertEquals('2010-01-05', $res->format('Y-m-d'));
    }

    public function testInvalidStringThrowsException()
    {
        $parser = new Parser();
        $this->expectException(\InvalidArgumentException::class);
        $parser('-invalid');
    }

    public function testValidDateTimeReturnsSameObject()
    {
        $parser = new Parser();
        $date = new \DateTime('2010-01-05');
        $res = $parser($date);
        $this->assertInstanceOf(\DateTimeInterface::class, $res);
        $this->assertSame($res, $date);
    }

    public function testInvalidTypeThrowsException()
    {
        $parser = new Parser();
        $this->expectException(\InvalidArgumentException::class);
        $parser(false);
    }

    public function testInvalidObjectThrowsException()
    {
        $parser = new Parser();
        $this->expectException(\InvalidArgumentException::class);
        $parser(new \StdClass());
    }

    public function testParseNullReturnsNull()
    {
        $parser = new Parser();
        $res = $parser(null);
        $this->assertNull($res);
    }

    public function testParseNullNotAllowedThrowsException()
    {
        $parser = new Parser();
        $this->expectException(\InvalidArgumentException::class);
        $parser(null, false);
    }
}
