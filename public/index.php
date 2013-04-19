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
        $app->view->setContentView('about');
        if ($app->request->isAjax()) {
            $app->view->setRenderType(\Tool\View::RENDER_VIEW);
        }
    });
$app->addAction('code',
                '/code/:code{\w+}:',
    function (App $app) {
        $app->store->setFileName($app->router->getParam('code'));
        $app->view->set('code', $app->store->load());
        $app->view->setContentView('code');
        $app->view->setRenderType(\Tool\View::RENDER_VIEW);
    });
$app->addAction('php',
                '/php',
    function (App $app) {
        $code = $app->request->request->get('code', '');
        $app->store->save($code);

        ob_start();
        eval('?>' . $code);
        $app->view->set('output', ob_get_contents());
        ob_clean();

    });
$app->addAction('javascript',
                '/javascript',
    function (App $app) {
        $code = $app->request->request->get('code', '');
        $app->store->save($code);

        $app->view->set('output', sprintf('<script>%s</script>', $code));
    });
$app->addAction('sql',
                '/sql',
    function (App $app) {
        $code = $app->request->request->get('code', '');
        $app->store->save($code);
        $app->store->setFileName('config');
        $config = new \Tool\ArrayObject($app->store->load());

        $pdo = new PDO(sprintf('mysql:dbname=%s;host=%s',
                               $config->get('dbName', ''),
                               $config->get('dbHost', 'localhost')), $config->get('dbUsername',
                                                                                  ''), $config->get('dbPassword', ''));

        $statement = $pdo->query($code);
        if ((int)$pdo->errorCode() != 0) {
            $app->view->set('message', current(array_reverse($pdo->errorInfo())));
        } else {
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute();
            $app->view->set('rows', new \Tool\ArrayObject($statement->fetchAll()));
            $app->view->set('output', $app->view->renderView('table'));
        }
    });
$app->addAction('config',
                '/config',
    function (App $app) {
        if ($app->request->isPost()) {
            $app->store->save($app->request->post->getArrayCopy());
        }
        $app->view->set('config', $app->store->load());
    });
$app->notFoundAction(function (App $app) {
    $app->response->setStatus(404);
    $app->view->setContentView('error');
    $app->view->set('message', 'Page not Found');
});

$app->run();

function debug()
{
    call_user_func_array('var_dump', func_get_args());
    die;
}