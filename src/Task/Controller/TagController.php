<?php

namespace Acme\Task\Controller;

use Acme\Exception\Api\InvalidRequestError;
use Acme\Exception\Tag\NotFoundError as TagNotFoundError;
use Acme\Task\Model\Tag;
use Acme\Task\Service\TagService;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tag Controller
 *
 * @package Acme\Task\Controller
 */
class TagController implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app): ControllerCollection
    {
        $factory = $app['controllers_factory'];
        $factory->get('/', 'Acme\Task\Controller\TagController::listAction');
        $factory->post('/', 'Acme\Task\Controller\TagController::createAction');
        $factory
            ->get('/{id}', 'Acme\Task\Controller\TagController::getAction')
            ->assert('id', '\d+')
        ;
        $factory
            ->patch('/{id}', 'Acme\Task\Controller\TagController::updateAction')
            ->assert('id', '\d+')
        ;
        $factory
            ->delete('/{id}', 'Acme\Task\Controller\TagController::deleteAction')
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
            /** @var TagService $service */
            $service = $app['service.tag'];

            $tagsPresenter = $service->getAllApi();
            $response = $tagsPresenter->toArray();
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
            $name = $request->get('name');
            $textColor = $request->get('textColor');
            $backgroundColor = $request->get('backgroundColor');

            $errors = [];

            if (is_null($name)) {
                $errors[] = 'The name field is required';
            }

            if (is_null($textColor)) {
                $errors[] = 'The textColor field is required';
            }

            if (is_null($backgroundColor)) {
                $errors[] = 'The backgroundColor field is required';
            }

            if (!Tag::isHexColor($textColor)) {
                $errors[] = 'The textColor field is not valid hexadecimal color';
            }

            if (!Tag::isHexColor($backgroundColor)) {
                $errors[] = 'The backgroundColor field is not valid hexadecimal color';
            }

            if ($errors) {
                throw new InvalidRequestError(
                    'Invalid request: ' . implode(', ', $errors)
                );
            }

            /** @var TagService $service */
            $service = $app['service.tag'];

            $tagPresenter = $service->addTagApi($name, $textColor, $backgroundColor);
            $response = $tagPresenter->toArray();
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
            /** @var TagService $service */
            $service = $app['service.tag'];

            $tagPresenter = $service->getTagApi($id);
            $response = $tagPresenter->toArray();
            $statusCode = Response::HTTP_OK;
        } catch (TagNotFoundError $e) {
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

            $name = $request->get('name', null);
            $textColor = $request->get('textColor', null);
            $backgroundColor = $request->get('backgroundColor', null);

            if (!is_null($textColor) && !Tag::isHexColor($textColor)) {
                $errors[] = 'The textColor field is not valid hexadecimal color';
            }

            if (!is_null($backgroundColor) && !Tag::isHexColor($backgroundColor)) {
                $errors[] = 'The backgroundColor field is not valid hexadecimal color';
            }

            if ($errors) {
                throw new InvalidRequestError(
                    'Invalid request: ' . implode(', ', $errors)
                );
            }

            /** @var TagService $service */
            $service = $app['service.tag'];

            $tagPresenter = $service->updateTagApi($id, $name, $textColor, $backgroundColor);
            $response = $tagPresenter->toArray();
            $statusCode = Response::HTTP_OK;
        } catch (TagNotFoundError $e) {
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
     * @return JsonResponse
     */
    public function deleteAction(Application $app, int $id): JsonResponse
    {
        try {
            /** @var TagService $service */
            $service = $app['service.tag'];

            $service->deleteTag($id);
            $response = ['message' => 'Tag removed'];
            $statusCode = Response::HTTP_OK;
        } catch (TagNotFoundError $e) {
            $response['message'] = $e->getMessage();
            $statusCode = Response::HTTP_NOT_FOUND;
        } catch (\Throwable $e) {
            $response['message'] = $e->getMessage();
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return new JsonResponse($response, $statusCode);
    }
}
