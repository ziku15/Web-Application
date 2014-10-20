<?php
namespace Biz_mela\Backend\Controllers;
use Biz_mela\Models\DeliveryMethod as DeliveryMethod;
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

class DeliveryMethodController extends \Phalcon\Mvc\Controller {

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
                  ->from('Biz_mela\Models\DeliveryMethod')
                  
                  ->columns('*')
                  ->getQuery()
                  ->execute();
        $page['deliverymethod'] = $newResult;
        
        // $page['value'] = $value;

        $this->view->setVars(array('sub_title' => 'DeliveryMethodManagement'));
        $this->view->setVars($page);

        
        //$this->view->setVars(array('action' => 'Group:Admin'));
      
    }

    public function newAction() {
        $form = new Form();
        $method_name = new Text("method_name", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'method_name',
            'placeholder' => 'Method Name',
            'onkeyup'=>"sync()",
            'autocomplete' => 'off'
        ));
       
        $method_name->addValidator(new PresenceOf(array(
            'message' => 'The Method Name field is required'
        )));
       
       
        
        $form->add($method_name);
       
        $data['form'] = $form;
        
        $this->view->setVars($data);
        if ($this->request->isPost()) {
            
            $method_name = $this->request->getPost('method_name');
            
            
           
            if (!$form->isValid($_POST)) {
                        $this->flash->error("Please solve the following error !!");
                    }
            
               
                else
                {
                    
                    $DeliveryMethod = new DeliveryMethod();
                    $DeliveryMethod->method_name = $method_name;
                    $DeliveryMethod->status=1;
                    $DeliveryMethod->created_at = date("Y-m-d h:i:s");
                    
                    if ($DeliveryMethod->create()) {
                        $this->flash->success("Delivery Method  added successfully!!");
                                
                        return $this->response->redirect('admin/deliverymethod/index/');
                                
                        //return $this->response->redirect('user/passwordconfirm/');
                        //exit();
                    } else {
                        $this->flash->error("error occured,please try again later!!");
                    }
                        /*$UserId=$User->id;
                        $to=$email;
                        $subject="Email verification";
                        $body='Hi, <br/> <br/> Please verify your email and get started using your Website account.Your password is: '.$password.'
                        <br/> <br/> <a href="http://localhost/sidra/user/passwordconfirm/'.$User->id.'">Click Here To Confirm</a>' ;
                        $this->Send_Mail($to,$subject,$body,$UserId);   */  
                }
            }
        }

        public function updateAction($value) {
        $deliverymethod = DeliveryMethod::findFirst('id=' . $value);
         $form = new Form();
        $method_name = new Text("method_name", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'method_name',
            'placeholder' => 'Method Name',
            'onkeyup'=>"sync()",
            'autocomplete' => 'off'
        ));
       
        $method_name->addValidator(new PresenceOf(array(
            'message' => 'The Method Name field is required'
        )));
       
        
        $form->add($method_name);
       
        $data['form'] = $form;
        $data['deliverymethod'] = $deliverymethod;
        
        $this->view->setVars($data);
        if ($this->request->isPost()) {
            if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            } else {
                $method_name = $this->request->getPost('method_name');
           
               $DeliveryMethod = new DeliveryMethod();
               $DeliveryMethod->id= $deliverymethod->id;
                    $DeliveryMethod->method_name = $method_name;
                    $DeliveryMethod->status = 1;
                    $DeliveryMethod->created_at = $deliverymethod->created_at;
                    $DeliveryMethod->updated_at = date("Y-m-d h:i:s");
                    if ($DeliveryMethod->save()) {
                        $this->flash->success("Delivery Method  updated successfully!!");
                        return $this->response->redirect('admin/deliverymethod/index/');
                    } else {
                        $this->flash->error("error occured,please try again later!!");
                    }
                
            }
        }
        //$data['action'] = "Create Press Release";
    }
public function deleteAction($value) {
    $DeliveryMethod = DeliveryMethod::findFirst('id=' . $value);
    $DeliveryMethod->delete();
    $this->response->redirect('admin/deliverymethod');
 }

 public function changestatusAction($value) {
    $DeliveryMethod = DeliveryMethod::findFirst('id=' . $value);
    if(($DeliveryMethod->status)==1)
    {
        $DeliveryMethod->status=0;
    }
    else
    {

        $DeliveryMethod->status=1;
    }
    $DeliveryMethod->save();
    $this->response->redirect('admin/deliverymethod');
 }
 
}
