<?php

namespace DigipolisGent\SyslogBundle\Tests\Monolog\Processor;

use DigipolisGent\SyslogBundle\Monolog\Processor\BaseUrlProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class BaseUrlProcessorTest extends TestCase {

    public function testInvokeNoRequest()
    {
        $requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $requestStack->expects($this->once())->method('getCurrentRequest')->willReturn(null);
        $processor = new BaseUrlProcessor($requestStack);
        $id = uniqid();
        $record = $processor(['id' => $id]);
        $this->assertEquals($record['extra']['base_url'], '');
        $this->assertEquals($record['id'], $id);
    }

    public function testInvoke()
    {
        $url = uniqid();
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects($this->once())->method('getSchemeAndHttpHost')->willReturn($url);
        $requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $requestStack->expects($this->once())->method('getCurrentRequest')->willReturn($request);
        $processor = new BaseUrlProcessor($requestStack);
        $id = uniqid();
        $record = $processor(['id' => $id]);
        $this->assertEquals($record['extra']['base_url'], $url);
        $this->assertEquals($record['id'], $id);
    }
}
