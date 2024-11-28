<?php

declare(strict_types=1);

namespace DigipolisGent\SyslogBundle\Monolog\Processor;

/**
 * Processor that transforms the level name to lowercase in a log record.
 */
final class LowerCaseLevelNameProcessor
{
    /**
     * Transforms the level name to lowercase in the given record.
     *
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        $record['level_name'] = strtolower($record['level_name']);

        return $record;
    }
}
