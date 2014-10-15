<?php

namespace Biz_mela\Frontend\Controllers;

use Phalcon\Tag as Tag;

class BuyController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('buyaccount');
        //Tag::setTitle('BuyerAccount');
        //parent::initialize();
		$this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            return $this->response->redirect('session/start/');
        }

        $this->view->setVars(array('title' => 'Buyer Account'));
    }

    public function indexAction()
    {
        /*if (!$this->request->isPost()) {
            Tag::setDefault('email', 'demo@phalconphp.com');
            Tag::setDefault('password', 'phalcon');
        }*/
    }
	}