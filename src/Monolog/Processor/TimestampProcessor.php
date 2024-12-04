<?php

declare(strict_types=1);

namespace DigipolisGent\SyslogBundle\Monolog\Processor;

use Monolog\LogRecord;

/**
 * Processor that adds a timestamp to a log record.
 */
final class TimestampProcessor
{
    /**
     * Adds the timestamp to the record.
     *
     * @param \Monolog\LogRecord $record
     *
     * @return \Monolog\LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $record->extra['timestamp'] = $record->datetime->getTimestamp();
        return $record;
    }
}
