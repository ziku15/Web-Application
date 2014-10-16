<?php
//namespace Biz_mela\Frontend\Models;
namespace Biz_mela\Backend\Controllers;
//namespace Modules\Frontend\Controllers;

//use Biz_mela\Frontend\Models\Lang as Lang;
//use Biz_mela\Frontend\Models\SiteLabes as SiteLabes;


use Biz_mela\Frontend\Models\Delivery as Delivery;

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

        Delivery::find();

//        $users = \Biz_mela\Frontend\Models\UserMaster::find();

    }

}
