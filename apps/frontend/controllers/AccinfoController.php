<?php

namespace Biz_mela\Frontend\Controllers;
use Biz_mela\Frontend\Models\UserMaster as UserMaster;

use Phalcon\Tag as Tag;

class AccinfoController extends ControllerBase
{
    public function initialize()
    {
		$this->view->setTemplateAfter('myaccount');
		$this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            return $this->response->redirect('session/start/');
        }

        $this->view->setVars(array('title' => 'Account Information'));
        
        //Tag::setTitle('Account Information');
        //parent::initialize();
    }

    public function indexAction()
    {
		
	}
	
	
	
	
	public function accinfoAction() {
		$username = $this->session->get('auth');
		$con=UserMaster::findFirst("username="."'".$username['name']."'");
		//$con=UserMaster::findFirst("username="."'".$user['name']."'" AND "email="."'".$user['email']."'");
		$data['value']=$con;
		$this->view->setVar('data',$data);	
    }
	
}