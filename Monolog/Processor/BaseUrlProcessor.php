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
     * @var string
     */
    protected $defaultBaseUrl;

    /**
     * Creates a new BaseUrlProcessor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack, $defaultBaseUrl = null)
    {
        $this->requestStack = $requestStack;
        $this->defaultBaseUrl = $defaultBaseUrl;
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
        $record['extra']['base_url'] = $this->defaultBaseUrl;

        // Ensure we have a request (maybe we're in a console command).
        if (!$request = $this->requestStack->getCurrentRequest()) {
            // No current request. Set the referrer to the base url.
            $record['extra']['referrer'] = $this->defaultBaseUrl;
            return $record;
        }

        $record['extra']['base_url'] = $request->getSchemeAndHttpHost();

        return $record;
    }
}
