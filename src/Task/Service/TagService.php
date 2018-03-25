<?php

namespace Acme\Task\Service;

use Acme\Exception\Tag\NotFoundError as TagNotFoundError;
use Acme\Task\Model\Tag;
use Acme\Task\Presenter\TagPresenter;
use Acme\Task\Presenter\TagsPresenter;
use Acme\Task\Repository\TagRepository;

/**
 * Tag Service
 *
 * @package Acme\Task\Service
 */
class TagService
{
    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * Tag Service constructor
     *
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param int $id
     * @return Tag
     * @throws \ReflectionException
     * @throws TagNotFoundError
     */
    public function getTag(int $id): Tag
    {
        $tag = $this
            ->tagRepository
            ->get($id)
        ;

        if (is_null($tag->getId())) {
            throw new TagNotFoundError(
                sprintf('Tag not found on database: %d', $id)
            );
        }

        return $tag;
    }

    /**
     * @param int $id
     * @return TagPresenter
     * @throws TagNotFoundError
     * @throws \ReflectionException
     */
    public function getTagApi(int $id): TagPresenter
    {
        return new TagPresenter($this->getTag($id));
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getAll(): array
    {
        return $this
            ->tagRepository
            ->getAll()
        ;
    }

    /**
     * @return TagsPresenter
     * @throws \ReflectionException
     */
    public function getAllApi(): TagsPresenter
    {
        $tagsPresenter = new TagsPresenter();

        /** @var Tag $tag */
        foreach ($this->getAll() as $tag) {
            $tagsPresenter->addTag($tag);
        }

        return $tagsPresenter;
    }

    /**
     * @param string $name
     * @param string $textColor
     * @param string $backgroundColor
     * @return Tag
     * @throws \ReflectionException
     */
    public function addTag(
        string $name,
        string $textColor,
        string $backgroundColor
    ): Tag {
        $tag = new Tag();
        $tag->setName($name);
        $tag->setTextColor($textColor);
        $tag->setBackgroundColor($backgroundColor);

        return $this
            ->tagRepository
            ->add($tag)
        ;
    }

    /**
     * @param string $name
     * @param string $textColor
     * @param string $backgroundColor
     * @return TagPresenter
     * @throws \ReflectionException
     */
    public function addTagApi(
        string $name,
        string $textColor,
        string $backgroundColor
    ): TagPresenter {
        return new TagPresenter($this->addTag($name, $textColor, $backgroundColor));
    }

    /**
     * @param int $id
     * @param string|null $name
     * @param string|null $textColor
     * @param string|null $backgroundColor
     * @return Tag
     * @throws TagNotFoundError
     * @throws \ReflectionException
     */
    public function updateTag(
        int $id,
        ?string $name,
        ?string $textColor,
        ?string $backgroundColor
    ): Tag {
        $tag = $this->getTag($id);

        if ($name) {
            $tag->setName($name);
        }

        if ($textColor) {
            $tag->setTextColor($textColor);
        }

        if ($backgroundColor) {
            $tag->setBackgroundColor($backgroundColor);
        }

        return $this
            ->tagRepository
            ->update($tag)
        ;
    }

    /**
     * @param int $id
     * @param string|null $name
     * @param string|null $textColor
     * @param string|null $backgroundColor
     * @return TagPresenter
     * @throws TagNotFoundError
     * @throws \ReflectionException
     */
    public function updateTagApi(
        int $id,
        ?string $name,
        ?string $textColor,
        ?string $backgroundColor
    ): TagPresenter {
        return new TagPresenter(
            $this->updateTag($id, $name, $textColor, $backgroundColor)
        );
    }

    /**
     * @param int $id
     * @return bool
     * @throws TagNotFoundError
     * @throws \ReflectionException
     */
    public function deleteTask(int $id): bool
    {
        $tag = $this->getTag($id);

        return $this
            ->tagRepository
            ->delete($tag)
        ;
    }
}
