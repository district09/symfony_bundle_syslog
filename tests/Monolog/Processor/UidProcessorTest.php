<?php

declare(strict_types=1);

namespace DigipolisGent\SyslogBundle\Tests\Monolog\Processor;

use DigipolisGent\SyslogBundle\Monolog\Processor\UidProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @covers \DigipolisGent\SyslogBundle\Monolog\Processor\UidProcessor
 *
 * @group DigipolisGentSyslogBundle
 */
final class UidProcessorTest extends TestCase
{
    /**
     * 0 is used when the token storage has no token.
     *
     * @test
     */
    public function itUsesZeroWhenThereIsNoToken(): void
    {
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->once())->method('getToken')->willReturn(null);

        $id = uniqid('', true);
        $processor = new UidProcessor($tokenStorage);
        $record = $processor(['id' => $id]);

        self::assertEquals($id, $record['id']);
        self::assertEquals(0, $record['extra']['uid']);
    }

    /**
     * 0 is used when there is no user within the token.
     *
     * @test
     */
    public function itUsesZeroWhenThereIsNoUser(): void
    {
        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn(null);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->once())->method('getToken')->willReturn($token);

        $id = uniqid('', true);
        $processor = new UidProcessor($tokenStorage);
        $record = $processor(['id' => $id]);

        self::assertEquals($id, $record['id']);
        self::assertEquals(0, $record['extra']['uid']);
    }

    /**
     * The id() method is used when user object has that method.
     *
     * @test
     */
    public function itUsesIdMethodWhenAvailable(): void
    {
        $user = new class implements UserInterface {
            public function id(): int { return 123; }
            public function getUserIdentifier(): string { return 'foo'; }
            public function getRoles(): array { return []; }
            public function eraseCredentials(): void { }
        };

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->once())->method('getToken')->willReturn($token);

        $id = uniqid('', true);
        $processor = new UidProcessor($tokenStorage);
        $record = $processor(['id' => $id]);

        self::assertEquals($id, $record['id']);
        self::assertEquals('123', $record['extra']['uid']);
    }

    /**
     * The getId() method is used when user object has that method.
     *
     * @test
     */
    public function itUsesGetIdMethodWhenAvailable(): void
    {
        $user = new class implements UserInterface {
            public function getId(): int { return 456; }
            public function getUserIdentifier(): string { return 'foo'; }
            public function getRoles(): array { return []; }
            public function eraseCredentials(): void { }
        };

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->once())->method('getToken')->willReturn($token);

        $id = uniqid('', true);
        $processor = new UidProcessor($tokenStorage);
        $record = $processor(['id' => $id]);

        self::assertEquals($id, $record['id']);
        self::assertEquals('456', $record['extra']['uid']);
    }

    /**
     * The getRecordId() method is used when user object has that method.
     *
     * @test
     */
    public function itUsesRecordIdMethodWhenAvailable(): void
    {
        $user = new class implements UserInterface {
            public function recordId(): int { return 789; }
            public function getUserIdentifier(): string { return 'foo'; }
            public function getRoles(): array { return []; }
            public function eraseCredentials(): void { }
        };

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->once())->method('getToken')->willReturn($token);

        $id = uniqid('', true);
        $processor = new UidProcessor($tokenStorage);
        $record = $processor(['id' => $id]);

        self::assertEquals($id, $record['id']);
        self::assertEquals('789', $record['extra']['uid']);
    }

    /**
     * The getUserIdentifier() method is used when no ID methods found.
     *
     * @test
     */
    public function itUsesGetUserIdentifierWhenNoIdMethodsFound(): void
    {
        $user = new class implements UserInterface {
            public function getUserIdentifier(): string { return 'foo'; }
            public function getRoles(): array { return []; }
            public function eraseCredentials(): void { }
        };

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUser')->willReturn($user);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->once())->method('getToken')->willReturn($token);

        $id = uniqid('', true);
        $processor = new UidProcessor($tokenStorage);
        $record = $processor(['id' => $id]);

        self::assertEquals($id, $record['id']);
        self::assertEquals('foo', $record['extra']['uid']);
    }
}
