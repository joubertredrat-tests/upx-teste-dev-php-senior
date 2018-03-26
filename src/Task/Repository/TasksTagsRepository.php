<?php

namespace Acme\Task\Repository;

use Acme\Task\Model\Tag;
use Acme\Task\Model\Task;

/**
 * TasksTags Repository
 *
 * @package Acme\Task\Repository
 */
class TasksTagsRepository extends AbstractRepository
{
    public function getTags(Task $task): array
    {
        $return = [];

        if (!is_null($task->getId())) {
            $pdo = $this
                ->getConnection()
                ->getPdo()
            ;

            $query = "SELECT * FROM tasks_tags WHERE task_id = :id";
            $statement = $pdo->prepare($query);
            $statement->bindParam(
                ":id",
                $task->getId(),
                \PDO::PARAM_INT
            );

            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            $statement->execute();
            $data = $statement->fetchAll();

            foreach ($data as $row) {
                $return[] = $row['tag_id'];
            }
        }

        return $return;
    }

    /**
     * @param Task $task
     * @return bool
     */
    public function linkTags(Task $task): bool
    {
        $pdo = $this
            ->getConnection()
            ->getPdo()
        ;

        /** @var Tag $tag */
        foreach ($task->getTags() as $tag) {
            $query = "INSERT INTO tasks_tags (task_id, tag_id) "
                . "VALUES (:taskId, :tagId)";

            $statement = $pdo->prepare($query);

            $statement->bindParam(
                ":taskId",
                $task->getId(),
                \PDO::PARAM_INT
            );
            $statement->bindParam(
                ":tagId",
                $tag->getId(),
                \PDO::PARAM_INT
            );

            $statement->execute();
        }

        return true;
    }
}
