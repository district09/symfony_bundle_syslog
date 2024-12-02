<?php

declare(strict_types = 1);

namespace DigipolisGent\SyslogBundle\Tests\Monolog\Processor;

use DigipolisGent\SyslogBundle\Monolog\Processor\ClientIpProcessor;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @covers \DigipolisGent\SyslogBundle\Monolog\Processor\ClientIpProcessor
 *
 * @group DigipolisGentSyslogBundle
 */
final class ClientIpProcessorTest extends TestCase
{
    /**
     * Fallback to localhost (127.0.0.1) when there is no request.
     *
     * @test
     */
    public function itUsesLocalhostWhenThereIsNoRequest(): void
    {
        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->expects($this->once())->method('getCurrentRequest')->willReturn(null);

        $processor = new ClientIpProcessor($requestStack);
        $record = $processor(
            new LogRecord(new \DateTimeImmutable(), 'TEST', Level::Debug, 'Foo message')
        );

        self::assertEquals('127.0.0.1', $record->extra['client_ip']);
    }

    /**
     * IP from request is added to the log data.
     *
     * The extracted IP is cached in memory.
     *
     * @test
     */
    public function itUsesIpFromRequestInLogData(): void
    {
        $ip = '168.0.0.1';
        $request = $this->createMock(Request::class);
        $request->expects($this->once())->method('getClientIp')->willReturn($ip);
        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->expects($this->once())->method('getCurrentRequest')->willReturn($request);

        $id = uniqid('', true);
        $processor = new ClientIpProcessor($requestStack);
        $record = $processor(
            new LogRecord(new \DateTimeImmutable(), 'TEST', Level::Debug, 'Foo message')
        );
        self::assertEquals($ip, $record->extra['client_ip']);

        // The mocks expect their methods to be called once. Invoking this
        // processor twice should use its cached ip.
        $record2 = $processor(
            new LogRecord(new \DateTimeImmutable(), 'TEST', Level::Debug, 'Foo message')
        );
        self::assertEquals($ip, $record2->extra['client_ip']);
    }

    /**
     * No IP when there is none in the request.
     *
     * @test
     */
    public function itAddsEmptyStringWhenRequestHasNoIp(): void
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->once())->method('getClientIp')->willReturn(null);
        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->expects($this->once())->method('getCurrentRequest')->willReturn($request);

        $processor = new ClientIpProcessor($requestStack);
        $record = $processor(
            new LogRecord(new \DateTimeImmutable(), 'TEST', Level::Debug, 'Foo message')
        );

        self::assertEquals('', $record->extra['client_ip']);
    }
}
