<?php

namespace Acme\Task\Presenter;

use Acme\Task\Model\Tag;

/**
 * Tags Presenter
 *
 * @package Acme\Task\Presenter
 */
class TagsPresenter
{
    /**
     * @var array<Tag>
     */
    private $tags;

    /**
     * @param Tag $tag
     * @return void
     */
    public function addTag(Tag $tag): void
    {
        if (!is_null($tag->getId())) {
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
     * @return void
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

    /**
     * @return array
     */
    public function toArray(): array
    {
        $return = [];

        /** @var Tag $tag */
        foreach ($this->getTags() as $tag) {
            $tagPresenter = new TagPresenter($tag);
            $return[] = $tagPresenter->toArray();
        }

        return $return;
    }
}
