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

    const TEXT_POSITION_LEFT = 5;

    const TEXT_POSITION_TOP = 1;

    const TEXT_POSITION_CENTER = 2;

    const TEXT_POSITION_RIGHT = 3;

    const TEXT_POSITION_BOTTOM = 4;

    const TEXT_MARGIN = 0.1;


    /**
     * @var string
     */
    protected $value = '';

    /**
     * @var string
     */
    protected $posX = '0';

    /**
     * @var string
     */
    protected $posY = '0';

    /**
     * @var string
     */
    protected $color = '#000000';


    /**
     * @var string
     */
    protected $fontLink = '';

    /**
     * @var int
     */
    protected $fontSize = 24;

    /**
     * Перенос но новую строку будет определен как новый объект текста
     * @var bool
     */
    protected $wrap = false;

    /**
     * @var bool
     */
    protected $box = false;

    /**
     * @var string
     */
    protected $boxColor = '#ffffff';


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
     * @return string
     */
    public function getPosX()
    {
        return $this->posX;
    }

    /**
     * @param string $posX
     */
    public function setPosX($posX)
    {
        $this->posX = $posX;
    }

    /**
     * @return string
     */
    public function getPosY()
    {
        return $this->posY;
    }

    /**
     * @param string $posY
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

    /**
     * @return bool
     */
    public function isBox(): bool
    {
        return $this->box;
    }

    /**
     * @param bool $box
     */
    public function setBox(bool $box)
    {
        $this->box = $box;
    }

    /**
     * @return string
     */
    public function getBoxColor(): string
    {
        return $this->boxColor;
    }

    /**
     * @param string $boxColor
     */
    public function setBoxColor(string $boxColor)
    {
        $this->boxColor = $boxColor;
    }


    /**
     * @param int $horizontal
     * @param int $vertical
     * @see TEXT_POSITION_*
     */
    public function setTextPosition($horizontal, $vertical) {
        $margin = self::TEXT_MARGIN;

        $x =  "(w * {$margin})";//LEFT
        $y = "(h * {$margin})";//TOP

        if($horizontal == self::TEXT_POSITION_CENTER) {
            $x = "(w / 2 - text_w / 2)";
        } else if($horizontal == self::TEXT_POSITION_RIGHT) {
            $x = "((w - text_w) - (w * {$margin}))";
        }

        if($vertical == self::TEXT_POSITION_CENTER) {
            $y = "(h / 2 - text_h / 2)";
        } else if($vertical == self::TEXT_POSITION_BOTTOM) {
            $y = "((h - text_h) - (h * {$margin}))";
        }

        $this->setPosX($x);
        $this->setPosY($y);
    }


}