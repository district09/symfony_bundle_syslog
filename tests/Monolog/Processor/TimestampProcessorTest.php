<?php

declare(strict_types=1);

namespace DigipolisGent\SyslogBundle\Tests\Monolog\Processor;

use DateTime;
use DigipolisGent\SyslogBundle\Monolog\Processor\TimestampProcessor;
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
    public function itUsesSystemTimeWhenNoDateTimeInRecord(): void
    {
        $before = time();

        $id = uniqid('', true);
        $processor = new TimestampProcessor();
        $record = $processor(['id' => $id]);

        $after = time();

        self::assertEquals($id, $record['id']);
        self::assertGreaterThanOrEqual($before, $record['timestamp']);
        self::assertLessThanOrEqual($after, $record['timestamp']);
    }

    /**
     * Fallback to system time when there is no datetime value in record data.
     *
     * @test
     */
    public function itUsesSystemTimeWhenNoDateTimeValueInRecord(): void
    {
        $before = time();

        $id = uniqid('', true);
        $processor = new TimestampProcessor();
        $record = $processor(['id' => $id, 'datetime' => '']);

        $after = time();

        self::assertEquals($id, $record['id']);
        self::assertGreaterThanOrEqual($before, $record['timestamp']);
        self::assertLessThanOrEqual($after, $record['timestamp']);
    }

    /**
     * The datetime value in record is used when available.
     *
     * @test
     */
    public function itUsesDateTimeFromRecordWhenAvailable(): void
    {
        $dateTime = new DateTime();
        $id = uniqid('', true);

        $processor = new TimestampProcessor();
        $record = $processor(['id' => $id, 'datetime' => $dateTime]);

        self::assertEquals($dateTime->getTimestamp(), $record['timestamp']);
        self::assertEquals($id, $record['id']);
    }
}
