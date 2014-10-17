<?php
namespace Biz_mela\Backend\Controllers;
class IndexController extends ControllerBase {

public function initialize() {
    $this->auth = $auth = $this->session->get('auth');
    if (!$auth) {
        $this->response->redirect('admin/auth/login/');
    }

    $this->view->setVars(array('title' => 'Bizmela Admin Homepage'));
}

    public function indexAction() 
    {
       
      
    }

}
