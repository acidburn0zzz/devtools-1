<?php
/**
 * @date: 25.03.13
 * @time: 15:07
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file: Router.php
 */


namespace Tool;


class Router
{
    /**
     * @var Route[]
     */
    protected $routes;
    /**
     * @var Route
     */
    protected $matchedRoute;

    public function __construct()
    {
        $this->routes  = new ArrayObject();
    }

    public function add($name, $pattern, $methods = [])
    {
        if ($this->routes->has($name)) {
            throw new \Exception(sprintf('Route %s already exists', $name));
        }
        $this->routes->set($name, new Route($name, $pattern, $methods));
        return $this;
    }

    public function match(Request $request)
    {
        foreach($this->routes as $route) {
            if($route->match($request)) {
                return $this->matchedRoute = $route;
            }
        }
        return false;
    }
}