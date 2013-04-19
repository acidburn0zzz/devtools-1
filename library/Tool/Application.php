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
    public $response;

    /**
     * @var View
     */
    public $view;

    /**
     * @var Store
     */
    public $store;

    /**
     * @var ArrayObject
     */
    protected $actions;

    /**
     * @var ArrayObject
     */
    protected $options;
    /**
     * @var callable
     */
    protected $postAction;

    public function __construct($appDir)
    {
        spl_autoload_register(function ($className) {
            require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('\\',
                                                                              DIRECTORY_SEPARATOR,
                                                                              $className) . '.php';
        });
        $this->options = new ArrayObject();
        $this->options->set('appDir', rtrim($appDir, '/\\'));
        $this->init();
    }

    final protected function init()
    {
        set_include_path($this->options->get('appDir'));
        $this->request  = new Request();
        $this->router   = new Router();
        $this->response = new Response();
        $this->view     = new View($this->options->get('appDir') . '/view');
        $this->store    = new Store($this->options->get('appDir') . '/data');
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

    /**
     * @param callable $postAction
     *
     * @return $this
     */
    public function postAction($postAction)
    {
        $this->postAction = $postAction;

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
        if (!$this->actions->has($actionName)) {
            throw new \Exception(sprintf('Action %s not found', $actionName));
        }
        $this->store->setFileName($actionName);
        if ($this->request->isAjax()) {
            $this->view->setRenderType(View::RENDER_JSON);
        } else {
            $this->view->setContentView('error');
        }
        $action = $this->actions->get($actionName);
        call_user_func_array($action, array($this));
        if (is_callable($this->postAction)) {
            call_user_func_array($this->postAction, array($this));
        }
        $this->response->setContent($this->view->render());
        $this->response->send();
    }
}