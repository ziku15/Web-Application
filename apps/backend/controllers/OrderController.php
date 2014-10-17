<?php
namespace Biz_mela\Backend\Controllers;
use Biz_mela\Models\OrderMaster as OrderMaster;
use Phalcon\Mvc\View,
    Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\Password,
    Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\StringLength,
	Phalcon\Mvc\Model\Validator\Email,
    Phalcon\Paginator\Adapter\QueryBuilder;

class OrderController extends \Phalcon\Mvc\Controller {

public function initialize() {
    $this->auth = $auth = $this->session->get('auth');
    if (!$auth) {
        $this->response->redirect('admin/auth/login/');
    }

    $this->view->setVars(array('title' => 'Bizmela Admin Homepage'));
}

    public function indexAction() 
    {
         $newResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\OrderMaster')
                  ->where('Biz_mela\Models\OrderMaster.status=1')
                  ->columns('*')
                  ->getQuery()
                  ->execute();
        $page['order'] = $newResult;
        
        // $page['value'] = $value;

        $this->view->setVars(array('sub_title' => 'OrderManagement'));
        $this->view->setVars($page);

        
        //$this->view->setVars(array('action' => 'Group:Admin'));
      
    }

    

        public function updateAction($value) {
        $User = OrderMaster::findFirst('id=' . $value);
        $User->status=0;
        $User->save();

        $this->response->redirect('admin/order');
    }


}
