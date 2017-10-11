<?php
/**
 * Created by PhpStorm.
 * User: vench
 * Date: 10.10.17
 * Time: 13:12
 */

namespace LpMovieMaker\Models;


class Text
{

    /**
     * @var string
     */
    protected $value = '';

    /**
     * @var int
     */
    protected $posX = 0;

    /**
     * @var int
     */
    protected $posY = 0;

    /**
     * @var string
     */
    protected $color = '#ffffff';


    /**
     * @var string
     */
    protected $fontLink = '';

    /**
     * @var int
     */
    protected $fontSize = 24;

    /**
     * @var bool
     */
    protected $wrap = true;


    /**
     * Text constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = []) {

        foreach ($attributes as $name => $value) {
            $this->{$name} = $value;
        }
    }


    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getPosX()
    {
        return $this->posX;
    }

    /**
     * @param int $posX
     */
    public function setPosX($posX)
    {
        $this->posX = $posX;
    }

    /**
     * @return int
     */
    public function getPosY()
    {
        return $this->posY;
    }

    /**
     * @param int $posY
     */
    public function setPosY($posY)
    {
        $this->posY = $posY;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getFontLink()
    {
        return $this->fontLink;
    }

    /**
     * @param string $fontLink
     */
    public function setFontLink($fontLink)
    {
        $this->fontLink = $fontLink;
    }

    /**
     * @return int
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }

    /**
     * @param int $fontSize
     */
    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;
    }

    /**
     * @return bool
     */
    public function isWrap(): bool
    {
        return $this->wrap;
    }

    /**
     * @param bool $wrap
     */
    public function setWrap(bool $wrap)
    {
        $this->wrap = $wrap;
    }




}