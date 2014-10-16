<?php

namespace Biz_mela\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function initialize()
    {
        $this->view->setTemplateBefore('main');
    }

    /**
     * This class checks if the class has an annotation called "Private" denoting the
     * class is private and the user needs to be authenticated
     */
    public function beforeExecuteRoute()
    {
        $annotations = $this->annotations->get($this)->getClassAnnotations();
        if ($annotations) {
            if ($annotations->has('Private')) {
                if (!$this->session->has('identity')) {
                    $this->flash->notice('You don\'t have access to this option');
                    $this->dispatcher->forward(array('controller' => 'session', 'action' => 'index'));
                    return false;
                }
            }
        }
    }

}