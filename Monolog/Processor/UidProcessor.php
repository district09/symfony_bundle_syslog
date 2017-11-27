<?php

namespace DigipolisGent\SyslogBundle\Monolog\Processor;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UidProcessor {

  /**
   * @var TokenStorageInterface
   */
  protected $tokenStorage;

  public function __construct(TokenStorageInterface $tokenStorage) {
    $this->tokenStorage = $tokenStorage;
  }

  public function __invoke(array $record) {
      // client_ip will hold the request's actual origin address
      $record['extra']['uid'] = 0;

      if (null === $token = $this->tokenStorage->getToken()) {
          return $record;
      }

      if (!is_object($user = $token->getUser())) {
          // e.g. anonymous authentication
          return $record;
      }

      $record['extra']['uid'] = is_scalar($user) ? $user : (method_exists($user, 'getId') ? $user->getId() : $user->getUsername());

      return $record;
  }

}
