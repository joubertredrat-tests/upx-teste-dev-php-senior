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

        $query = "SELECT * FROM task WHERE id = :id";
        $statement = $pdo->prepare($query);
        $statement->bindParam(":id", $id, \PDO::PARAM_INT);

        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->execute();
        $data = $statement->fetchAll();

        if (count($data) == 1) {
            $this->bindDataModel($task, $data[0]);
        }

        return $task;
    }

    /**
     * @return array<Task>
     * @throws \ReflectionException
     */
    public function getAll(): array
    {
        $return = [];

        $pdo = $this
            ->getConnection()
            ->getPdo()
        ;

        $query = "SELECT * FROM task";
        $statement = $pdo->prepare($query);

        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->execute();
        $data = $statement->fetchAll();

        if (count($data) > 0) {
            foreach ($data as $row) {
                $task = new Task();
                $this->bindDataModel($task, $row);
                $return[] = $task;
            }
        }

        return $return;
    }

    /**
     * @param Task $task
     * @return Task
     * @throws \ReflectionException
     */
    public function add(Task $task): Task
    {
        if (is_null($task->getId())) {
            $pdo = $this
                ->getConnection()
                ->getPdo()
            ;

            $task->setCreated(
                new \DateTime('now')
            );

            $query = "INSERT INTO task (title, description, is_done, created) "
                . "VALUES (:title, :description, :isDone, :created)";

            $statement = $pdo->prepare($query);
            $statement->bindParam(
                ":title",
                $task->getTitle(),
                \PDO::PARAM_STR
            );
            $statement->bindParam(
                ":description",
                $task->getDescription(),
                \PDO::PARAM_STR
            );
            $statement->bindParam(
                ":isDone",
                $task->isDone(),
                \PDO::PARAM_BOOL
            );
            $statement->bindParam(
                ":created",
                $task->getCreated()->format('Y-m-d H:i:s'),
                \PDO::PARAM_STR
            );

            $statement->execute();

            $reflection = new \ReflectionProperty(Task::class, 'id');
            $reflection->setAccessible(true);
            $reflection->setValue($task, $pdo->lastInsertId());
            $reflection->setAccessible(false);
        }

        return $task;
    }

    /**
     * @param Task $task
     * @return Task
     */
    public function update(Task $task): Task
    {
        if (!is_null($task->getId())) {
            $pdo = $this
                ->getConnection()
                ->getPdo()
            ;

            $task->setUpdated(
                new \DateTime('now')
            );

            $query = "UPDATE task SET title = :title, description = :description, "
                . "is_done = :isDone, updated = :updated WHERE id = :id";

            $statement = $pdo->prepare($query);
            $statement->bindParam(
                ":title",
                $task->getTitle(),
                \PDO::PARAM_STR
            );
            $statement->bindParam(
                ":description",
                $task->getDescription(),
                \PDO::PARAM_STR
            );
            $statement->bindParam(
                ":isDone",
                $task->isDone(),
                \PDO::PARAM_BOOL
            );
            $statement->bindParam(
                ":updated",
                $task->getUpdated()->format('Y-m-d H:i:s'),
                \PDO::PARAM_STR
            );
            $statement->bindParam(
                ":id",
                $task->getId(),
                \PDO::PARAM_INT
            );

            $statement->execute();
        }

        return $task;
    }

    /**
     * @param Task $task
     * @return bool
     */
    public function delete(Task $task): bool
    {
        if (!is_null($task->getId())) {
            $pdo = $this
                ->getConnection()
                ->getPdo()
            ;

            $query = "DELETE FROM task WHERE id = :id";
            $statement = $pdo->prepare($query);
            $statement->bindParam(
                ":id",
                $task->getId(),
                \PDO::PARAM_INT
            );

            $statement->execute();
        }

        return true;
    }

    /**
     * @param Task $task
     * @param array $data
     * @throws \ReflectionException
     */
    private function bindDataModel(Task $task, array $data): void
    {
        $reflection = new \ReflectionProperty(Task::class, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($task, $data['id']);
        $reflection->setAccessible(false);

        $task->setTitle($data['title']);
        $task->setDescription($data['description']);
        $task->setIsDone((bool) $data['is_done']);
        $task->setCreated(
            new \DateTime($data['created'])
        );

        if ($data['updated']) {
            $task->setUpdated(
                new \DateTime($data['updated'])
            );
        }
    }
}
