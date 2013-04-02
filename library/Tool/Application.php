<?php
/**
 * @date: 25.03.13
 * @time: 15:06
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file: Application.php
 */
 

namespace Tool;


class Application {

    public function __construct()
    {
        spl_autoload_register(function($className){
            require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
        });
    }

    /**
     * @return static
     */
    public static function factory()
    {
        $reflection = new \ReflectionClass(get_called_class());
        return $reflection->newInstanceArgs(func_get_args());
    }

    public function run()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $domain = $_SERVER['HTTP_HOST'];
        $controllerName = preg_replace('/(\w+)\.tools/', '$1', $domain);
        $controllerClass = sprintf('Tool\\Controller\\%sController', ucfirst($controllerName));
        /**
         * @var $controller Controller\AbstractController
         */
        $controller = new $controllerClass();
        return $controller->dispatch();
    }
}