<?php
/**
 * Created by PhpStorm.
 * User: vench
 * Date: 10.10.17
 * Time: 13:00
 */

namespace LpMovieMaker\Models;

/**
 * Class Frame
 * @package LpMovieMaker\Models
 */
abstract class Frame extends Input
{




    /**
     * @var Effect[]
     */
    protected $effects = [];

    /**
     * @var Text
     */
    protected $text;

    /**
     * @var boolean
     */
    protected $processed = false;



    /**
     * @return Effect[]
     */
    public function getEffects()
    {
        return $this->effects;
    }

    /**
     * @param Effect[] $effects
     */
    public function setEffects($effects)
    {
        $this->effects = $effects;
    }

    /**
     * @param Effect $effect
     */
    public function addEffect(Effect $effect) {
        $this->effects[] = $effect;
    }

    /**
     * @return Text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param Text $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }


    /**
     * @param bool $processed
     */
    public function setProcessed($processed) {
        $this->processed = $processed;
    }


    /**
     * @return bool
     */
    public function isProcessed() {
        return $this->processed;
    }


    /**
     * @param Movie $movie
     * @return string
     */
    abstract public function getProcessedFile(Movie $movie);


    /**
     * @param Movie $movie
     * @return string
     */
    abstract public function getProcessedCommand(Movie $movie);

}