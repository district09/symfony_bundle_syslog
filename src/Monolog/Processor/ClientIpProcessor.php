<?php

declare(strict_types=1);

namespace DigipolisGent\SyslogBundle\Monolog\Processor;

use Monolog\LogRecord;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Processor that adds a client_ip to the extra key of a log record.
 */
final class ClientIpProcessor
{
    /**
     * Cache the client IP in memory.
     *
     * @var string|null
     */
    protected ?string $cachedClientIp = null;

    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    /**
     * Adds the client_ip to the record's extra key.
     *
     * @param \Monolog\LogRecord $record
     *
     * @return \Monolog\LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        if ($this->cachedClientIp !== null) {
            $record->extra['client_ip'] = $this->cachedClientIp;
            return $record;
        }

        // Use localhost when there is no request. Probably a CLI command.
        $request = $this->requestStack->getCurrentRequest();
        $this->cachedClientIp = $request
            ? $request->getClientIp() ?? ''
            : '127.0.0.1';

        $record->extra['client_ip'] = $this->cachedClientIp;
        return $record;
    }
}
