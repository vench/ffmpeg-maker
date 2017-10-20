<?php
/**
 * Created by PhpStorm.
 * User: vench
 * Date: 17.10.17
 * Time: 12:55
 */

namespace LpMovieMaker\Models;

/**
 * Class ZoomEffect
 * @package LpMovieMaker\Models
 */
class FadeEffect extends Effect
{

    /**
     * @var int
     */
    protected $duration = 1;

    /**
     * @var int
     */
    protected $fps = 25;

    protected $scale;

    /**
     * ZoomEffect constructor.
     * @param int $duration
     * @param int $fps
     */
    public function __construct($duration = 1, $fps = 25, $scale = null)
    {
        $this->duration = $duration;
        $this->fps = $fps;
        if(is_null($scale)) {
            $scale = '640x480';
        }

        $this->scale = $scale;
    }


    /**
     * @return string
     */
    public function getCommands()
    {
        $d = $this->getDuration() * $this->getFps();
        return "zoompan=z='if(lte(zoom,1.0),1.5,max(1.001,zoom-0.0050))':d={$d},trim=duration={$this->getDuration()},scale={$this->getScale()},setdar=dar=4:3";
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
    public function getFps(): int
    {
        return $this->fps;
    }

    /**
     * @param int $fps
     */
    public function setFps(int $fps)
    {
        $this->fps = $fps;
    }

    /**
     * @return null|string
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * @param null|string $scale
     */
    public function setScale($scale)
    {
        $this->scale = $scale;
    }




}