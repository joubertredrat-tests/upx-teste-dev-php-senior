<?php

namespace Acme\Task;

use Acme\Task\Controller\TasksController;
use Acme\Util\Database\Connection;
use Silex\Application;

/**
 * Bootstrap
 *
 * @package Acme\Task
 */
class Bootstrap
{
    /**
     * @var Application
     */
    private $app;

    /**
     * Bootstrap constructor
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->enableDebug();
        $this->dispatchServices();
        $this->dispatchRoutes();
    }

    public function enableDebug()
    {
        $this->app['debug'] = true;
    }

    public function dispatchServices(): void
    {
        $this->app['connection.sqlite'] = function () {
            return new Connection(
                Connection::DRIVER_SQLITE,
                [
                    'filepath' => '/home/dev/source/other/teste-dev-php-senior/db/task.sqlite',
                ]
            );
        };
    }

    /**
     * @return void
     */
    public function dispatchRoutes(): void
    {
        $this
            ->app
            ->mount('/tasks', new TasksController())
        ;
    }
}