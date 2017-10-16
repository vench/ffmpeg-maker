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
     * @return string
     */
    public function getProcessedCommand(Movie $movie)
    {
        //TODO ['setsar=1:1', 'setdar=4:3'] to options
        $filters = ['setsar=1:1', 'setdar=4:3'];
        if($movie->needResize()) {
            $filters[] = "scale={$movie->getWidth()}:{$movie->getHeight()}";
        }

        if(!is_null($text = $this->getText())) {
            $values = $text->isWrap() ? explode("\n", $text->getValue()) : [$text->getValue()];
            $posY =  $text->getPosY();
            foreach ($values as $value) {
                $value = trim($value);
                $fontfile = $text->getFontLink() ? "fontfile={$text->getFontLink()}:" : '';
                $fontcolor = $text->getColor() ? ":fontcolor={$text->getColor()}" : '';
                $fontsize = $text->getColor() ? ":fontsize={$text->getFontSize()}" : '';
                $x = $text->getPosX() ? ":x={$text->getPosX()}" : '';
                $y = $posY ? ":y={$posY}" : '';
                $box = $text->isBox() ? ":box=1:boxcolor={$text->getBoxColor()}:boxborderw=5" : '';
                $drawtext = "drawtext={$fontfile}text='{$value}'{$fontcolor}{$fontsize}{$x}{$y}{$box}";
                $filters[] = $drawtext;
                $posY += ($text->getFontSize() ? $text->getFontSize() : 0) + 15;
            }
        }

        if(empty($filters)) {
            return null;
        }

        $command = MovieMaker::PROGRAM_NAME;
        $command .= " -i {$this->getFilePath()} ";
        $command .= '-vf "'. join(',', $filters) .'" ';
        $command .= "-y {$this->getFilePathExt($movie->getProcessedExtensionImage())}";

        return $command;
    }

    /**
     * @param Movie $movie
     * @return string
     */
    public function getProcessedFile(Movie $movie)
    {
        return $this->isProcessed() ?
            $this->getFilePathExt($movie->getProcessedExtensionImage()) : $this->getFilePath();
    }
}