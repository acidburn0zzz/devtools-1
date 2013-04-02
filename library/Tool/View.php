<?php
/**
 * @date: 25.03.13
 * @time: 15:38
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file: View.php
 */


namespace Tool;


class View
{

    const RENDER_HTML = 'html';
    const RENDER_JSON = 'json';
    const RENDER_XML  = 'xml';

    private $viewDir;
    private $vars = array();
    private $contentView;

    private $renderType = self::RENDER_HTML;

    public function __construct()
    {
        $this->viewDir = realpath(__DIR__ . '/../../view');
    }

    public function setContentView($contentView)
    {
        $this->contentView = $contentView;
    }

    public function setRenderType($renderType)
    {
        $this->renderType = $renderType;
    }

    public function set($name, $value)
    {
        $this->vars[$name] = $value;
    }

    public function renderView($view)
    {
        ob_start();
        include realpath($this->viewDir) . DIRECTORY_SEPARATOR . $view . '.phtml';
        $layout = ob_get_contents();
        ob_clean();

        return $layout;
    }

    public function render()
    {
        switch ($this->renderType) {
            case self::RENDER_HTML:
                return $this->renderView('layout');
                break;
            case self::RENDER_JSON:
                header('Content-Type:application/json;charset=UTF-8');
                return json_encode($this->vars);
                break;
            case self::RENDER_XML:
                return json_encode($this->vars);
                break;
            default:

        }

    }

    public function getContent()
    {
        return $this->renderView($this->contentView);
    }

    public function __set($name, $value)
    {
        $this->vars[$name] = $value;
    }

    public function __get($name)
    {
        return array_key_exists($name, $this->vars) ? $this->vars[$name] : null;
    }


}