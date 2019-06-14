<?php

namespace Tests\Mducharme\DateFormatter;

use Mducharme\DateFormatter\Parser;
use Mducharme\DateFormatter\Formatter;

class FormatterTest extends \PHPUnit\Framework\TestCase
{

    public function testFormatValidDateTimeDefaultFormatIsAtom()
    {
        $date = new \DateTime('2019-06-24 15:15:15');
        $formatter = new Formatter(new Parser());
        $res = $formatter($date);
        $this->assertEquals($res, $date->format(\DateTime::ATOM));
    }

    public function testFormatValidDateTimeFormat()
    {
        $date = new \DateTime('2019-06-24 15:15:15');
        $formatter = new Formatter(new Parser());
        $res = $formatter($date, 'rfc822');
        $this->assertEquals($res, $date->format(\DateTime::RFC822));
    }

    public function testFormatNullReturnsNull()
    {
        $formatter = new Formatter(new Parser());
        $res = $formatter(null);
        $this->assertNull($res);
    }
}
