<?php

namespace Acme\Task\Middleware;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * ApiRequest Middleware
 *
 * @package Acme\Task\Middleware
 */
class ApiRequestMiddleware
{
    /**
     * @var Application
     */
    private $app;

    /**
     * ApiRequest Middleware constructor
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->registerEvent();
    }

    /**
     * @return void
     */
    public function registerEvent(): void
    {
        $this
            ->app
            ->before(function (Request $request) {
                if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
                    $data = json_decode($request->getContent(), true);
                    $request->request->replace(is_array($data) ? $data : array());
                }
            })
        ;
    }
}
