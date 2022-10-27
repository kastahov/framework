<?php

declare(strict_types=1);

namespace Framework\Bootloader\Http;

use Spiral\Auth\Config\AuthConfig;
use Spiral\Auth\Session\TokenStorage;
use Spiral\Auth\Session\TokenStorage as SessionTokenStorage;
use Spiral\Auth\TokenStorageInterface;
use Spiral\Auth\TokenStorageProvider;
use Spiral\Auth\TokenStorageProviderInterface;
use Spiral\Tests\Framework\BaseTest;

final class HttpAuthBootloaderTest extends BaseTest
{
    public function testTokenStorageProviderInterfaceBinding(): void
    {
        $this->assertContainerBoundAsSingleton(TokenStorageProviderInterface::class, TokenStorageProvider::class);
    }

    public function testTokenStorageInterfaceBinding(): void
    {
        $this->assertContainerBoundAsSingleton(TokenStorageInterface::class, TokenStorage::class);
    }

    public function testConfig(): void
    {
        $this->assertConfigHasFragments(AuthConfig::CONFIG, [
            'defaultStorage' => 'session',
            'defaultTransport' => 'cookie',
            'storages' => [
                'session' => SessionTokenStorage::class,
            ],
        ]);
    }
}