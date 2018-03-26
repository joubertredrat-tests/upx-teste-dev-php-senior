<?php

namespace Acme\Task\Provider;

use Acme\Util\Database\Connection;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Database Provider
 *
 * @package Acme\Task\Provider
 */
class DatabaseProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $filePath = $pimple['dbDir'] . $pimple['databaseConfig']['sqlite']['file'];

        $pimple['connection.sqlite'] = function () use ($filePath) {
            return new Connection(
                Connection::DRIVER_SQLITE,
                [
                    'filepath' => $filePath,
                ]
            );
        };
    }
}
