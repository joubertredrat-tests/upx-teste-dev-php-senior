<?php

namespace Acme\Task\Model;

/**
 * Task
 *
 * @package Acme\Task\Model
 */
class Task
{
    use DateTimeTrait;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var bool
     */
    private $isDone;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return bool|null
     */
    public function isDone(): ?bool
    {
        return $this->isDone;
    }

    /**
     * @param bool $isDone
     * @return void
     */
    public function setIsDone(bool $isDone): void
    {
        $this->isDone = $isDone;
    }
}
