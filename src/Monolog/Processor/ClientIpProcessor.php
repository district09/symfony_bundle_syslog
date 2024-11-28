<?php

namespace DigipolisGent\SyslogBundle\Monolog\Processor;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Processor that adds a client_ip to the extra key of a log record.
 */
class ClientIpProcessor
{

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var string
     */
    protected $cachedClientIp = null;

    /**
     * Creates a new ClientIpProcessor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Adds the client_ip to the record's extra key.
     *
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        // Yhe client_ip will hold the request's actual origin address.
        $record['extra']['client_ip'] = $this->cachedClientIp
            ? $this->cachedClientIp
            : 'unavailable';

        // Return if we already know client's IP
        if ($record['extra']['client_ip'] !== 'unavailable') {
            return $record;
        }

        // Ensure we have a request (maybe we're in a console command)
        if (!$request = $this->requestStack->getCurrentRequest()) {
            $this->cachedClientIp = '127.0.0.1';
            $record['extra']['client_ip'] = $this->cachedClientIp;
            return $record;
        }

        // If we do, get the client's IP, and cache it for later.
        $this->cachedClientIp = $request->getClientIp();
        $record['extra']['client_ip'] = $this->cachedClientIp;

        return $record;
    }
}
