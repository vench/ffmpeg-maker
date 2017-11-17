# ffmpeg-maker
PHP library allow create simple movie with ffmpeg

### Install

> TODO


### Initialize object


Initialize object Movie

``` 
$mv = new Movie([
    'width'             => 640,
    'height'            => 480,
    'outputFile'        => 'movie.mp4',
    'outputDirectory'   => './out',
]);
``` 

Add frame

```
$mv->addFrame(new ImageFrame([
    'filePath'  => 'image1.jpg',
    'duration'  => 6,
    'effects'   => [
         FadeEffect::makeIn(1, 0),
         FadeEffect::makeOut(1, 4)
    ],
    'text'  => new Text([
         'value' => "Hello world!\nSome text.",
         'posX'  => '10',
         'posY'  => '20',
         'color' => '#ffff00',
         'box'   => true,
         'wrap'  => false,
    ])
]));
```

Add audio track 

```
$mv->addAudio(new Audio([
    'filePath'  => 'audio.mp3',
]));
```

Build movie

```
$mm = new MovieMaker($mv);
$mm->build();
```


