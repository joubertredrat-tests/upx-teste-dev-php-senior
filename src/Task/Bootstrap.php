<?php

namespace Acme\Task;

use Acme\Task\Controller\TagController;
use Acme\Task\Controller\TaskController;
use Acme\Task\Middleware\ApiRequestMiddleware;
use Acme\Task\Provider\CommandProvider;
use Acme\Task\Provider\ConfigProvider;
use Acme\Task\Provider\DatabaseProvider;
use Acme\Task\Repository\TagRepository;
use Acme\Task\Repository\TaskRepository;
use Acme\Task\Repository\TasksTagsRepository;
use Acme\Task\Service\TagService;
use Acme\Task\Service\TaskService;
use Knp\Provider\ConsoleServiceProvider;
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
            ->register(
                new ConsoleServiceProvider()
            )
            ->register(
                new CommandProvider()
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

        $app['repository.tag'] = function () use ($app) {
            return new TagRepository($app['connection.sqlite']);
        };

        $app['repository.tasks_tags'] = function () use ($app) {
            return new TasksTagsRepository($app['connection.sqlite']);
        };
    }

    /**
     * @return void
     */
    public function dispatchServices(): void
    {
        $app = $this->app;

        $app['service.tag'] = function () use ($app) {
            return new TagService($app['repository.tag']);
        };

        $app['service.task'] = function () use ($app) {
            return new TaskService(
                $app['repository.task'],
                $app['repository.tasks_tags'],
                $app['service.tag']
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
            ->mount('/tasks', new TaskController())
            ->mount('/tags', new TagController())
        ;
    }
}
