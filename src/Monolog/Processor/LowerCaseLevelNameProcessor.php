<?php

declare(strict_types=1);

namespace DigipolisGent\SyslogBundle\Monolog\Processor;

use Monolog\LogRecord;

/**
 * Processor that transforms the level name to lowercase in a log record.
 */
final class LowerCaseLevelNameProcessor
{
    /**
     * Transforms the level name to lowercase in the given record.
     *
     * @param \Monolog\LogRecord $record
     *
     * @return \Monolog\LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $record->extra['level_name'] = strtolower($record->level->getName());

        return $record;
    }
}
