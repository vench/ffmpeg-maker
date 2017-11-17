#ffmpeg-maker
PHP library allow create simple movie with ffmpeg

Initialize object

`` 
$mv = new Movie([
'width'             => 640,
'height'            => 480,
'outputFile'        =>  $this->getSourcePath() . 'out/movie.mp4',
'outputDirectory'   => $this->getSourcePath() . 'out',
]);

``

