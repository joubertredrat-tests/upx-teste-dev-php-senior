<?php

namespace Acme\Task\Presenter;

use Acme\Task\Model\Task;

/**
 * Task Presenter
 *
 * @package Acme\Task\Presenter
 */
class TaskPresenter
{
    /**
     * @var Task
     */
    private $task;

    /**
     * Task Presenter constructor
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $created = $this->task->getCreated() instanceof \DateTime ?
            $this
                ->task
                ->getCreated()
                ->format('Y-m-d H:i:s')
            :
            null
        ;

        $updated = $this->task->getUpdated() instanceof \DateTime ?
            $this
                ->task
                ->getUpdated()
                ->format('Y-m-d H:i:s')
            :
            null
        ;

        $tags = [];

        foreach ($this->task->getTags() as $tag) {
            $tagPresenter = new TagPresenter($tag);
            $tags[] = $tagPresenter->toArray();
        }

        return [
            'id' => $this->task->getId(),
            'title' => $this->task->getTitle(),
            'description' => $this->task->getDescription(),
            'isDone' => $this->task->isDone(),
            'tags' => $tags,
            'created' => $created,
            'updated' => $updated,
        ];
    }
}
