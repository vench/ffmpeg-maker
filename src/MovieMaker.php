<?php
/**
 * Created by PhpStorm.
 * User: vench
 * Date: 10.10.17
 * Time: 13:01
 */

namespace LpMovieMaker;


use LpMovieMaker\Models\Audio;
use LpMovieMaker\Models\Frame;
use LpMovieMaker\Models\Movie;
use LpMovieMaker\Models\Text;

class MovieMaker
{


    const PROGRAM_NAME = 'ffmpeg';


    /**
     * MovieMaker constructor.
     * @param Movie|null $movie
     */
    function __construct(Movie $movie = null)  {
        if(is_null($movie)) {
            $movie = new Movie();
        }

        $this->movie = $movie;
    }

    /**
     * @return Movie|null
     */
    public function getMovie() {
        return $this->movie;
    }


    /**
     *
     */
    public function build() {
        $movie = $this->getMovie();
        foreach ($movie->getFrames() as $frame) {
            $command = $this->getProcessedCommand($frame);
            if($command) {
                $this->exec($command);
                $frame->setProcessed(true);
            }
        }

        $command = $this->getMoveCommand();
        $this->exec($command);

        //add audio
        $tmp = $movie->getOutputFile() . '.tmp';
        if($command = $this->getAddAudioCommand($tmp)) {
            $this->exec("mv {$movie->getOutputFile()} {$tmp}");
            $this->exec($command);
        }
    }

    /**
     * @param Frame $frame
     * @return null|string
     */
    public function getProcessedCommand(Frame $frame){
        $movie = $this->getMovie();

        //TODO ['setsar=1:1', 'setdar=4:3'] to options
        $filters = ['setsar=1:1', 'setdar=4:3'];
        if($movie->needResize()) {
            $filters[] = "scale={$movie->getWidth()}:{$movie->getHeight()}";
        }

        if(!is_null($text = $frame->getText())) {
            $values = $text->isWrap() ? explode("\n", $text->getValue()) : [$text->getValue()];
            $posY =  $text->getPosY();
            foreach ($values as $value) {
                $value = trim($value);
                $fontfile = $text->getFontLink() ? "fontfile={$text->getFontLink()}:" : '';
                $fontcolor = $text->getColor() ? ":fontcolor={$text->getColor()}" : '';
                $fontsize = $text->getColor() ? ":fontsize={$text->getFontSize()}" : '';
                $x = $text->getPosX() ? ":x={$text->getPosX()}" : '';
                $y = $posY ? ":y={$posY}" : '';
                $drawtext = "drawtext={$fontfile}text='{$value}'{$fontcolor}{$fontsize}{$x}{$y}";
                $filters[] = $drawtext;
                $posY += ($text->getFontSize() ? $text->getFontSize() : 0) + 15;
            }
        }

        if(empty($filters)) {
            return null;
        }

        $command = self::PROGRAM_NAME;
        $command .= " -i {$frame->getFilePath()} ";
        $command .= '-vf "'. join(',', $filters) .'" ';
        $command .= "-y {$frame->getFilePathExt($movie->getProcessedExtension())}";

        return $command;
    }

    /**
     * @param string $outputFile
     * @return null|string
     */
    public function getAddAudioCommand($outputFile){
        $movie = $this->getMovie();
        if(empty($movie->getAudioTracks())) {
            return null;
        }

        $buffer = self::PROGRAM_NAME;
        $totalDuration = $movie->getDuration();

        $buffer .= " -i {$outputFile} ";
        foreach ($movie->getAudioTracks() as $audio) {
            $duration = $audio->getDuration() ? $audio->getDuration() : $totalDuration;
            $buffer .= " -t {$duration} -i {$audio->getFilePath()} ";
        }

        $buffer .= "-c copy -map 0:v:0 -map 1:a:0 -y {$movie->getOutputFile()}";

        return $buffer;
    }


    /**
     * @return string
     */
    public function getMoveCommand(){
        $buffer = self::PROGRAM_NAME;

        $movie = $this->getMovie();

        $effects = [];

        foreach ($movie->getFrames() as $n => $frame) {
            $file = $frame->isProcessed() ?
                $frame->getFilePathExt($movie->getProcessedExtension()) : $frame->getFilePath();
            $buffer .= " -loop 1 -t {$frame->getDuration()} -i {$file} ";

            $efs = [];
            foreach($frame->getEffects() as $ef) {
                $efs[] = $ef->getCommands();
            }
            if(!empty($efs)) {
                $effects['[v' . $n.']'] = '['.$n.':v]' . join(',', $efs) .'[v'.$n.'];';
            }
        }

        $buffer .= " -filter_complex \"";

        if(!empty($effects)) {
            $buffer .= join('', $effects) . ' ';
            $buffer .= join('', array_keys($effects));
        }

        //TODO format=yuv420p to vars
        $buffer .="concat=n={$movie->getFrameCount()}:v=1:a=0,format=yuv420p[v]\" ";

        $buffer .= '-map "[v]" ';
        $buffer .= "-y {$movie->getOutputFile()}";

        return $buffer;
    }

    /**
     * @param string $command
     * @throws \Exception
     */
    private function exec($command) {

        echo $command, PHP_EOL;

        $ret = null;
        $cmd = system($command, $ret);

        if($ret !== 0) {
            throw new \Exception("Returned an error: $cmd");
        }
    }

    /**
     * @param  string $jsonString
     * @return MovieMaker
     */
    public static function makeByJson( $jsonString ) {
        $data = json_decode($jsonString, true);

        $mv = new Movie([
            'width'                 =>  $data['width'] ? : 0,
            'height'                =>  $data['height'] ? : 0,
            'outputFile'            =>  $data['outputFile'] ? : 0,
            'processedExtension'    =>  $data['processedExtension'] ? : 0,

            'frames'            =>  isset($data['frames']) ?
                array_map(function($f){

                    if(isset($f['text'])) {
                        $f['text'] = new Text( $f['text']);
                    }
                    if(isset($f['effects'])) {
                        $f['effects'] = [];
                        //TODO
                    }

                    return new Frame($f);
                }, $data['frames'])
                : [],

            'audioTracks'        =>  isset($data['audioTracks']) ?
            array_map(function($a){
                return new Audio($a);
            }, $data['audioTracks'])
            : []
        ]);



        return new MovieMaker($mv);
    }



}