<?php

namespace DigipolisGent\SyslogBundle\Monolog\Processor;

use Symfony\Component\HttpFoundation\RequestStack;

class BaseUrlProcessor {

  /**
   * @var RequestStack
   */
  protected $requestStack;

  public function __construct(RequestStack $requestStack) {
    $this->requestStack = $requestStack;
  }

  public function __invoke(array $record) {
      // client_ip will hold the request's actual origin address
      $record['extra']['base_url'] = '';

      // Ensure we have a request (maybe we're in a console command)
      if (!$request = $this->requestStack->getCurrentRequest()) {
        return $record;
      }

      $record['extra']['base_url'] = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();

      return $record;
  }

}
