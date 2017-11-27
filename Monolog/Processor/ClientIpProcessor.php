<?php

namespace DigipolisGent\SyslogBundle\Monolog\Processor;

class ClientIpProcessor {

  private $requestStack;
  private $cachedClientIp = null;

  public function __construct(RequestStack $requestStack) {
    $this->requestStack = $requestStack;
  }

  public function __invoke(array $record) {
      // client_ip will hold the request's actual origin address
      $record['extra']['client_ip'] = $this->cachedClientIp ? $this->cachedClientIp : 'unavailable';

      // Return if we already know client's IP
      if ($record['extra']['client_ip'] !== 'unavailable') {
        return $record;
      }

      // Ensure we have a request (maybe we're in a console command)
      if (!$request = $this->requestStack->getCurrentRequest()) {
        return $record;
      }

      // If we do, get the client's IP, and cache it for later.
      $this->cachedClientIp = $request->getClientIp();
      $record['extra']['client_ip'] = $this->cachedClientIp;

      return $record;
  }

}
