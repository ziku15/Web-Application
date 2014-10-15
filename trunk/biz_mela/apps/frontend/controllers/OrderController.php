<?php

namespace Biz_mela\Frontend\Controllers;

use Phalcon\Tag as Tag,
Phalcon\Forms\Form,
Phalcon\Forms\Element\Text,
Phalcon\Forms\Element\Password,
Phalcon\Validation\Validator\PresenceOf;

class WalletController extends ControllerBase
{
	public function initialize()
    {
		$this->view->setTemplateAfter('myaccount');
		$this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            return $this->response->redirect('session/start/');
        }

        $this->view->setVars(array('title' => 'My Orders'));
        
        
    }
	
	public function listAction()
	{
	
		
	
	
	
	
	
	
	}
	
	













}