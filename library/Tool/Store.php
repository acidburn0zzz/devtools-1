<?php
/**
 * @date: 25.03.13
 * @time: 18:25
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file: Store.php
 */
 

namespace Tool;


class Store {

    private $storeDir;
    private $fileName;

    public function __construct($storeDir)
    {
        $this->storeDir = rtrim($storeDir,'\\/');
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        touch($this->getFilePath());
        return $this;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function getFilePath()
    {
        return $this->storeDir . DIRECTORY_SEPARATOR . $this->fileName . '.json';
    }

    public function save($data)
    {
        file_put_contents($this->getFilePath(), json_encode($data));
        return $this;
    }

    public function load()
    {
        return json_decode(file_get_contents($this->getFilePath()), true);
    }


}