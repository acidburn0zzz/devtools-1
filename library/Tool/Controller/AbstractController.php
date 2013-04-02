<?php
/**
 * @date: 25.03.13
 * @time: 15:07
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file: AbstractController.php
 */
 

namespace Tool\Controller;


use Tool\Request;
use Tool\Router;
use Tool\View;
use Tool\Store;

abstract class AbstractController {

    private $action;
    /**
     * @var Router
     */
    protected $router;
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Store
     */
    protected $store;
    /**
     * @var View
     */
    protected $view;


    public function __construct()
    {
        $this->view = new View();
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function dispatch()
    {
        $parts = explode('\\', get_called_class());
        $className = array_pop($parts);
        $controller = preg_replace('/^(\w+)Controller$/','$1', $className);

        $this->router = new Router();
        $this->request = new Request();
        $this->store = new Store(__DIR__ . '/../../../data/' . $controller . '.json');

        $this->action = $this->router->getAction();
        $this->view->setContentView(strtolower($controller . DIRECTORY_SEPARATOR . $this->action));
        call_user_func_array(array($this, $this->action . 'Action'), array());
        return $this->view->render();
    }
}