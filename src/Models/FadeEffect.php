<?php
/**
 * Created by PhpStorm.
 * User: vench
 * Date: 11.10.17
 * Time: 10:31
 */

namespace LpMovieMaker\Models;


/**
 * Class FadeEffect
 * @package LpMovieMaker\Models
 */
class FadeEffect extends Effect
{


    const TYPE_IN = 'in';

    const TYPE_OUT = 'out';

    /**
     * @var string
     */
    protected $type = '';


    /**
     * @var int
     */
    protected $duration = 1;

    /**
     * @var int
     */
    protected $startTime = 0;


    /**
     * FadeEffect constructor.
     * @param $type
     * @param int $duration
     * @param int $startTime
     */
    public function __construct($type, $duration = 1, $startTime = 0)
    {
        $this->type = $type;
        $this->duration = $duration;
        $this->startTime = $startTime;
    }


    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration(int $duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return int
     */
    public function getStartTime(): int
    {
        return $this->startTime;
    }

    /**
     * @param int $startTime
     */
    public function setStartTime(int $startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }


    /**
     * @return string
     */
    public function getCommands()
    {
        $st = $this->getStartTime();
        return "fade=t={$this->getType()}:st={$st}:d={$this->getDuration()}";
    }

    /**
     * @param int $duration
     * @param int $startTime
     * @return FadeEffect
     */
    public static function makeIn($duration = 1, $startTime = 0) {
        return new static(static::TYPE_IN, $duration, $startTime);
    }

    /**
     * @param int $duration
     * @param int $startTime
     * @return FadeEffect
     */
    public static function makeOut($duration = 1, $startTime = 0) {
        return new static(static::TYPE_OUT, $duration, $startTime);
    }
}