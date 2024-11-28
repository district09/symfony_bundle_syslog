<?php

namespace DigipolisGent\SyslogBundle\Monolog\Processor;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Processor that adds an uid to the extra key of a log record.
 */
final class UidProcessor
{
    /**
     * Creates a new UidProcessor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        private TokenStorageInterface $tokenStorage
    ) {
    }

    /**
     * Adds the uid to the record's extra key.
     *
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record): array
    {
        // client_ip will hold the request's actual origin address.
        $record['extra']['uid'] = 0;

        $token = $this->tokenStorage->getToken();
        if ($token === null) {
            return $record;
        }

        $user = $token->getUser();
        if (!$user) {
            return $record;
        }

        $record['extra']['uid'] = $this->getUserIdentifier($token, $user);

        return $record;
    }

    /**
     * Extract the user ID from user.
     *
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return string
     */
    private function getUserIdentifier(TokenInterface $token, UserInterface $user): string
    {
        if (\method_exists($user, 'id')) {
            return (string) $user->id();
        }

        if (\method_exists($user, 'getId')) {
            return (string) $user->getId();
        }

        if (\method_exists($user, 'getRecordId')) {
            return (string) $user->getRecordId();
        }

        return $token->getUserIdentifier();
    }
}
