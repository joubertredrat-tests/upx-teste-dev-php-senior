<?php

namespace Acme\Task\Service;

use Acme\Exception\Task\NotFoundError as TaskNotFoundError;
use Acme\Task\Model\Task;
use Acme\Task\Presenter\TaskPresenter;
use Acme\Task\Presenter\TasksPresenter;
use Acme\Task\Repository\TaskRepository;

/**
 * Task Service
 *
 * @package Acme\Task\Service
 */
class TaskService
{
    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * Task Service constructor
     *
     * @param TaskRepository $taskRepository
     */
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param int $id
     * @return Task
     * @throws \ReflectionException
     * @throws TaskNotFoundError
     */
    public function getTask(int $id): Task
    {
        $task = $this
            ->taskRepository
            ->get($id)
        ;

        if (is_null($task->getId())) {
            throw new TaskNotFoundError(
                sprintf('Task not found on database: %d', $id)
            );
        }

        return $task;
    }

    /**
     * @param int $id
     * @return TaskPresenter
     * @throws TaskNotFoundError
     * @throws \ReflectionException
     */
    public function getTaskApi(int $id): TaskPresenter
    {
        return new TaskPresenter($this->getTask($id));
    }

    /**
     * @return array<Task>
     * @throws \ReflectionException
     */
    public function getAll(): array
    {
        return $this
            ->taskRepository
            ->getAll()
        ;
    }

    /**
     * @return TasksPresenter
     * @throws \ReflectionException
     */
    public function getAllApi(): TasksPresenter
    {
        $tasksPresenter = new TasksPresenter();

        /** @var Task $task */
        foreach ($this->getAll() as $task) {
            $tasksPresenter->addTask($task);
        }

        return $tasksPresenter;
    }

    /**
     * @param string $title
     * @param string|null $description
     * @return Task
     * @throws \ReflectionException
     */
    public function addTask(string $title, ?string $description): Task
    {
        $task = new Task();
        $task->setTitle($title);
        $task->setDescription($description);
        $task->setIsDone(false);

        return $this
            ->taskRepository
            ->add($task)
        ;
    }

    /**
     * @param string $title
     * @param string|null $description
     * @return TaskPresenter
     * @throws \ReflectionException
     */
    public function addTaskApi(string $title, ?string $description): TaskPresenter
    {
        return new TaskPresenter($this->addTask($title, $description));
    }
}
