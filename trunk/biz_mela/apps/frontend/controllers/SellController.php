<?php

namespace Biz_mela\Frontend\Controllers;

use Phalcon\Tag as Tag;

class SellController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('myaccount');
        /*Tag::setTitle('SellerAccount');
        parent::initialize();*/
		$this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            return $this->response->redirect('session/start/');
        }

        $this->view->setVars(array('title' => 'Seller Account'));
    }

    public function indexAction()
    {
        
    }
}