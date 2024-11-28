<?php

namespace DigipolisGent\SyslogBundle\Tests\Monolog\Processor;

use DigipolisGent\SyslogBundle\Monolog\Processor\LowerCaseLevelNameProcessor;
use PHPUnit\Framework\TestCase;

class LowerCaseLevelNameProcessorTest extends TestCase
{

    public function testInvoke()
    {
        // Assert uppercase log levels are transformed to lowercase.
        $id = uniqid();
        $processor = new LowerCaseLevelNameProcessor();
        $record = $processor(['id' => $id, 'level_name' => 'DEBUG']);
        $this->assertEquals($record['level_name'], 'debug');
        $this->assertEquals($record['id'], $id);

        $id = uniqid();
        $record = $processor(['id' => $id, 'level_name' => 'WARNING']);
        $this->assertEquals($record['level_name'], 'warning');
        $this->assertEquals($record['id'], $id);

        $id = uniqid();
        $record = $processor(['id' => $id, 'level_name' => 'ERROR']);
        $this->assertEquals($record['level_name'], 'error');
        $this->assertEquals($record['id'], $id);

        // Assert lowercase log levels stay lowercase.
        $id = uniqid();
        $processor = new LowerCaseLevelNameProcessor();
        $record = $processor(['id' => $id, 'level_name' => 'debug']);
        $this->assertEquals($record['level_name'], 'debug');
        $this->assertEquals($record['id'], $id);

        $id = uniqid();
        $record = $processor(['id' => $id, 'level_name' => 'warning']);
        $this->assertEquals($record['level_name'], 'warning');
        $this->assertEquals($record['id'], $id);

        $id = uniqid();
        $record = $processor(['id' => $id, 'level_name' => 'error']);
        $this->assertEquals($record['level_name'], 'error');
        $this->assertEquals($record['id'], $id);
    }
}
