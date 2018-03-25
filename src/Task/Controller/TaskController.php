<?php

namespace Acme\Task\Controller;

use Acme\Exception\Api\InvalidRequestError;
use Acme\Exception\Task\NotFoundError as TaskNotFoundError;
use Acme\Task\Service\TaskService;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Task Controller
 *
 * @package Acme\Task\Controller
 */
class TaskController implements ControllerProviderInterface
{
    /**
     * @param Application $app
     * @return ControllerCollection
     */
    public function connect(Application $app): ControllerCollection
    {
        $factory = $app['controllers_factory'];
        $factory->get('/', 'Acme\Task\Controller\TaskController::listAction');
        $factory->post('/', 'Acme\Task\Controller\TaskController::createAction');
        $factory
            ->get('/{id}', 'Acme\Task\Controller\TaskController::getAction')
            ->assert('id', '\d+')
        ;
        $factory
            ->patch('/{id}', 'Acme\Task\Controller\TaskController::updateAction')
            ->assert('id', '\d+')
        ;

        return $factory;
    }

    /**
     * @param Application $app
     * @return JsonResponse
     */
    public function listAction(Application $app): JsonResponse
    {
        try {
            /** @var TaskService $service */
            $service = $app['service.task'];

            $tasksPresenter = $service->getAllApi();
            $response = $tasksPresenter->toArray();
            $statusCode = Response::HTTP_OK;
        } catch (\Throwable $e) {
            $response['message'] = $e->getMessage();
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return new JsonResponse($response, $statusCode);
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Application $app, Request $request): JsonResponse
    {
        try {
            $title = $request->get('title', null);
            $description = $request->get('description', null);

            $errors = [];

            if (is_null($title)) {
                $errors[] = 'The title field is required';
            }

            if (strlen($title) < 3) {
                $errors[] = 'The title field must have 3 or more characters';
            }

            if ($errors) {
                throw new InvalidRequestError(
                    'Invalid request: ' . implode(', ', $errors)
                );
            }

            /** @var TaskService $service */
            $service = $app['service.task'];

            $taskPresenter = $service->addTaskApi($title, $description);
            $response = $taskPresenter->toArray();
            $statusCode = Response::HTTP_CREATED;
        } catch (InvalidRequestError $e) {
            $response['message'] = $e->getMessage();
            $statusCode = Response::HTTP_BAD_REQUEST;
        } catch (\Throwable $e) {
            $response['message'] = $e->getMessage();
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return new JsonResponse($response, $statusCode);
    }

    /**
     * @param Application $app
     * @param int $id
     * @return JsonResponse
     */
    public function getAction(Application $app, int $id): JsonResponse
    {
        try {
            /** @var TaskService $service */
            $service = $app['service.task'];

            $taskPresenter = $service->getTaskApi($id);
            $response = $taskPresenter->toArray();
            $statusCode = Response::HTTP_OK;
        } catch (TaskNotFoundError $e) {
            $response['message'] = $e->getMessage();
            $statusCode = Response::HTTP_NOT_FOUND;
        } catch (\Throwable $e) {
            $response['message'] = $e->getMessage();
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return new JsonResponse($response, $statusCode);
    }

    /**
     * @param Application $app
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAction(Application $app, int $id, Request $request): JsonResponse
    {
        try {
            $errors = [];

            $title = $request->get('title', null);
            $description = $request->get('description', null);
            $isDone = $request->get('isDone', null);

            if (!is_null($title) && strlen($title) < 3) {
                $errors[] = 'The title field must have 3 or more characters';
            }

            if (!is_null($isDone) && !is_bool($isDone)) {
                $errors[] = 'The isDone field must be boolean';
            }

            if ($errors) {
                throw new InvalidRequestError(
                    'Invalid request: ' . implode(', ', $errors)
                );
            }

            /** @var TaskService $service */
            $service = $app['service.task'];

            $taskPresenter = $service->updateTaskApi($id, $title, $description, $isDone);
            $response = $taskPresenter->toArray();
            $statusCode = Response::HTTP_OK;
        } catch (TaskNotFoundError $e) {
            $response['message'] = $e->getMessage();
            $statusCode = Response::HTTP_NOT_FOUND;
        } catch (\Throwable $e) {
            $response['message'] = $e->getMessage();
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return new JsonResponse($response, $statusCode);
    }
}
