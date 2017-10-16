<?php
/**
 * Created by PhpStorm.
 * User: vench
 * Date: 16.10.17
 * Time: 12:00
 */

namespace LpMovieMaker\Models;

use LpMovieMaker\MovieMaker;

class VideoFrame extends Frame
{

    /**
     * @param Movie $movie
     * @return string
     */
    public function getProcessedCommand(Movie $movie)
    {

        $filters = [];
        if($movie->needResize()) {
            $filters[] = "scale={$movie->getWidth()}:{$movie->getHeight()}";
        }
        $duration = $this->getDuration() > 0 ? $this->getDuration() : 1;
        $command = MovieMaker::PROGRAM_NAME;
        if($duration) {
            $command .= "  -t {$duration} ";
        }
        $command .= "  -i {$this->getFilePath()} ";
        $command .= '-vf "'. join(',', $filters) .'" ';
        //TODO to config
        //$command .= ' -bsf:v h264_mp4toannexb -f mpegts ';

        $command .= "-y {$this->getFilePathExt($movie->getProcessedExtensionVideo())}";
        return $command;
    }

    /**
     * @param Movie $movie
     * @return string
     */
    public function getProcessedFile(Movie $movie)
    {
        return $this->isProcessed() ?
            $this->getFilePathExt($movie->getProcessedExtensionVideo()) : $this->getFilePath();
    }
}