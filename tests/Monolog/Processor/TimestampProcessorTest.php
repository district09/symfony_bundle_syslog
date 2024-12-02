<?php

declare(strict_types=1);

namespace DigipolisGent\SyslogBundle\Tests\Monolog\Processor;

use DigipolisGent\SyslogBundle\Monolog\Processor\TimestampProcessor;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DigipolisGent\SyslogBundle\Monolog\Processor\TimestampProcessor
 *
 * @group DigipolisGentSyslogBundle
 */
final class TimestampProcessorTest extends TestCase
{
    /**
     * Fallback to system time when there is no datetime in record data.
     *
     * @test
     */
    public function itConvertsLogRecordDateTimeToCreateTimestamp(): void
    {
        $logDateTime = new \DateTimeImmutable();

        $processor = new TimestampProcessor();
        $record = $processor(
            new LogRecord($logDateTime, 'TEST', Level::Debug, 'Foo message')
        );

        self::assertEquals(
            $logDateTime->getTimestamp(),
            $record->extra['timestamp']
        );
    }
}
