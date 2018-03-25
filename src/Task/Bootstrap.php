<?php

namespace Acme\Task;

use Acme\Task\Controller\TaskController;
use Acme\Task\Middleware\ApiRequestMiddleware;
use Acme\Task\Provider\ConfigProvider;
use Acme\Task\Provider\DatabaseProvider;
use Acme\Task\Repository\TaskRepository;
use Acme\Task\Service\TaskService;
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
        $this->registerProviders();
        $this->registerMiddleware();
        $this->dispatchRepository();
        $this->dispatchServices();
        $this->dispatchRoutes();
    }

    /**
     * @return void
     */
    public function enableDebug(): void
    {
        $this->app['debug'] = true;
    }

    /**
     * @return void
     */
    public function registerProviders(): void
    {
        $this
            ->app
            ->register(
                new ConfigProvider()
            )
            ->register(
                new DatabaseProvider()
            )
        ;
    }

    /**
     * @return void
     */
    public function registerMiddleware(): void
    {
        new ApiRequestMiddleware($this->app);
    }

    /**
     * @return void
     */
    public function dispatchRepository(): void
    {
        $app = $this->app;

        $app['repository.task'] = function () use ($app) {
            return new TaskRepository($app['connection.sqlite']);
        };
    }

    /**
     * @return void
     */
    public function dispatchServices(): void
    {
        $app = $this->app;

        $app['service.task'] = function () use ($app) {
            return new TaskService($app['repository.task']);
        };
    }

    /**
     * @return void
     */
    public function dispatchRoutes(): void
    {
        $this
            ->app
            ->mount('/tasks', new TaskController())
        ;
    }
}
