<?php
namespace Biz_mela\Backend\Controllers;
use Biz_mela\Models\DeliveryArea as DeliveryArea;
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

class DeliveryAreaController extends \Phalcon\Mvc\Controller {

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
                  ->from('Biz_mela\Models\DeliveryArea')
                  
                  ->columns('*')
                  ->getQuery()
                  ->execute();
         $numberPage = $this->request->getQuery("page", "int", 1);
        $paginator = new Paginator(array(
            "data" => $newResult,
            "limit" => 10,
            "page" => $numberPage
        ));
        $page['deliveryarea'] = $paginator->getPaginate();
       // $page['deliveryarea'] = $newResult;
        
        // $page['value'] = $value;

        $this->view->setVars(array('sub_title' => 'DeliveryAreaManagement'));
        $this->view->setVars($page);

        
        //$this->view->setVars(array('action' => 'Group:Admin'));
      
    }

    public function newAction() {
        $form = new Form();
        $area_name = new Text("area_name", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'area_name',
            'placeholder' => 'Area Name',
            'onkeyup'=>"sync()",
            'autocomplete' => 'off'
        ));
       
        $area_name->addValidator(new PresenceOf(array(
            'message' => 'The Area Name field is required'
        )));
       
       
        
        $form->add($area_name);
       
        $data['form'] = $form;
        
        $this->view->setVars($data);
        if ($this->request->isPost()) {
            
            $area_name = $this->request->getPost('area_name');
            
            
           
            if (!$form->isValid($_POST)) {
                        $this->flash->error("Please solve the following error !!");
                    }
            
               
                else
                {
                    
                    $DeliveryArea = new DeliveryArea();
                    $DeliveryArea->area_name = $area_name;
                    $DeliveryArea->status=1;
                    $DeliveryArea->created_at = date("Y-m-d h:i:s");
                    
                    if ($DeliveryArea->create()) {
                        $this->flash->success("Delivery Area  added successfully!!");
                                
                        return $this->response->redirect('admin/deliveryarea/index/');
                                
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
        $deliveryarea = DeliveryArea::findFirst('id=' . $value);
         $form = new Form();
        $area_name = new Text("area_name", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'area_name',
            'placeholder' => 'Area Name',
            'onkeyup'=>"sync()",
            'autocomplete' => 'off'
        ));
       
        $area_name->addValidator(new PresenceOf(array(
            'message' => 'The Area Name field is required'
        )));
       
        
        $form->add($area_name);
       
        $data['form'] = $form;
        $data['deliveryarea'] = $deliveryarea;
        
        $this->view->setVars($data);
        if ($this->request->isPost()) {
            if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            } else {
                $area_name = $this->request->getPost('area_name');
           
               $DeliveryArea = new DeliveryArea();
               $DeliveryArea->id= $deliveryarea->id;
                    $DeliveryArea->area_name = $area_name;
                    $DeliveryArea->status = 1;
                    $DeliveryArea->created_at = $deliveryarea->created_at;
                    $DeliveryArea->updated_at = date("Y-m-d h:i:s");
                    if ($DeliveryArea->save()) {
                        $this->flash->success("Delivery Area  updated successfully!!");
                        return $this->response->redirect('admin/deliveryarea/index/');
                    } else {
                        $this->flash->error("error occured,please try again later!!");
                    }
                
            }
        }
        //$data['action'] = "Create Press Release";
    }
public function deleteAction($value) {
    $DeliveryArea = DeliveryArea::findFirst('id=' . $value);
    $DeliveryArea->delete();
    $this->response->redirect('admin/deliveryarea');
 }

 public function changestatusAction($value) {
    $DeliveryArea = DeliveryArea::findFirst('id=' . $value);
    if(($DeliveryArea->status)==1)
    {
        $DeliveryArea->status=0;
    }
    else
    {

        $DeliveryArea->status=1;
    }
    $DeliveryArea->save();
    $this->response->redirect('admin/deliveryarea');
 }
 
}
