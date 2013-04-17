<?php
/**
 * @date: 25.03.13
 * @time: 15:06
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file: Application.php
 */


namespace Tool;


class Application
{
    const ACTION_NOT_FOUND = 'notFound';

    /**
     * @var Request
     */
    public $request;

    /**
     * @var Router
     */
    public $router;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var View
     */
    protected $view;

    /**
     * @var ArrayObject
     */
    protected $actions;

    protected $options = array(
        'applicationDir' => __DIR__
    );

    public function __construct($options)
    {
        spl_autoload_register(function ($className) {
            require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('\\',
                                                                              DIRECTORY_SEPARATOR,
                                                                              $className) . '.php';
        });
        $this->setOptions($options);
        $this->init();
    }

    protected function setOptions()
    {

    }

    final protected function init()
    {
        $this->request  = new Request();
        $this->router   = new Router();
        $this->response = new Response();
        $this->view     = new View($applicationDir . '/view');
        $this->actions  = new ArrayObject();
    }

    /**
     * @return Application
     */
    public static function factory()
    {
        $reflection = new \ReflectionClass(get_called_class());

        return $reflection->newInstanceArgs(func_get_args());
    }

    /**
     * @param string   $name
     * @param string   $pattern
     * @param callable $action
     *
     * @return $this
     */
    public function addAction($name, $pattern, $action)
    {
        $this->router->add($name, $pattern);
        $this->actions->set($name, $action);

        return $this;
    }

    /**
     * @param callable $action
     *
     * @return $this
     */
    public function notFoundAction($action)
    {
        $this->actions->set(self::ACTION_NOT_FOUND, $action);
        return $this;
    }

    public function run()
    {
        $route = $this->router->match($this->request);
        if ($route === false) {
            $actionName = self::ACTION_NOT_FOUND;
        } else {
            $actionName = $route->getName();
        }
        if(!$this->actions->has($actionName)) {
            throw new \Exception(sprintf('Action %s not found', $actionName));
        }
        if($this->request->isAjax()) {
            $this->view->setRenderType(View::RENDER_JSON);
        }
        $action = $this->actions->get($actionName);
        call_user_func_array($action, array($this));
        \debug($this->view);
        $this->response->setContent($this->view->render());
        $this->response->send();
    }
}