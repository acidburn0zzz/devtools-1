<?php
/**
 * @date: 17.04.13
 * @time: 15:18
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file: Route.php
 */


namespace Tool;


class Route
{
    const CONSTRAIN_DEFAULT = '[a-z][a-zA-Z0-9_-]';
    /**
     * @var string
     */
    protected $name;
    /**
     * @var
     */
    protected $pattern;
    /**
     * @var string
     */
    protected $expression;
    /**
     * @var
     */
    protected $constrains;
    /**
     * @var string
     */
    protected $methods;
    /**
     * @var array
     */
    protected $matches;

    public function __construct($name, $pattern)
    {
        $this->setName($name);
        $this->setPattern($pattern);
    }

    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        $this->constrains = array();
        $matches       = array();
        $replaces      = array(array(), array());
        if (preg_match('/^\(([a-z\:]+)\)/', $pattern, $matches)) {
            $this->methods = explode(':', $matches[1]);
            $replaces[0][] = $matches[0];
            $replaces[1][] = '';
        } else {
            $this->methods = array();
        }
        if (preg_match_all('/:([a-z]+)({(.+)})?(\?)?:/', $pattern, $matches)) {
            foreach ($matches[1] as $index => $constrain) {
                $mode = $matches[4][$index];
                $regexp                       = $matches[3][$index] === '' ? self::CONSTRAIN_DEFAULT : $matches[3][$index];
                $this->constrains[$constrain] = $regexp;
                $replaces[0][]                = $matches[0][$index];
                $replaces[1][]                = sprintf('%1$s(?<%2$s>%3$s)%1$s',$mode,$constrain,$regexp);
            }
        }
        $this->expression = sprintf('#%s/?#',str_replace($replaces[0], $replaces[1], $pattern));
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function match(Request $request)
    {
        return (!count($this->methods) || $request->isRequestMethods($this->methods)) && preg_match($this->expression, $request->getURI(), $this->matches);
    }

    public function getParams()
    {
        return array_intersect_key($this->matches, $this->constrains);
    }
}