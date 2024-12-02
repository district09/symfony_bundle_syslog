<?php

declare(strict_types = 1);

namespace DigipolisGent\SyslogBundle\Tests\Monolog\Processor;

use DigipolisGent\SyslogBundle\Monolog\Processor\LowerCaseLevelNameProcessor;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DigipolisGent\SyslogBundle\Monolog\Processor\LowerCaseLevelNameProcessor
 *
 * @group DigipolisGentSyslogBundle
 */
final class LowerCaseLevelNameProcessorTest extends TestCase
{
    /**
     * Uppercase log levels are transformed to lowercase.
     *
     * @test
     */
    public function itChangesCaseOfLevelNameToLowerCase(): void
    {
        $processor = new LowerCaseLevelNameProcessor();
        $record = $processor(
            new LogRecord(new \DateTimeImmutable(), 'TEST', Level::Debug, 'Foo message')
        );

        self::assertSame('debug', $record->extra['level_name']);
    }
}
