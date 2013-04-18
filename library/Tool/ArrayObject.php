<?php
/**
 * @date: 17.04.13
 * @time: 11:12
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file: ArrayObject.php
 */


namespace Tool;


class ArrayObject extends \ArrayObject
{
    /**
     * @var callable
     */
    protected $filterOutput;

    /**
     * @param mixed $name
     *
     * @return bool
     */
    public function has($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * @param mixed      $name
     * @param null|mixed $default
     *
     * @return mixed|null
     */
    public function get($name, $default = null)
    {
        $value = $this->has($name) ? $this->offsetGet($name) : $default;

        return is_callable($this->filterOutput) ? call_user_func_array($this->filterOutput, array($value)) : $value;
    }

    /**
     * @param mixed $name
     * @param mixed $value
     *
     * @return $this
     */
    public function set($name, $value)
    {
        $this->offsetSet($name, $value);

        return $this;
    }

    /**
     * @param callable $filter
     *
     * @return $this
     */
    public function setFilter($filter)
    {
        $this->filterOutput = $filter;

        return $this;
    }

    public function merge()
    {
        $args = func_get_args();
        array_unshift($args,$this->getArrayCopy());
        $this->exchangeArray(call_user_func_array('array_merge',$args));
        return $this;
    }

    public function clear()
    {
        $this->exchangeArray(array());
        gc_collect_cycles();
        return $this;
    }
}