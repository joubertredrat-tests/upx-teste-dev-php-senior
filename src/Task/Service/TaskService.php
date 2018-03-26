<?php

namespace Acme\Task\Service;

use Acme\Exception\Task\NotFoundError as TaskNotFoundError;
use Acme\Task\Model\Task;
use Acme\Task\Presenter\TaskPresenter;
use Acme\Task\Presenter\TasksPresenter;
use Acme\Task\Repository\TaskRepository;
use Acme\Task\Repository\TasksTagsRepository;

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
     * @var TasksTagsRepository
     */
    private $tasksTagsRepository;

    /**
     * @var TagService
     */
    private $tagService;

    /**
     * Task Service constructor
     *
     * @param TaskRepository $taskRepository
     * @param TasksTagsRepository $tasksTagsRepository
     * @param TagService $tagService
     */
    public function __construct(
        TaskRepository $taskRepository,
        TasksTagsRepository $tasksTagsRepository,
        TagService $tagService
    ) {
        $this->taskRepository = $taskRepository;
        $this->tasksTagsRepository = $tasksTagsRepository;
        $this->tagService = $tagService;
    }

    /**
     * @param int $id
     * @return Task
     * @throws \ReflectionException
     * @throws TaskNotFoundError
     * @throws \Acme\Exception\Tag\NotFoundError
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

        $tags = $this->tasksTagsRepository->getTags($task);

        foreach ($tags as $tagId) {
            $tag = $this
                ->tagService
                ->getTag($tagId)
            ;

            $task->addTag($tag);
        }

        return $task;
    }

    /**
     * @param int $id
     * @return TaskPresenter
     * @throws TaskNotFoundError
     * @throws \ReflectionException
     * @throws \Acme\Exception\Tag\NotFoundError
     */
    public function getTaskApi(int $id): TaskPresenter
    {
        return new TaskPresenter($this->getTask($id));
    }

    /**
     * @return array<Task>
     * @throws \ReflectionException
     * @throws \Acme\Exception\Tag\NotFoundError
     */
    public function getAll(): array
    {
        $return = [];

        $tasks = $this
            ->taskRepository
            ->getAll()
        ;

        /** @var Task $task */
        foreach ($tasks as $task) {
            $tags = $this->tasksTagsRepository->getTags($task);

            foreach ($tags as $tagId) {
                $tag = $this
                    ->tagService
                    ->getTag($tagId)
                ;

                $task->addTag($tag);
            }

            $return[] = $task;
        }

        return $return;
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
     * @param array $tags
     * @return Task
     * @throws \ReflectionException
     * @throws \Acme\Exception\Tag\NotFoundError
     */
    public function addTask(string $title, ?string $description, array $tags): Task
    {
        $task = new Task();
        $task->setTitle($title);
        $task->setDescription($description);
        $task->setIsDone(false);

        foreach ($tags as $tagId) {
            $tag = $this->tagService->getTag($tagId);
            $task->addTag($tag);
        }

        $task = $this
            ->taskRepository
            ->add($task)
        ;

        $this
            ->tasksTagsRepository
            ->linkTags($task)
        ;

        return $task;
    }

    /**
     * @param string $title
     * @param string|null $description
     * @param array $tags
     * @return TaskPresenter
     * @throws \ReflectionException
     * @throws \Acme\Exception\Tag\NotFoundError
     */
    public function addTaskApi(string $title, ?string $description, array $tags): TaskPresenter
    {
        return new TaskPresenter(
            $this->addTask($title, $description, $tags)
        );
    }

    /**
     * @param int $id
     * @param string|null $title
     * @param string|null $description
     * @param bool|null $isDone
     * @param array $tags
     * @return Task
     * @throws TaskNotFoundError
     * @throws \ReflectionException
     * @throws \Acme\Exception\Tag\NotFoundError
     */
    public function updateTask(
        int $id,
        ?string $title,
        ?string $description,
        ?bool $isDone,
        array $tags
    ): Task {
        $task = $this->getTask($id);

        if ($title) {
            $task->setTitle($title);
        }

        if ($description) {
            $task->setDescription($description);
        }

        if ($isDone) {
            $task->setIsDone($isDone);
        }

        $task = $this
            ->taskRepository
            ->update($task)
        ;

        if ($tags) {
            foreach ($tags as $tagId) {
                $tag = $this->tagService->getTag($tagId);
                $task->addTag($tag);
            }

            $this
                ->tasksTagsRepository
                ->unlinkTagsByTask($task)
            ;

            $this
                ->tasksTagsRepository
                ->linkTags($task)
            ;
        }

        return $task;
    }

    /**
     * @param int $id
     * @param string|null $title
     * @param string|null $description
     * @param bool|null $isDone
     * @param array $tags
     * @return TaskPresenter
     * @throws TaskNotFoundError
     * @throws \ReflectionException
     * @throws \Acme\Exception\Tag\NotFoundError
     */
    public function updateTaskApi(
        int $id,
        ?string $title,
        ?string $description,
        ?bool $isDone,
        array $tags
    ): TaskPresenter {
        return new TaskPresenter(
            $this->updateTask($id, $title, $description, $isDone, $tags)
        );
    }

    /**
     * @param int $id
     * @return bool
     * @throws TaskNotFoundError
     * @throws \ReflectionException
     */
    public function deleteTask(int $id): bool
    {
        $task = $this->getTask($id);

        return $this
            ->taskRepository
            ->delete($task)
        ;
    }
}
