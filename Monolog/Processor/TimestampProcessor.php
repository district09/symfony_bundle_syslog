<?php

namespace DigipolisGent\SyslogBundle\Monolog\Processor;

class TimestampProcessor {

  public function __invoke(array $record) {
      $record['timestamp'] = time();
      if (isset($record['datetime']) && $record['datetime'] instanceof \DateTime) {
          $record['timestamp'] = $record['datetime']->getTimestamp();
      }
      return $record;
  }

}
