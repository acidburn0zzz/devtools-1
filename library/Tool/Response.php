<?php
/**
 * @date: 17.04.13
 * @time: 14:55
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file: Response.php
 */


namespace Tool;


class Response
{
    const STATUS_OK = 200;
    const STATUS_NOT_FOUND = 404;

    protected $headers;
    protected $status;
    protected $content;

    public function __construct()
    {
        $this->headers = new ArrayObject();
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function addHeader($name, $value)
    {
        $this->headers->set($name, $value);

        return $this;
    }

    public function clearHeaders()
    {
        $this->headers->clear();

        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function send()
    {
        foreach($this->headers as $name => $value) {
            header(sprintf('%s: %s', $name, $value));
        }
        http_response_code($this->getStatus() ? : self::STATUS_OK);
        echo $this->getContent();
        flush();
    }

    public function __toString()
    {
        $this->send();
    }
}