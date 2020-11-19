<?php

namespace AndrewSvirin\Interview\Tests;

use Dotenv\Dotenv;
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
}
