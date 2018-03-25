<?php

namespace Acme\Task\Presenter;

use Acme\Task\Model\Tag;

/**
 * Tag Presenter
 *
 * @package Acme\Task\Presenter
 */
class TagPresenter
{
    /**
     * @var Tag
     */
    private $tag;

    /**
     * Tag Presenter constructor
     *
     * @param Tag $tag
     */
    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $created = $this->tag->getCreated() instanceof \DateTime ?
            $this
                ->tag
                ->getCreated()
                ->format('Y-m-d H:i:s')
            :
            null
        ;

        $updated = $this->tag->getUpdated() instanceof \DateTime ?
            $this
                ->tag
                ->getUpdated()
                ->format('Y-m-d H:i:s')
            :
            null
        ;

        return [
            'id' => $this->tag->getId(),
            'name' => $this->tag->getName(),
            'textColor' => $this->tag->getTextColor(),
            'backgroundColor' => $this->tag->getBackgroundColor(),
            'created' => $created,
            'updated' => $updated,
        ];
    }
}
