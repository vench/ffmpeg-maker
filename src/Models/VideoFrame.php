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
     * @param bool $force
     * @return string
     */
    public function getProcessedFile(Movie $movie, $force = false)
    {
        return $this->isProcessed() || $force ?
            $this->getFilePathExt($movie->getProcessedExtensionVideo(), $movie->getOutputDirectory(), true) : $this->getFilePath();
    }
}