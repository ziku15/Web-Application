<?php
namespace Biz_mela\Backend\Controllers;
use Biz_mela\Models\Brand as Brand;
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

class BrandController extends \Phalcon\Mvc\Controller {

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
                  ->from('Biz_mela\Models\Brand')
                  
                  ->columns('*')
                  ->getQuery()
                  ->execute();
         $numberPage = $this->request->getQuery("page", "int", 1);
        $paginator = new Paginator(array(
            "data" => $newResult,
            "limit" => 10,
            "page" => $numberPage
        ));
        $page['brand'] = $paginator->getPaginate();
       // $page['brand'] = $newResult;
        
        // $page['value'] = $value;

        $this->view->setVars(array('sub_title' => 'BrandManagement'));
        $this->view->setVars($page);

        
        //$this->view->setVars(array('action' => 'Group:Admin'));
      
    }

    public function newAction() {
        $form = new Form();
        $brand_name = new Text("brand_name", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'brand_name',
            'placeholder' => 'Brand Name',
            'onkeyup'=>"sync()",
            'autocomplete' => 'off'
        ));
       
        $brand_name->addValidator(new PresenceOf(array(
            'message' => 'The Brand Name field is required'
        )));
       
       
        
        $form->add($brand_name);
       
        $data['form'] = $form;
        
        $this->view->setVars($data);
        if ($this->request->isPost()) {
            
            $brand_name = $this->request->getPost('brand_name');
            
            
           
            if (!$form->isValid($_POST)) {
                        $this->flash->error("Please solve the following error !!");
                    }
            
               
                else
                {
                    
                    $Brand = new Brand();
                    $Brand->brand_name = $brand_name;
                    $Brand->status=1;
                    $Brand->created_at = date("Y-m-d h:i:s");
                    
                    if ($Brand->create()) {
                        $this->flash->success("Brand  added successfully!!");
                                
                        return $this->response->redirect('admin/brand/index/');
                                
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
        $brand = Brand::findFirst('id=' . $value);
         $form = new Form();
        $brand_name = new Text("brand_name", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'brand_name',
            'placeholder' => 'Brand Name',
            'onkeyup'=>"sync()",
            'autocomplete' => 'off'
        ));
       
        $brand_name->addValidator(new PresenceOf(array(
            'message' => 'The Brand Name field is required'
        )));
       
        
        $form->add($brand_name);
       
        $data['form'] = $form;
        $data['brand'] = $brand;
        
        $this->view->setVars($data);
        if ($this->request->isPost()) {
            if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            } else {
                $brand_name = $this->request->getPost('brand_name');
           
               $Brand = new Brand();
               $Brand->id= $brand->id;
                    $Brand->brand_name = $brand_name;
                    $Brand->status = 1;
                    $Brand->created_at = $brand->created_at;
                    $Brand->updated_at = date("Y-m-d h:i:s");
                    if ($Brand->save()) {
                        $this->flash->success("Brand  updated successfully!!");
                        return $this->response->redirect('admin/brand/index/');
                    } else {
                        $this->flash->error("error occured,please try again later!!");
                    }
                
            }
        }
        //$data['action'] = "Create Press Release";
    }
public function deleteAction($value) {
    $Brand = Brand::findFirst('id=' . $value);
    $Brand->delete();
    $this->response->redirect('admin/brand');
 }

  public function changestatusAction($value) {
    $Brand = Brand::findFirst('id=' . $value);
    if(($Brand->status)==1)
    {
        $Brand->status=0;
    }
    else
    {

        $Brand->status=1;
    }
    $Brand->save();
    $this->response->redirect('admin/brand');
 }
 
}
