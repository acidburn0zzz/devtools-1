<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
/**
 * @date: 25.03.13
 * @time: 15:04
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file: index.php
 */
 require_once __DIR__ . '/../library/Tool/Application.php';

echo Tool\Application::factory()->run();

function debug()
{
    call_user_func_array('var_dump',func_get_args());die;
}