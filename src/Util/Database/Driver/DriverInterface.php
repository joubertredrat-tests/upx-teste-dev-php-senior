<?php

namespace Acme\Util\Database\Driver;

/**
 * Driver Interface
 *
 * @package Acme\Util\Database\Driver
 */
interface DriverInterface
{
    /**
     * Returns connection
     *
     * @return \PDO
     */
    public function getConnection(): \PDO;

    /**
     * Validate driver config array data
     *
     * @param array $config
     * @return bool
     */
    public static function isValidConfigArray(array $config): bool;
}
