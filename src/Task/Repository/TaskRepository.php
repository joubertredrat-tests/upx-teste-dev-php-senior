<?php

namespace Acme\Task\Repository;

use Acme\Task\Model\Task;

/**
 * Task Repository
 *
 * @package Acme\Task\Repository
 */
class TaskRepository extends AbstractRepository
{
    /**
     * @param int $id
     * @return Task
     * @throws \ReflectionException
     */
    public function get(int $id): Task
    {
        $task = new Task();

        $pdo = $this
            ->getConnection()
            ->getPdo()
        ;

        $query = "SELECT * FROM task WHERE id = :id;";
        $statement = $pdo->prepare($query);
        $statement->bindParam(":id", $id, \PDO::PARAM_INT);

        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->execute();
        $data = $statement->fetchAll();

        if (count($data) == 1) {
            $reflection = new \ReflectionProperty(Task::class, 'id');
            $reflection->setAccessible(true);
            $reflection->setValue($task, $data[0]['id']);
            $reflection->setAccessible(false);

            $task->setTitle($data[0]['title']);
            $task->setDescription($data[0]['description']);
            $task->setIsDone((bool) $data[0]['isDone']);
            $task->setCreated(
                new \DateTime($data[0]['created'])
            );

            if ($data[0]['updated']) {
                $task->setUpdated(
                    new \DateTime($data[0]['updated'])
                );
            }
        }

        return $task;
    }
}
