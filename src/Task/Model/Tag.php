<?php

namespace Acme\Task\Model;

/**
 * Tag
 *
 * @package Acme\Task\Model
 */
class Tag
{
    use DateTimeTrait;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $textColor;

    /**
     * @var string
     */
    private $backgroundColor;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    /**
     * @param string $textColor
     */
    public function setTextColor(string $textColor): void
    {
        $this->textColor = $textColor;
    }

    /**
     * @return string|null
     * @return void
     */
    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    /**
     * @param string $backgroundColor
     * @return void
     */
    public function setBackgroundColor(string $backgroundColor): void
    {
        $this->backgroundColor = $backgroundColor;
    }

    /**
     * @param string|null $color
     * @return bool
     */
    public static function isHexColor(?string $color): bool
    {
        return (bool) preg_match("/([a-fA-F0-9]{3}){1,2}/", $color);
    }
}
