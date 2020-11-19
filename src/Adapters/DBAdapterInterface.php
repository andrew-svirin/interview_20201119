<?php

namespace AndrewSvirin\Interview\Adapters;

/**
 * Interface DBAdapterInterface
 * Implements database usage interface.
 */
interface DBAdapterInterface
{

    /**
     * Output fetch formats.
     */
    const OUTPUT_FETCH_ALL_ASSOC = 2;

    /**
     * Establish connection to database.
     * @param string $host
     * @param string $name
     * @param string $username
     * @param string $password
     * @param string $port
     *
     * @return resource
     */
    public function connect(string $host, string $name, string $username, string $password, string $port);

    /**
     * Close connection.
     * @param resource $connection
     * @return bool
     */
    public function close($connection): bool;

    /**
     * Perform query.
     * @param resource $connection
     * @param string $query
     * @param array|null $params
     * @param int $outputFormat
     * @return array|mixed
     */
    public function query(
        $connection,
        string $query,
        array $params = null,
        $outputFormat = DBAdapterInterface::OUTPUT_FETCH_ALL_ASSOC
    );
}
