<?php

declare(strict_types=1);

namespace DigipolisGent\SyslogBundle\Monolog\Processor;

/**
 * Processor that adds a timestamp to a log record.
 */
final class TimestampProcessor
{
    /**
     * Adds the timestamp to the record.
     *
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record): array
    {
        $record['timestamp'] = (isset($record['datetime']) && $record['datetime'] instanceof \DateTime)
            ? $record['datetime']->getTimestamp()
            : time();

        return $record;
    }
}
