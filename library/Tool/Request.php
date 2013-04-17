<?php
/**
 * @date: 25.03.13
 * @time: 18:06
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file: Request.php
 */


namespace Tool;


class Request
{
    const METHOD_GET     = 'get';
    const METHOD_POST    = 'post';
    const METHOD_PUT     = 'put';
    const METHOD_DELETE  = 'delete';
    const METHOD_HEAD    = 'head';
    const METHOD_OPTIONS = 'options';
    const METHOD_TRACE   = 'trace';
    const METHOD_CONNECT = 'connect';

    /**
     * @var ArrayObject
     */
    public $get;

    /**
     * @var ArrayObject
     */
    public $post;

    /**
     * @var ArrayObject
     */
    public $request;

    /**
     * @var ArrayObject
     */
    public $server;

    /**
     * @var ArrayObject
     */
    public $cookie;

    public function __construct()
    {
        $this->server = new ArrayObject($_SERVER);
        $this->server->setFilter('strtolower');

        $this->get = new ArrayObject($_GET);
        $this->post = new ArrayObject($_POST);
        $this->request = new ArrayObject($_REQUEST);
        $this->cookie = new ArrayObject($_COOKIE);

        $this->method = $this->server->get('REQUEST_METHOD');
        $this->isAjax = $this->server->get('HTTP_X_REQUESTED_WITH') === 'xmlhttprequest';
    }

    /**
     * @param string|array $methods
     *
     * @return bool
     */
    public function isRequestMethods($methods)
    {
        return in_array($this->method, (array)$methods, false);
    }

    public function isPost()
    {
        return $this->method === 'post';
    }

    public function isGet()
    {
        return $this->method === 'get';
    }

    public function isAjax()
    {
        return $this->isAjax;
    }

    public function getURI()
    {
        return $this->server->get('REQUEST_URI','/');
    }
}