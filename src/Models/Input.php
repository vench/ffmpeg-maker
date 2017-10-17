<?php
/**
 * Created by PhpStorm.
 * User: vench
 * Date: 10.10.17
 * Time: 13:08
 */

namespace LpMovieMaker\Models;


abstract class Input
{

    /**
     * @var int
     */
    private static $index = 0;


    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $filePath = '';


    /**
     * @var integer
     */
    protected $duration = 0;


    /**
     * Input constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = []) {

        foreach ($attributes as $name => $value) {
            $this->{$name} = $value;
        }
    }


    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }


    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @param string $mask
     * @return mixed|string
     */
    public function getFilePathMask($mask = '%s') {
        $file = $this->getFilePath();
        if(preg_match('/.+\/(.+)\.[a-zA-Z]{2,}/', $file, $math) && isset($math[1])) {
            return str_replace($math[1], $mask, $file);
        }
        return $file;
    }

    /**
     * @param null|string $exc
     * @param null|string $outputDir
     * @param boolean $modeName
     * @return string
     */
    public function getFilePathExt($exc = null, $outputDir = null, $modeName = false) {

        $info =  pathinfo($this->getFilePath());
        if(empty($outputDir)) {
            $outputDir = $info['dirname'];
        }
        if(empty($exc)) {
            $exc = $info['extension'];
        }
        $filename = $info['filename'];
        if($modeName) {
            $filename  .= $this->getName();
        }
        return rtrim($outputDir, '/') .DIRECTORY_SEPARATOR . $filename . '.' .$exc;
    }

    /**
     * @return string
     */
    public function getName()
    {
        if(empty($this->name)) {
            $this->name = sprintf("f_%d", $this->getUnique());
        }
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @return int
     */
    protected function getUnique() {
        return ++ self::$index;
    }


    /**
     * @return string
     */
    public function getClassName() {
        return static::class;
    }


}