<?php

namespace Acme\Task\Repository;

use Acme\Util\Database\Connection;

/**
 * Abstract Repository
 *
 * @package Acme\Task\Repository
 */
abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * Abstract Repository constructor
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }
}
