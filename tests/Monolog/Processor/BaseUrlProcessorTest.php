<?php

declare(strict_types=1);

namespace DigipolisGent\SyslogBundle\Tests\Monolog\Processor;

use DigipolisGent\SyslogBundle\Monolog\Processor\BaseUrlProcessor;
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

        $id = uniqid('', true);
        $processor = new BaseUrlProcessor($requestStack);
        $record = $processor(['id' => $id]);

        self::assertEquals('', $record['extra']['base_url']);
        self::assertEquals($id, $record['id']);
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

        $id = uniqid('', true);
        $processor = new BaseUrlProcessor($requestStack, $defaultBaseUrl);
        $record = $processor(['id' => $id]);

        self::assertEquals($id, $record['id']);
        self::assertEquals($defaultBaseUrl, $record['extra']['base_url']);
        self::assertEquals($defaultBaseUrl, $record['extra']['referrer']);
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

        $id = uniqid('', true);
        $processor = new BaseUrlProcessor($requestStack, 'https://foo.bar');
        $record = $processor(['id' => $id]);

        self::assertEquals($id, $record['id']);
        self::assertEquals($url, $record['extra']['base_url']);
        self::assertFalse(isset($record['extra']['referrer']));
    }
}
