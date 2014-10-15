<?php

class HomeController extends \Phalcon\Mvc\Controller
{


	public function initialize()
    {
        $auth = $this->session->get('auth');
        if (!$auth) {
            $this->response->redirect('seesion/start/');
        } 

        $this->view->setVars(array('title'=>'Home'));
    }

    public function indexAction()
    {
    	
    }

    public function logoutAction()
    {
         $this->session->destroy();
         $this->response->redirect('session/start/');
    }

}

