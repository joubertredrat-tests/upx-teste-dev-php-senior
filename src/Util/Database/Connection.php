<?php

namespace Acme\Util\Database;

use Acme\Exception\Util\Database\InvalidDriverException;
use Acme\Util\Database\Driver\SqliteDriver;

/**
 * Connection
 *
 * @package Acme\Util\Database
 */
class Connection
{
    /**
     * Drivers
     */
    const DRIVER_SQLITE = 'sqlite';
    const DRIVER_MYSQL = 'mysql';

    /**
     * @var string
     */
    private $driver;

    /**
     * @var array
     */
    private $config;

    /**
     * Connection constructor
     *
     * @param string $driver
     * @param array $config
     */
    public function __construct(string $driver, array $config)
    {
        if (!self::isValidDriver($driver)) {
            throw new InvalidDriverException(
                sprintf('Invalid connection driver: %s', $driver)
            );
        }

        $this->driver = $driver;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return \PDO
     */
    public function getPdo(): \PDO
    {
        switch ($this->getDriver()) {
            case self::DRIVER_SQLITE:
                return $this->getSqlite();
                break;
            case self::DRIVER_MYSQL:
                throw new InvalidDriverException(
                    sprintf(
                        'Connection driver not activated: %s',
                        $this->getDriver()
                    )
                );
                break;
        }
    }

    /**
     * @return \PDO
     */
    private function getSqlite(): \PDO
    {
        $driver = new SqliteDriver($this->config);

        return $driver->getConnection();
    }

    /**
     * @return array
     */
    public static function getDriversAvailable(): array
    {
        return [
            self::DRIVER_SQLITE,
            self::DRIVER_MYSQL,
        ];
    }

    /**
     * @param string $driver
     * @return bool
     */
    public static function isValidDriver(string $driver): bool
    {
        return in_array($driver, self::getDriversAvailable());
    }
}
