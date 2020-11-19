<?php

namespace AndrewSvirin\Interview\Tests;

use AndrewSvirin\Interview\Factories\DBAdapterFactory;
use AndrewSvirin\Interview\Services\DBClient;
use Dotenv\Dotenv;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    /**
     * Configuration array.
     * @var array
     */
    protected $config;

    /**
     * Setup environment.
     */
    private function setUpEnvironment(): void
    {
        // Create in unsafe mode to access by function `getenv`.
        $dotenv = Dotenv::createUnsafeImmutable(BASE_DIR);
        $dotenv->load();
        $dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USERNAME', 'DB_PASSWORD']);
    }

    /**
     * Setup config.
     */
    private function setUpConfig(): void
    {
        $this->config = include(BASE_DIR . '/resources/config/config.php');
    }

    /**
     * @inheritDoc
     * Setup environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpEnvironment();
        $this->setUpConfig();
    }

    /**
     * Use default config or pass custom config for create client connection.
     * @param array|null $config
     * @return DBClient
     */
    protected function client(array $config = null)
    {
        if (null === $config) {
            $config = $this->config['database'];
        }
        if (!$config) {
            throw new InvalidArgumentException('Configuration incorrect. Config has not section `database`.');
        }

        $dbAdapter = DBAdapterFactory::produce($config['driver'] ?? null);

        return new DBClient($dbAdapter, [
            'host' => $config['host'],
            'name' => $config['name'],
            'username' => $config['username'],
            'password' => $config['password'],
            'port' => $config['port'],
        ]);
    }
}
