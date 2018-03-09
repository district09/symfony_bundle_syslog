<?php

namespace DigipolisGent\SyslogBundle\Monolog\Processor;

/**
 * Processor that adds the lowercase level name to a log record.
 */
class LowerCaseLevelNameProcessor
{

    /**
     * Adds the lowercase level name to the record.
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
