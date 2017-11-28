<?php

namespace DigipolisGent\SyslogBundle\Tests\Monolog\Processor;

use DigipolisGent\SyslogBundle\Monolog\Processor\ClientIpProcessor;
use DigipolisGent\SyslogBundle\Monolog\Processor\TimestampProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

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
        $dateTime = new \DateTime();
        $id = uniqid();
        $processor = new TimestampProcessor();
        $record = $processor(['id' => $id, 'datetime' => $dateTime]);
        $this->assertEquals($record['timestamp'], $dateTime->getTimestamp());
        $this->assertEquals($record['id'], $id);
    }
}
