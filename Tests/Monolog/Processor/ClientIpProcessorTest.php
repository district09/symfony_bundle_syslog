<?php

namespace DigipolisGent\SyslogBundle\Tests\Monolog\Processor;

use DigipolisGent\SyslogBundle\Monolog\Processor\ClientIpProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ClientIpProcessorTest extends TestCase
{

    public function testInvokeNoRequest()
    {
        $requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $requestStack->expects($this->once())->method('getCurrentRequest')->willReturn(null);
        $processor = new ClientIpProcessor($requestStack);
        $id = uniqid();
        $record = $processor(['id' => $id]);
        $this->assertEquals($record['extra']['client_ip'], 'unavailable');
        $this->assertEquals($record['id'], $id);
    }

    public function testInvoke()
    {
        $ip = uniqid();
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects($this->once())->method('getClientIp')->willReturn($ip);
        $requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $requestStack->expects($this->once())->method('getCurrentRequest')->willReturn($request);
        $processor = new ClientIpProcessor($requestStack);
        $id = uniqid();
        $record = $processor(['id' => $id]);
        $this->assertEquals($record['extra']['client_ip'], $ip);
        $this->assertEquals($record['id'], $id);

        // The mocks expect their methods to be called once. Invoking this
        // processor twice should use its cached ip.
        $record2 = $processor(['id' => $id]);
        $this->assertEquals($record2['extra']['client_ip'], $ip);
        $this->assertEquals($record2['id'], $id);
    }
}
