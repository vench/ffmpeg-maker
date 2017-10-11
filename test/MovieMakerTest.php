<?php


require_once '../vendor/autoload.php';

use LpMovieMaker\MovieMaker;
use LpMovieMaker\Models\Movie;
use LpMovieMaker\Models\Frame;
use LpMovieMaker\Models\FadeEffect;
use LpMovieMaker\Models\Text;
use LpMovieMaker\Models\Audio;


/**
 * Created by PhpStorm.
 * User: vench
 * Date: 10.10.17
 * Time: 13:22
 */
class MovieMakerTest extends PHPUnit_Framework_TestCase
{

    public function testCommand() {

        $mv = new Movie([
            'width'         => 640,
            'height'        => 480,
            'outputFile'    =>  '/home/vench/projects/lp-movie-maker/source/out/movie.mp4'
        ]);

        $mv->addAudio(new Audio([
            'filePath'  => '/home/vench/projects/lp-movie-maker/source/sone.mp3',
        ]));

        $mv->addFrame(new Frame([
            'filePath'  => '/home/vench/projects/lp-movie-maker/source/image1.jpg',
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
            ])
        ]));
        $mv->addFrame(new Frame([
            'filePath'  => '/home/vench/projects/lp-movie-maker/source/image3.jpg',
            'duration'  => 2,
            'effects'   => [
                FadeEffect::makeIn(1, 0),
                FadeEffect::makeOut(1, 4)
            ],
            'text'  => new Text([
                'value' => 'Привет мир!',
            ])
        ]));
        $mv->addFrame(new Frame([
            'filePath'  => '/home/vench/projects/lp-movie-maker/source/image2.jpg',
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
            ])
        ]));
        $mv->addFrame(new Frame([
            'filePath'  => '/home/vench/projects/lp-movie-maker/source/image4.jpg',
            'duration'  => 3,
            'effects'   => [
                FadeEffect::makeIn(1, 0)
            ],
        ]));
        $mv->addFrame(new Frame([
            'filePath'  => '/home/vench/projects/lp-movie-maker/source/image5.jpg',
            'duration'  => 3,
            'effects'   => [
                FadeEffect::makeIn(1, 0)
            ],
        ]));

        $mm = new MovieMaker($mv);
        $mm->build();


    }
}