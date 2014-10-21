<?php
namespace Biz_mela\Backend\Controllers;
use Biz_mela\Models\PaymentMethod as PaymentMethod;
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
    use Phalcon\Paginator\Adapter\Model as Paginator;

class PaymentMethodController extends \Phalcon\Mvc\Controller {

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
                  ->from('Biz_mela\Models\PaymentMethod')
                  
                  ->columns('*')
                  ->getQuery()
                  ->execute();
        $numberPage = $this->request->getQuery("page", "int", 1);
        $paginator = new Paginator(array(
            "data" => $newResult,
            "limit" => 10,
            "page" => $numberPage
        ));
        $page['paymentmethod'] = $paginator->getPaginate();
        //$page['paymentmethod'] = $newResult;
        
        // $page['value'] = $value;

        $this->view->setVars(array('sub_title' => 'PaymentMethodManagement'));
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
                    
                    $PaymentMethod = new PaymentMethod();
                    $PaymentMethod->method_name = $method_name;
                    $PaymentMethod->status=1;
                    $PaymentMethod->created_at = date("Y-m-d h:i:s");
                    
                    if ($PaymentMethod->create()) {
                        $this->flash->success("Payment Method Area  added successfully!!");
                                
                        return $this->response->redirect('admin/paymentmethod/index/');
                                
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
        $paymentmethod = PaymentMethod::findFirst('id=' . $value);
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
        $data['paymentmethod'] = $paymentmethod;
        
        $this->view->setVars($data);
        if ($this->request->isPost()) {
            if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            } else {
                $method_name = $this->request->getPost('method_name');
           
               $PaymentMethod = new PaymentMethod();
               $PaymentMethod->id= $paymentmethod->id;
                    $PaymentMethod->method_name = $method_name;
                    $PaymentMethod->status = 1;
                    $PaymentMethod->created_at = $paymentmethod->created_at;
                    $PaymentMethod->updated_at = date("Y-m-d h:i:s");
                    if ($PaymentMethod->save()) {
                        $this->flash->success("Delivery Area  updated successfully!!");
                        return $this->response->redirect('admin/paymentmethod/index/');
                    } else {
                        $this->flash->error("error occured,please try again later!!");
                    }
                
            }
        }
        //$data['action'] = "Create Press Release";
    }
public function deleteAction($value) {
    $PaymentMethod = PaymentMethod::findFirst('id=' . $value);
    $PaymentMethod->delete();
    $this->response->redirect('admin/paymentmethod');
 }
 public function changestatusAction($value) {
    $PaymentMethod = PaymentMethod::findFirst('id=' . $value);
    if(($PaymentMethod->status)==1)
    {
        $PaymentMethod->status=0;
    }
    else
    {

        $PaymentMethod->status=1;
    }
    $PaymentMethod->save();
    $this->response->redirect('admin/paymentmethod');
 }
 
}
