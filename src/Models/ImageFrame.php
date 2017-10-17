<?php
/**
 * Created by PhpStorm.
 * User: vench
 * Date: 16.10.17
 * Time: 11:59
 */

namespace LpMovieMaker\Models;


use LpMovieMaker\MovieMaker;

class ImageFrame extends Frame
{


    /**
     * @param Movie $movie
     * @param bool $force
     * @return string
     */
    public function getProcessedFile(Movie $movie, $force = false)
    {
        return $this->isProcessed() || $force ?
            $this->getFilePathExt($movie->getProcessedExtensionImage(), $movie->getOutputDirectory(), true) : $this->getFilePath();
    }
}