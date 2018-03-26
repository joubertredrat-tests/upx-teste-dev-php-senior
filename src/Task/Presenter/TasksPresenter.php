<?php

namespace Acme\Task\Presenter;

use Acme\Task\Model\Task;

/**
 * Tasks Presenter
 *
 * @package Acme\Task\Presenter
 */
class TasksPresenter
{
    /**
     * @var array<Task>
     */
    private $tasks = [];

    /**
     * @param Task $task
     * @return void
     */
    public function addTask(Task $task): void
    {
        if (!is_null($task->getId())) {
            $this->tasks[$task->getId()] = $task;
        }
    }

    /**
     * @param Task $task
     * @return bool
     */
    public function hasTask(Task $task): bool
    {
        return array_key_exists($task->getId(), $this->tasks);
    }

    /**
     * @param Task $task
     * @return void
     */
    public function removeTask(Task $task): void
    {
        if ($this->hasTask($task)) {
            unset($this->tasks[$task->getId()]);
        }
    }

    /**
     * @return array<Task>
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $return = [];

        /** @var Task $task */
        foreach ($this->getTasks() as $task) {
            $taskPresenter = new TaskPresenter($task);
            $return[] = $taskPresenter->toArray();
        }

        return $return;
    }
}
