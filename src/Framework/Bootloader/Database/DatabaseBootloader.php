<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Bootloader\Database;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Core\Container\SingletonInterface;
use Cycle\Database\Database;
use Cycle\Database\DatabaseInterface;
use Cycle\Database\DatabaseManager;
use Cycle\Database\DatabaseProviderInterface;

final class DatabaseBootloader extends Bootloader implements SingletonInterface
{
    protected const SINGLETONS = [
        DatabaseProviderInterface::class => DatabaseManager::class,
    ];

    protected const BINDINGS = [
        DatabaseInterface::class => Database::class,
    ];

    /** @var ConfiguratorInterface */
    private $config;

    /**
     * @param ConfiguratorInterface $config
     */
    public function __construct(ConfiguratorInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Init database config.
     */
    public function boot(): void
    {
        $this->config->setDefaults(
            'database',
            [
                'default'   => 'default',
                'aliases'   => [],
                'databases' => [],
                'drivers'   => [],
            ]
        );
    }
}
