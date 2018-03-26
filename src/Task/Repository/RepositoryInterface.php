<?php

namespace Acme\Task\Repository;

use Acme\Util\Database\Connection;

/**
 * Repository Interface
 *
 * @package Acme\Task\Repository
 */
interface RepositoryInterface
{
    /**
     * @return Connection
     */
    public function getConnection(): Connection;
}
