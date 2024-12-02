<?php

declare(strict_types=1);

namespace DigipolisGent\SyslogBundle\Tests\Monolog\Processor;

use DigipolisGent\SyslogBundle\Monolog\Processor\BaseUrlProcessor;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @covers \DigipolisGent\SyslogBundle\Monolog\Processor\BaseUrlProcessor
 *
 * @group DigipolisGentSyslogBundle
 */
final class BaseUrlProcessorTest extends TestCase
{
    /**
     * No value when no request.
     *
     * @test
     */
    public function itReturnsEmptyStringWhenNoRequest(): void
    {
        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->expects($this->once())->method('getCurrentRequest')->willReturn(null);

        $processor = new BaseUrlProcessor($requestStack);
        $record = $processor(
            new LogRecord(new \DateTimeImmutable(), 'TEST', Level::Debug, 'Foo message')
        );

        self::assertEquals('', $record->extra['base_url']);
    }

    /**
     * Default URL is used when no request.
     *
     * @test
     */
    public function itReturnsDefaultUrlWhenNoRequest(): void
    {
        $defaultBaseUrl = uniqid('', true);

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->expects($this->once())->method('getCurrentRequest')->willReturn(null);

        $processor = new BaseUrlProcessor($requestStack, $defaultBaseUrl);
        $record = $processor(
            new LogRecord(new \DateTimeImmutable(), 'TEST', Level::Debug, 'Foo message')
        );

        self::assertEquals($defaultBaseUrl, $record->extra['base_url']);
        self::assertEquals($defaultBaseUrl, $record->extra['referrer']);
    }

    /**
     * Url is extracted from request.
     *
     * @test
     */
    public function itExtractsBaseUrlFromRequest(): void
    {
        $url = uniqid('', true);
        $request = $this->createMock(Request::class);
        $request->expects($this->once())->method('getSchemeAndHttpHost')->willReturn($url);
        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->expects($this->once())->method('getCurrentRequest')->willReturn($request);

        $processor = new BaseUrlProcessor($requestStack, 'https://foo.bar');
        $record = $processor(
            new LogRecord(new \DateTimeImmutable(), 'TEST', Level::Debug, 'Foo message')
        );

        self::assertEquals($url, $record->extra['base_url']);
        self::assertFalse(isset($record->extra['referrer']));
    }
}
