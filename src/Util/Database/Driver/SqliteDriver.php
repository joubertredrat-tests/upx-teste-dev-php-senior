<?php

namespace Acme\Util\Database\Driver;

use Acme\Exception\Util\Database\Driver\InvalidArgumentsException;
use Acme\Exception\Util\Database\Driver\Sqlite\ConnectionException;
use Acme\Exception\Util\Database\Driver\Sqlite\FileNotFoundException;

/**
 * SqliteDriver
 *
 * @package Acme\Util\Database\Driver
 */
class SqliteDriver implements DriverInterface
{
    /**
     * prefix
     */
    const PREFIX = 'sqlite:';

    /**
     * @var string
     */
    private $filePath;

    /**
     * SqliteDriver constructor
     *
     * @param array $config
     * @throws InvalidArgumentsException
     * @throws FileNotFoundException
     */
    public function __construct(array $config)
    {
        if (!self::isValidConfigArray($config)) {
            throw new InvalidArgumentsException(
                sprintf(
                    'Invalid sqlite driver config params, %s',
                    implode(', ', $config)
                )
            );
        }

        $filePath = $config['filepath'];

        if (!file_exists($filePath)) {
            throw new FileNotFoundException(
                sprintf(
                    'database filepath not found: %s',
                    $filePath
                )
            );
        }

        $this->filePath = $filePath;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return string
     */
    public function getDsn(): string
    {
        return self::PREFIX . $this->getFilePath();
    }

    /**
     * {@inheritdoc}
     */
    public function getConnection(): \PDO
    {
        $pdo = new \PDO($this->getDsn());

        if (!$pdo) {
            throw new ConnectionException(
                sprintf(
                    'Fail to connect on sqlite dsn %s: %s',
                    $this->getDsn(),
                    $pdo->errorInfo()
                )
            );
        }

        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->query("PRAGMA foreign_keys = ON");

        return $pdo;
    }

    /**
     * {@inheritdoc}
     */
    public static function isValidConfigArray(array $config): bool
    {
        return isset($config['filepath']);
    }
}
