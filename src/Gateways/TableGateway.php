<?php

namespace AndrewSvirin\Interview\Gateways;

use AndrewSvirin\Interview\Services\DBClient;

/**
 * Class TableGateway
 * Common operations with database tables.
 */
abstract class TableGateway
{

    /**
     * @var DBClient
     */
    protected $dbClient;

    public function __construct(DBClient $dbClient)
    {
        $this->dbClient = $dbClient;
    }
}
