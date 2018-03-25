<?php

namespace Acme\Task\Controller;

use Acme\Exception\Task\NotFoundError as TaskNotFoundError;
use Acme\Task\Model\Task;
use Acme\Task\Service\TaskService;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Acme\Util\Database;
use Symfony\Component\HttpFoundation\Response;

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

        return $factory;
    }

    public function listAction()
    {
        $conn = Database::getConnection();
        $results = $conn->query('SELECT * FROM tasks');
        $response = array(
            'tasks' => [],
        );

        foreach ($results as $t) {
            $response['tasks'][] = array(
                'id' => $t['id'],
                'title' => $t['description'],
            );
        }

        return new JsonResponse($response);
    }

    public function createAction()
    {
        $raw_data = file_get_contents("php://input");

        $data = json_decode($raw_data, TRUE);

        $title = isset($data['title']) ? $data['title'] : NULL;

        if (strlen($title) < 3) {
            return new JsonResponse([
                'message' => 'The title field must have 3 or more characters'
            ], 422);
        } else {
            $task = new Task();
            $task->setDescription($title);

            $conn = Database::getConnection();
            $sql = "INSERT INTO tasks (description) VdALUES (:title)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->execute();

            $task->setId($conn->lastInsertId());

            return new JsonResponse([
                'id' => $task->getId(),
                'title' => $task->getDescription(),
            ], 201);
        }
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
}
