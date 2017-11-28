<?php

namespace DigipolisGent\SyslogBundle\Tests\Monolog\Processor;

use DigipolisGent\SyslogBundle\Monolog\Processor\UidProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UidProcessorTest extends TestCase
{

    public function testInvokeNoToken()
    {
        $tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)->disableOriginalConstructor()->getMock();
        $tokenStorage->expects($this->once())->method('getToken')->willReturn(null);
        $processor = new UidProcessor($tokenStorage);
        $id = uniqid();
        $record = $processor(['id' => $id]);
        $this->assertEquals($record['id'], $id);
        $this->assertEquals($record['extra']['uid'], 0);
    }

    public function testInvokeNoUser()
    {
        $token = $this->getMockBuilder(TokenInterface::class)->disableOriginalConstructor()->getMock();
        $token->expects($this->once())->method('getUser')->willReturn(null);
        $tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)->disableOriginalConstructor()->getMock();
        $tokenStorage->expects($this->once())->method('getToken')->willReturn($token);
        $processor = new UidProcessor($tokenStorage);
        $id = uniqid();
        $record = $processor(['id' => $id]);
        $this->assertEquals($record['id'], $id);
        $this->assertEquals($record['extra']['uid'], 0);
    }

    public function testInvokeUserName()
    {
        $userName = uniqid();
        $token = $this->getMockBuilder(TokenInterface::class)->disableOriginalConstructor()->getMock();
        $token->expects($this->once())->method('getUser')->willReturn($userName);
        $tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)->disableOriginalConstructor()->getMock();
        $tokenStorage->expects($this->once())->method('getToken')->willReturn($token);
        $processor = new UidProcessor($tokenStorage);
        $id = uniqid();
        $record = $processor(['id' => $id]);
        $this->assertEquals($record['id'], $id);
        $this->assertEquals($record['extra']['uid'], $userName);
    }

    public function testInvokeUserNoGetId()
    {
        $userName = uniqid();
        $user = $this->getMockBuilder(\stdClass::class)->setMethods(['getUsername'])->getMock();
        $user->expects($this->once())->method('getUsername')->willReturn($userName);
        $token = $this->getMockBuilder(TokenInterface::class)->disableOriginalConstructor()->getMock();
        $token->expects($this->once())->method('getUser')->willReturn($user);
        $tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)->disableOriginalConstructor()->getMock();
        $tokenStorage->expects($this->once())->method('getToken')->willReturn($token);
        $processor = new UidProcessor($tokenStorage);
        $id = uniqid();
        $record = $processor(['id' => $id]);
        $this->assertEquals($record['id'], $id);
        $this->assertEquals($record['extra']['uid'], $userName);
    }

    public function testInvokeUserGetId()
    {
        $userId = uniqid();
        $user = $this->getMockBuilder(\stdClass::class)->setMethods(['getId'])->getMock();
        $user->expects($this->once())->method('getId')->willReturn($userId);
        $token = $this->getMockBuilder(TokenInterface::class)->disableOriginalConstructor()->getMock();
        $token->expects($this->once())->method('getUser')->willReturn($user);
        $tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)->disableOriginalConstructor()->getMock();
        $tokenStorage->expects($this->once())->method('getToken')->willReturn($token);
        $processor = new UidProcessor($tokenStorage);
        $id = uniqid();
        $record = $processor(['id' => $id]);
        $this->assertEquals($record['id'], $id);
        $this->assertEquals($record['extra']['uid'], $userId);
    }
}
