<?php
/**
 * @date: 25.03.13
 * @time: 18:25
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file: Store.php
 */
 

namespace Tool;


class Store {

    private $fileName;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
        touch($this->fileName);
    }

    public function save($data)
    {
        file_put_contents($this->fileName, json_encode($data));
    }

    public function load()
    {
        return json_decode(file_get_contents($this->fileName), true);
    }


}