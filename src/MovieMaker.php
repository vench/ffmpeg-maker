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
use LpMovieMaker\Models\ImageFrame;
use LpMovieMaker\Models\Movie;
use LpMovieMaker\Models\Text;
use LpMovieMaker\Models\VideoFrame;

class MovieMaker
{


    const PROGRAM_NAME = 'ffmpeg';


    /**
     * MovieMaker constructor.
     * @param Movie|null $movie
     */
    public function __construct(Movie $movie = null)  {
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
            $this->processedCommand($frame);
        }

        $this->buildMove();
        //$this->clean();
    }


    /**
     *
     */
    public function clean() {
        $movie = $this->getMovie();
        $scan = scandir( $movie->getOutputDirectory() );

        foreach ($scan as $file) {
            if(preg_match('/f_[0-9]+\./', $file)) {
                $this->exec("rm {$movie->getOutputDirectory()}/{$file}");
            }
        }
    }


    /**
     * @param $outputFile
     * @return null|string
     */
    private function getAddAudioCommand($outputFile){
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
     *
     */
    private function buildMove(){

        $movie = $this->getMovie();
        $movies = [];
        $images = [];

        foreach ($movie->getFrames() as $frame) {

            if($frame instanceof VideoFrame) {
                if(!empty($images)) {
                    $movies[] = $this->imagesToVideo($images);
                    $images = [];
                }

                $movies[] = $frame;
                continue;
            }

            $images[] = $frame;
        }

        if(!empty($images)) {
            $movies[] = $this->imagesToVideo($images);
        }

        $command = self::PROGRAM_NAME;
        $video =  new VideoFrame([
            'filePath' => $movie->getOutputFile(),
        ]);
        $outputFile = $video->getFilePathExt(null, null, true);

        $moviesStr = join('|', array_map(function($m) use(&$movie){ return $m->getProcessedFile($movie); }, $movies));
        // -f mpegts
        $command .= "  -i 'concat:{$moviesStr}'  -y {$outputFile}"; //-c copy -bsf:a yuv420p
        $this->exec($command);

        //add audio
        if($command = $this->getAddAudioCommand($outputFile)) {
            $this->exec($command);
        } else {
            $this->exec("mv {$outputFile} {$movie->getOutputFile()}");
        }
    }


    /**
     * @param ImageFrame[] $frames
     * @return VideoFrame
     */
    private function imagesToVideo($frames) {
        $command = self::PROGRAM_NAME;
        $effects = [];
        $count = 0;

        $movie = $this->getMovie();

        foreach ($frames as $n => $frame) {

            $command .= " -loop 1 -t {$frame->getDuration()} -i {$frame->getProcessedFile($movie)} ";
            $efs = [];
            foreach($frame->getEffects() as $ef) {
                $efs[] = $ef->getCommands();
            }
            if(empty($efs)) {
                //$n = count($effects);
                $efs[] = "trim=duration={$frame->getDuration()}";
            }
            $effects['[v' . $n.']'] = '['.$n.':v]' . join(',', $efs) .'[v'.$n.'];';
        }

        $command .= " -filter_complex \"";

        if(!empty($effects)) {
            $command .= join('', $effects) . ' ';
            $command .= join('', array_keys($effects));
        }
        //TODO format=yuv420p to vars
        $count = count($frames);
        $command .= "concat=n={$count}:v=1:a=0,format=yuv420p[v]\" ";

        $video =  new VideoFrame([
            'filePath' => $movie->getOutputFile(),
        ]);

        $command .= '-map "[v]" -vb 20M ';
        $command .= "-y {$video->getProcessedFile($movie, true)}";

        $this->exec($command);
        $video->setProcessed(true);

        return $video;
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
     * @param Frame $frame
     */
    private function processedCommand(Frame $frame) {
        if(!empty($command = $this->getProcessedCommand($frame))) {
            $this->exec($command);
            $frame->setProcessed(true);
        }
    }

    /**
     * @param Frame $frame
     * @return string
     */
    private function getProcessedCommand(Frame $frame) {

        switch ($frame->getClassName()) {
            case VideoFrame::class:
                return $this->getProcessedCommandVideo($frame);
            case ImageFrame::class:
                return $this->getProcessedCommandImage($frame);
        }

        return '';
    }

    /**
     * @param VideoFrame $frame
     * @return string
     */
    private function getProcessedCommandVideo(VideoFrame $frame) {
        $movie = $this->getMovie();
        $filters = [];
        if($movie->needResize()) {
            $filters[] = "scale={$movie->getWidth()}:{$movie->getHeight()}";
        }
        $duration = $frame->getDuration() > 0 ? $frame->getDuration() : 1;
        $command = MovieMaker::PROGRAM_NAME;
        if($duration) {
            $command .= "  -t {$duration} ";
        }
        $command .= "  -i {$frame->getFilePath()} ";
        $command .= '-vf "'. join(',', $filters) .'" ';
        //TODO to config
        //$command .= ' -bsf:v h264_mp4toannexb -f mpegts ';

        $command .= "-y {$frame->getProcessedFile($movie, true)}";
        return $command;
    }

    /**
     * @param ImageFrame $frame
     * @return string
     */
    private function getProcessedCommandImage(ImageFrame $frame) {
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
        $command .= " -i {$frame->getFilePath()} ";
        $command .= '-vf "'. join(',', $filters) .'" ';
        $command .= "-y {$frame->getProcessedFile($movie, true)}";

        return $command;
    }

}