<?php


require_once '../vendor/autoload.php';

use LpMovieMaker\MovieMaker;
use LpMovieMaker\Models\Movie;
use LpMovieMaker\Models\ImageFrame;
use LpMovieMaker\Models\FadeEffect;
use LpMovieMaker\Models\Text;
use LpMovieMaker\Models\Audio;
use LpMovieMaker\Models\VideoFrame;


/**
 * Created by PhpStorm.
 * User: vench
 * Date: 10.10.17
 * Time: 13:22
 */
class MovieMakerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return string
     */
    public function getSourcePath() {
        return dirname(__FILE__) .  '/../source/';
    }

    /**
     *
     */
    public function testCommand() {

        $mv = new Movie([
            'width'         => 640,
            'height'        => 480,
            'outputFile'    =>  $this->getSourcePath() . 'out/movie.mp4',
        ]);

        $mv->addAudio(new Audio([
            'filePath'  => $this->getSourcePath() . 'sone.mp3',
        ]));

        $mv->addFrame(new ImageFrame([
            'filePath'  => $this->getSourcePath() . 'image1.jpg',
            'duration'  => 6,
            'effects'   => [
                FadeEffect::makeIn(1, 0),
                FadeEffect::makeOut(1, 4)
            ],
            'text'  => new Text([
                'value' => "Hello world!\nxsxsxsxs
xsxsx
xsxs",
                'posX'  => '10',
                'posY'  => '20',
                'color' => '#ffff00',
                'box'   => true,
                'wrap'  => false,
            ])
        ]));
        $mv->addFrame(new ImageFrame([
            'filePath'  => $this->getSourcePath() . 'image3.jpg',
            'duration'  => 2,
            'effects'   => [
                FadeEffect::makeIn(1, 0),
                FadeEffect::makeOut(1, 4)
            ],
            'text'  => new Text([
                'value' => 'Привет мир!',
            ])
        ]));
        $mv->addFrame(new ImageFrame([
            'filePath'  => $this->getSourcePath() . 'image2.jpg',
            'duration'  => 10,
            'effects'   => [
                FadeEffect::makeIn(1, 0)
            ],
            'text'  => new Text([
                'value' => 'Привет мир! Привет мир! Привет мир! '.  "\n".
                    '^LПривет мир! Привет мир! Привет мир! Привет мир! Привет мир!' . "\n".
                    'Привет мир! Привет мир! Привет мир! ^LПривет мир! Привет мир! Привет мир! Привет мир! Привет мир!' . "\n".
                    'Привет мир! Привет мир! Привет мир! Привет мир! Привет мир! Привет мир! Привет мир! Привет мир!'
                ,
                'posX'  => '10',
                'posY'  => '20',
                'color' => '#b642f4',
                'box'   => true,
                'boxColor' => '#ffffff',
            ])
        ]));
        $mv->addFrame(new ImageFrame([
            'filePath'  => $this->getSourcePath() . 'image4.jpg',
            'duration'  => 3,
            'effects'   => [
                FadeEffect::makeIn(1, 0)
            ],
        ]));
        $mv->addFrame(new ImageFrame([
            'filePath'  => $this->getSourcePath() . 'image5.jpg',
            'duration'  => 3,
            'effects'   => [
                FadeEffect::makeIn(1, 0)
            ],
        ]));

        $mm = new MovieMaker($mv);
        $mm->build();


    }

    /**
     *
     */
    public function testMerge() {

        $mv = new Movie([
            'width'         => 640,
            'height'        => 480,
            'outputFile'    =>  $this->getSourcePath() . 'out/join.mp4',
        ]);

        $mv->addFrame(new ImageFrame([
            'filePath'  => $this->getSourcePath() . 'german3.jpg',
            'duration'  => 5,
            'effects'   => [
                FadeEffect::makeIn(1, 0)
            ],
            'text'  => new Text([
                'value' => "Hello world!\n
Привет мир!",
                'posX'  => '10',
                'posY'  => '20',
                'color' => '#000000',
                'box'   => true,
                'wrap'  => false,
            ])
        ]));

        $mv->addFrame(new ImageFrame([
            'filePath'  => $this->getSourcePath() . 'german4.jpg',
            'duration'  => 3,
            'effects'   => [
                FadeEffect::makeIn(1, 0)
            ],
        ]));

        $mv->addFrame(new VideoFrame([
            'filePath'  => $this->getSourcePath() . 'videoplayback.mp4',
            'duration'  => 10,
            'allowProcessed'    => false,
            'effects'   => [
                FadeEffect::makeIn(1, 0)
            ],
        ])); /**/
        $mv->addAudio(new Audio([
            'filePath'  => $this->getSourcePath() . 'sone.mp3',
            'duration'  => 30,
        ]));





        $mm = new MovieMaker($mv);
        $mm->build();

    }
}