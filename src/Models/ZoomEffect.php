<?php
/**
 * Created by PhpStorm.
 * User: vench
 * Date: 17.10.17
 * Time: 12:55
 */

namespace LpMovieMaker\Models;


class ZoomEffect extends Effect
{

    /**
     * @var int
     */
    protected $duration = 1;

    /**
     * @var int
     */
    protected $fps = 25;

    /**
     * ZoomEffect constructor.
     * @param int $duration
     * @param int $fps
     */
    public function __construct($duration = 1, $fps = 25)
    {
        $this->duration = $duration;
        $this->fps = $fps;
    }


    /**
     * @return string
     */
    public function getCommands()
    {
        $d = $this->getDuration() * $this->getFps();
        return "zoompan=z='if(lte(zoom,1.0),1.5,max(1.001,zoom-0.0015))':d={$d},trim=duration={$this->getDuration()}";
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



}