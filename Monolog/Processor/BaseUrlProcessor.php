<?php

namespace DigipolisGent\SyslogBundle\Monolog\Processor;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Processor that adds a base_url to the extra key of a log record.
 */
class BaseUrlProcessor
{

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * Creates a new BaseUrlProcessor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Adds the base_url to the record's extra key.
     *
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        $record['extra']['base_url'] = '';

        // Ensure we have a request (maybe we're in a console command).
        if (!$request = $this->requestStack->getCurrentRequest()) {
            return $record;
        }

        $record['extra']['base_url'] = $request->getSchemeAndHttpHost();

        return $record;
    }
}
