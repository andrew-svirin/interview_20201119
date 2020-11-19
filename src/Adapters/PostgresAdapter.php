<?php

namespace AndrewSvirin\Interview\Adapters;

use InvalidArgumentException;
use RuntimeException;

class PostgresAdapter implements DBAdapterInterface
{

    /**
     * @inheritDoc
     */
    public function connect(string $host, string $name, string $username, string $password, string $port)
    {
        $connectionString = sprintf(
            'host=%s port=%s dbname=%s user=%s password=%s',
            $host,
            $port,
            $name,
            $username,
            $password
        );
        $connection = pg_connect($connectionString);

        if (!$connection) {
            throw new RuntimeException(pg_last_error());
        }

        return $connection;
    }

    /**
     * @inheritDoc
     */
    public function close($connection): bool
    {
        return pg_close($connection);
    }

    /**
     * @inheritDoc
     */
    public function query(
        $connection,
        string $query,
        array $params = null,
        $outputFormat = self::OUTPUT_FETCH_ALL_ASSOC
    )
    {
        $queryResult = pg_query_params($connection, $query, $params ?? null);
        if (!$queryResult) {
            throw new RuntimeException(pg_last_error($connection));
        }

        $outputResult = $this->output($queryResult, $outputFormat);

        return $outputResult;
    }

    /**
     * Format query result.
     * @param resource $queryResult
     * @param int $format
     * @return array|mixed
     */
    private function output($queryResult, int $format)
    {
        switch ($format) {
            case self::OUTPUT_FETCH_ALL_ASSOC:
                $outputResult = pg_fetch_all($queryResult, PGSQL_ASSOC);
                break;
            default:
                throw new InvalidArgumentException(sprintf('Fetch format `%d` not supported.', $format));
        }

        return $outputResult;
    }
}
