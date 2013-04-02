<?php
/**
 * @date: 25.03.13
 * @time: 18:06
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file: Request.php
 */
 

namespace Tool;


class Request {

    private $method;
    private $isAjax;

    public function __construct()
    {
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])==='xmlhttprequest');
    }

    public function isPost()
    {
        return $this->method ==='post';
    }

    public function isGet()
    {
        return $this->method ==='get';
    }

    public function isAjax()
    {
        return $this->isAjax;
    }

    private function get($array, $name, $default = null)
    {
        return isset($array[$name]) ? $array[$name] : $default;
    }

    public function getQuery($name, $default = null)
    {
        return $this->get($_GET, $name, $default);
    }

    public function getPost($name, $default)
    {
        return $this->get($_POST, $name, $default);
    }

    public function getRequest($name, $default)
    {
        return $this->get($_REQUEST, $name, $default);
    }

}