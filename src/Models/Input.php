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
     * @param string $exc
     * @return mixed|string
     */
    public function getFilePathExt($exc = 'png') {
        $file = $this->getFilePath();
        if(preg_match('/.+\/.+\.([a-zA-Z0-9]{2,4})/', $file, $math) && isset($math[1])) {
            return str_replace('.' .$math[1], $this->getName() . '.' . $exc, $file);
        }
        return $file;
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

}