<?php

declare(strict_types=1);

namespace DigipolisGent\SyslogBundle\Monolog\Processor;

use Monolog\LogRecord;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Processor that adds a base_url to the extra key of a log record.
 */
final readonly class BaseUrlProcessor
{
    public function __construct(
        private RequestStack $requestStack,
        private ?string $defaultBaseUrl = null,
    ) {
    }

    /**
     * Adds the base_url to the record's extra key.
     *
     * @param \Monolog\LogRecord $record
     *
     * @return \Monolog\LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $record->extra['base_url'] = $this->defaultBaseUrl;

        // Ensure we have a request (maybe we're in a console command).
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            // No current request. Set the referrer to the base url.
            $record->extra['referrer'] = $this->defaultBaseUrl;
            return $record;
        }

        $record->extra['base_url'] = $request->getSchemeAndHttpHost();
        return $record;
    }
}
