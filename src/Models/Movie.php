<?php
/**
 * Created by PhpStorm.
 * User: vench
 * Date: 10.10.17
 * Time: 13:00
 */

namespace LpMovieMaker\Models;



class Movie
{

    /**
     * @var Frame[]
     */
    protected $frames = [];

    /**
     * @var int
     */
    protected $width = 640;

    /**
     * @var int
     */
    protected $height = 480;

    /**
     * @var string
     */
    protected $processedExtension = 'png';

    /**
     * @var Audio[]
     */
    protected $audioTracks = [];

    /**
     * @var string
     */
    protected $outputFile = '';


    /**
     * Movie constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = []) {

        foreach ($attributes as $name => $value) {
            $this->{$name} = $value;
        }
    }

    /**
     * @return Frame[]
     */
    public function getFrames()
    {
        return $this->frames;
    }

    /**
     * @return int
     */
    public function getFrameCount() {
        return count($this->getFrames());
    }

    /**
     * @param Frame[] $frames
     */
    public function setFrames($frames)
    {
        $this->frames = $frames;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }


    /**
     * @return Audio[]
     */
    public function getAudioTracks()
    {
        return $this->audioTracks;
    }

    /**
     * @param Audio[] $audioTracks
     */
    public function setAudioTracks($audioTracks)
    {
        $this->audioTracks = $audioTracks;
    }


    /**
     * @return int
     */
    public function getDuration() {
        $duration = 0;
        foreach ($this->frames as $frame) {
            $duration += $frame->getDuration();
        }
        return $duration;
    }

    /**
     * @param Frame $frame
     */
    public function addFrame(Frame $frame) {
        $this->frames[] = $frame;
    }


    /**
     * @param Audio $audio
     */
    public function addAudio(Audio $audio) {
        $this->audioTracks[] = $audio;
    }

    /**
     * @return bool
     */
    public function needResize() {
        return !empty($this->getHeight()) && !empty($this->getWidth());
    }

    /**
     * @return string
     */
    public function getProcessedExtension(): string
    {
        return $this->processedExtension;
    }

    /**
     * @param string $processedExtension
     */
    public function setProcessedExtension(string $processedExtension)
    {
        $this->processedExtension = $processedExtension;
    }

    /**
     * @return string
     */
    public function getOutputFile(): string
    {
        return $this->outputFile;
    }

    /**
     * @param string $outputFile
     */
    public function setOutputFile(string $outputFile)
    {
        $this->outputFile = $outputFile;
    }






}