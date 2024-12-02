<?php

declare(strict_types=1);

namespace DigipolisGent\SyslogBundle\Monolog\Processor;

use Monolog\LogRecord;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Processor that adds an uid to the extra key of a log record.
 */
final readonly class UidProcessor
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    /**
     * Adds the uid to the record's extra key.
     *
     * @param \Monolog\LogRecord $record
     *
     * @return \Monolog\LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $user = $this->tokenStorage->getToken()?->getUser();
        if (!$user) {
            $record->extra['uid'] = '0';
            return $record;
        }

        $record->extra['uid'] = $this->getUserIdentifier($user);
        return $record;
    }

    /**
     * Extract the user ID or identifier from user.
     *
     * Try of one of the id related methods with fallback to the user
     * identifier.
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return string
     */
    private function getUserIdentifier(UserInterface $user): string
    {
        $methods = ['getId', 'recordId', 'id'];
        foreach ($methods as $method) {
            if (\method_exists($user, $method)) {
                return (string) $user->{$method}();
            }
        }

        return $user->getUserIdentifier();
    }
}
