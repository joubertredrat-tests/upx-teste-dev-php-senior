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
     * @var array<Tag>
     */
    private $tags = [];

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

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag): void
    {
        if ($tag->getId()) {
            $this->tags[$tag->getId()] = $tag;
        }
    }

    /**
     * @param Tag $tag
     * @return bool
     */
    public function hasTag(Tag $tag): bool
    {
        return array_key_exists($tag->getId(), $this->tags);
    }

    /**
     * @param Tag $tag
     */
    public function removeTag(Tag $tag): void
    {
        if ($this->hasTag($tag)) {
            unset($this->tags[$tag->getId()]);
        }
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
