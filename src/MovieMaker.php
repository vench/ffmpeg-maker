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
use LpMovieMaker\Models\VideoFrame;

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
            if(!empty($command = $frame->getProcessedCommand($movie))) {
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
        $count = 0;

        $fun = function() use(&$buffer, &$effects, &$movie, &$count) {
            $buffer .= " -filter_complex \"";

            if(!empty($effects)) {
                $buffer .= join('', $effects) . ' ';
                $buffer .= join('', array_keys($effects));
            }

            //TODO format=yuv420p to vars
            $buffer .="concat=n={$count}:v=1:a=0,format=yuv420p[v]\" ";


            $video =  new VideoFrame([
                'filePath' => $movie->getOutputFile(),
            ]);

            $buffer .= '-map "[v]" ';
            $buffer .= "-y {$video->getFilePathExt($movie->getProcessedExtensionVideo())}";
            $this->exec($buffer);
            $video->setProcessed(true);

            $effects = [];
            $buffer = self::PROGRAM_NAME;
            $count = 0;

            return $video;
        };

        $movies = [];


        foreach ($movie->getFrames() as $frame) {

            if($frame instanceof VideoFrame) {
                if($count > 0) {
                    $movies[] = $fun();
                }

                $movies[] = $frame;
                continue;
            }

            $count ++;

            $buffer .= " -loop 1 -t {$frame->getDuration()} -i {$frame->getProcessedFile($movie)} ";

            $efs = [];
            foreach($frame->getEffects() as $ef) {
                $efs[] = $ef->getCommands();
            }
            if(!empty($efs)) {
                $n = count($effects);
                $effects['[v' . $n.']'] = '['.$n.':v]' . join(',', $efs) .'[v'.$n.'];';
            }
        }

        if(!empty($effects)) {
            $movies[] = $fun();
        }

        $buffer = self::PROGRAM_NAME;
        $moviesStr = join('|', array_map(function($m) use(&$movie){ return $m->getProcessedFile($movie); }, $movies));
        // -f mpegts
        $buffer .= "  -i 'concat:{$moviesStr}'  -y {$movie->getOutputFile()}"; //-c copy -bsf:a yuv420p

        return $buffer;
    }


    private function imagesToVideo() {

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





}