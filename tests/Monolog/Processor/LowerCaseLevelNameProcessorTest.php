<?php

declare(strict_types = 1);

namespace DigipolisGent\SyslogBundle\Tests\Monolog\Processor;

use DigipolisGent\SyslogBundle\Monolog\Processor\LowerCaseLevelNameProcessor;
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

        $id = uniqid('', true);
        $processor = new LowerCaseLevelNameProcessor();
        $record = $processor(['id' => $id, 'level_name' => 'DEBUG']);

        self::assertEquals($id, $record['id']);
        self::assertSame('debug', $record['level_name']);
    }
}
