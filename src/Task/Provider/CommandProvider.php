<?php

namespace Acme\Task\Provider;

use Acme\Task\Command\Database\DatabaseCreateCommand;
use Acme\Task\Command\Database\DatabaseDropCommand;
use Acme\Task\Command\Database\SchemaCreateCommand;
use Acme\Task\Command\Database\SchemaDropCommand;
use Knp\Console\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Command Provider
 *
 * @package Acme\Task\Provider
 */
class CommandProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        if (isset($pimple['console'])) {
            $pimple->extend('console', function (Application $console) {
                $console->add(
                    new DatabaseCreateCommand()
                );
                $console->add(
                    new DatabaseDropCommand()
                );
                $console->add(
                    new SchemaCreateCommand()
                );
                $console->add(
                    new SchemaDropCommand()
                );

                return $console;
            });
        }
    }
}
