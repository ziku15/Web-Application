<?php

namespace Biz_mela\Frontend\Controllers;

class HelpController extends ControllerBase
{
    public function initialize()
    {
        //$this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('Help & Support');
        parent::initialize();
    }

    public function indexAction()
    {
    }
}