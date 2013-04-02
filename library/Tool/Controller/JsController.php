<?php
/**
 * @date: 25.03.13
 * @time: 15:31
 * @author: Ivan Zaharchenko ( 3axap4eHko@gmail.com )
 * @file: JsController.php
 */
 

namespace Tool\Controller;

use Tool\View;

class JsController extends AbstractController {

    public function indexAction()
    {
        if($this->request->isAjax())
        {
            $this->store->save($_REQUEST['code']);
            $this->view->setRenderType(View::RENDER_JSON);
        }else{
            $this->view->code = $this->store->load();
        }
    }

}