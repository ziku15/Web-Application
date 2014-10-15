<?php
namespace Biz_mela\Backend\Controllers;
class HomeController extends \Phalcon\Mvc\Controller
{

    public function initialize()
    {
        $auth = $this->session->get('auth');
        if (!$auth) {
            $this->response->redirect('auth/login/');
        } 

        $this->view->setVars(array('title'=>'Home'));
    }

    public function indexAction()
    {
    	
    }

    public function TestAction($value='')
    {
    	// echo "ok";
    	$data = Announcement::find();
    	// print_r($data[0]->title); exit();
    }

    public function logoutAction()
    {
         $this->session->destroy();
         $this->response->redirect('auth/login/');
    }

}

