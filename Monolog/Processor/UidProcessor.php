<?php

namespace DigipolisGent\SyslogBundle\Monolog\Processor;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Processor that adds a uid to the extra key of a log record.
 */
class UidProcessor
{

  /**
   * @var TokenStorageInterface
   */
  protected $tokenStorage;

  /**
   * Creates a new UidProcessor.
   *
   * @param TokenStorageInterface $tokenStorage
   */
  public function __construct(TokenStorageInterface $tokenStorage) {
      $this->tokenStorage = $tokenStorage;
  }

  /**
     * Adds the uid to the record's extra key.
     *
     * @param array $record
     *
     * @return array
     */
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
