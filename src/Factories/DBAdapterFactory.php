<?php

namespace AndrewSvirin\Interview\Factories;

use AndrewSvirin\Interview\Adapters\DBAdapterInterface;
use AndrewSvirin\Interview\Adapters\PostgresAdapter;
use InvalidArgumentException;

/**
 * Class DBAdapterFactory
 * Factory for @see \AndrewSvirin\Interview\Adapters\DBAdapterInterface
 */
class DBAdapterFactory
{

    /**
     * Produce DB adapter.
     * @param string $driver
     * @return DBAdapterInterface
     */
    public static function produce(string $driver): DBAdapterInterface
    {
        switch ($driver) {
            case 'pgsql':
                $dbAdapter = new PostgresAdapter();
                break;
            default:
                throw new InvalidArgumentException(sprintf('Database driver `%s` is not supported.', $driver));
        }

        return $dbAdapter;
    }
}
