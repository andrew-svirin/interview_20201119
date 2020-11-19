<?php

namespace AndrewSvirin\Interview\Services;

use AndrewSvirin\Interview\Adapters\DBAdapterInterface;

class DBClient
{

    /**
     * Adapter for database.
     * @var DBAdapterInterface
     */
    private $dbAdapter;

    /**
     * Client configurations.
     * @var array = [
     *   'host' => 'string',
     *   'name' => 'string',
     *   'username' => 'string',
     *   'password' => 'string',
     *   'port' => 'integer',
     * ]
     */
    private $config;

    /**
     * Connection to database.
     * @var resource
     */
    private $connection;

    public function __construct(DBAdapterInterface $dbAdapter, array $config)
    {
        $this->dbAdapter = $dbAdapter;
        $this->config = $config;
    }

    /**
     * Open connection to database.
     */
    public function connect(): void
    {
        $this->connection = $this->dbAdapter->connect(
            $this->config['host'],
            $this->config['name'],
            $this->config['username'],
            $this->config['password'],
            $this->config['port']
        );
    }

    /**
     * Close connection to database.
     */
    public function close(): void
    {
        $this->dbAdapter->close($this->connection);
    }

    /**
     * Check that connection exists.
     * @return bool
     */
    public function isConnected(): bool
    {
        return is_resource($this->connection);
    }

    /**
     * Perform query.
     * @param string $query
     * @param array|null $params
     * @param int $outputFormat
     * @return array|mixed
     */
    public function query(
        string $query,
        array $params = null,
        $outputFormat = DBAdapterInterface::OUTPUT_FETCH_ALL_ASSOC
    )
    {
        return $this->dbAdapter->query($this->connection, $query, $params ?? [], $outputFormat);
    }
}
