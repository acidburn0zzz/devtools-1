<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/**
 * @date: 25.03.13
 * @time: 15:04
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file: index.php
 */
require_once __DIR__ . '/../library/Tool/Application.php';
use Tool\Application as App;

$app = App::factory(realpath(__DIR__ . '/../'));

$app->addAction('default',
                '/',
    function (App $app) {
        $app->view->setContentView('code');

    });

$app->addAction('php', '(post)/php',
    function (App $app) {
        
    });
$app->addAction('php', '(get)/php',
    function (App $app) {

    });
$app->postAction(function (App $app) {
    if ($app->request->isAjax()) {
        $app->view->setRenderType(\Tool\View::RENDER_JSON);
    }
});

$app->run();

function debug()
{
    call_user_func_array('var_dump', func_get_args());
    die;
}