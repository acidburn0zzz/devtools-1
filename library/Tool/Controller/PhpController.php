<?php
/**
 * @date: 25.03.13
 * @time: 15:31
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file: JsController.php
 */


namespace Tool\Controller;

use Tool\View;

class PhpController extends AbstractController
{

    public function indexAction()
    {
        if ($this->request->isAjax()) {
            $code = $this->request->getRequest('code', '');
            ob_start();
            eval('?>' . $code);
            $this->view->output = ob_get_contents();
            ob_clean();
            $this->store->save($code);
            $this->view->setRenderType(View::RENDER_JSON);
        } else {
            $this->view->code = $this->store->load();
        }
    }

}