<?php

namespace DigipolisGent\SyslogBundle\Tests\Monolog\Processor;

use DateTime;
use DigipolisGent\SyslogBundle\Monolog\Processor\TimestampProcessor;
use PHPUnit\Framework\TestCase;

class TimestampProcessorTest extends TestCase
{

    public function testInvokeNoDatetime()
    {
        $processor = new TimestampProcessor();
        $id = uniqid();
        $before = time();
        sleep(1);
        $record = $processor(['id' => $id]);
        sleep(1);
        $after = time();
        $this->assertGreaterThan($before, $record['timestamp']);
        $this->assertLessThan($after, $record['timestamp']);
        $this->assertEquals($record['id'], $id);
    }

    public function testInvoke()
    {
        $dateTime = new DateTime();
        $id = uniqid();
        $processor = new TimestampProcessor();
        $record = $processor(['id' => $id, 'datetime' => $dateTime]);
        $this->assertEquals($record['timestamp'], $dateTime->getTimestamp());
        $this->assertEquals($record['id'], $id);
    }
}
