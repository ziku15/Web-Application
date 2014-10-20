<?php
namespace Biz_mela\Backend\Controllers;
use Biz_mela\Models\ProductCategory as ProductCategory;
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

class ProductCategoryController extends \Phalcon\Mvc\Controller {

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
                  ->from('Biz_mela\Models\ProductCategory')
                  ->where('Biz_mela\Models\ProductCategory.status=1')
                  ->columns('*')
                  ->getQuery()
                  ->execute();
        $page['productcategory'] = $newResult;
        
        // $page['value'] = $value;

        $this->view->setVars(array('sub_title' => 'ProductCategoryManagement'));
        $this->view->setVars($page);

        
        //$this->view->setVars(array('action' => 'Group:Admin'));
      
    }

    public function newAction() {
        $form = new Form();
        $cat_name = new Text("cat_name", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'cat_name',
            'placeholder' => 'Email',
            'onkeyup'=>"sync()",
            'autocomplete' => 'off'
        ));
        $parent_id = new Text("parent_id", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'parent_id',
            'placeholder' => 'parent_id',
            'onkeyup'=>"sync()",
            'autocomplete' => 'off'
        ));
        $cat_name->addValidator(new PresenceOf(array(
            'message' => 'The Category Name field is required'
        )));
        $parent_id->addValidator(new PresenceOf(array(
            'message' => 'The Parent ID field is required'
        )));
        $newResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductCategory')
                  ->where('Biz_mela\Models\ProductCategory.status=1')
                  ->andwhere('Biz_mela\Models\ProductCategory.parent_id=0')
                  ->columns('*')
                  ->getQuery()
                  ->execute();
        
        $form->add($cat_name);
        $form->add($parent_id);
        $data['form'] = $form;
        $data['productcategory'] = $newResult;
        $this->view->setVars($data);
        if ($this->request->isPost()) {
            
            $cat_name = $this->request->getPost('cat_name');
            $parent_id = $this->request->getPost('parent_id');
            
           
            if (!$form->isValid($_POST)) {
                        $this->flash->error("Please solve the following error !!");
                    }
            
               
                else
                {
                    
                    $ProductCategory = new ProductCategory();
                    $ProductCategory->cat_name = $cat_name;
                    $ProductCategory->parent_id = $parent_id;
                    $ProductCategory->status=1;
                    $ProductCategory->created_at = date("Y-m-d h:i:s");
                    
                    if ($ProductCategory->create()) {
                        $this->flash->success("Product Category  added successfully!!");
                                
                        return $this->response->redirect('admin/productcategory/index/');
                                
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
        $productcategory = ProductCategory::findFirst('id=' . $value);
         $form = new Form();
        $cat_name = new Text("cat_name", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'cat_name',
            'placeholder' => 'Email',
            'onkeyup'=>"sync()",
            'autocomplete' => 'off'
        ));
        $parent_id = new Text("parent_id", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'parent_id',
            'placeholder' => 'parent_id',
            'onkeyup'=>"sync()",
            'autocomplete' => 'off'
        ));
        $cat_name->addValidator(new PresenceOf(array(
            'message' => 'The Category Name field is required'
        )));
        $parent_id->addValidator(new PresenceOf(array(
            'message' => 'The Parent ID field is required'
        )));
        $newResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductCategory')
                  ->where('Biz_mela\Models\ProductCategory.status=1')
                  ->andwhere('Biz_mela\Models\ProductCategory.parent_id=0')
                  ->columns('*')
                  ->getQuery()
                  ->execute();
        
        $form->add($cat_name);
        $form->add($parent_id);
        $data['form'] = $form;
        $data['productcategory'] = $productcategory;
        $data['productcategories'] = $newResult;
        $this->view->setVars($data);
        if ($this->request->isPost()) {
            if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            } else {
                $cat_name = $this->request->getPost('cat_name');
            $parent_id = $this->request->getPost('parent_id');
               $ProductCategory = new ProductCategory();
               $ProductCategory->id= $productcategory->id;
                    $ProductCategory->cat_name = $cat_name;
                    $ProductCategory->parent_id = $parent_id;
                    $ProductCategory->status=1;
                    $ProductCategory->updated_at = date("Y-m-d h:i:s");
                    if ($ProductCategory->save()) {
                        $this->flash->success("Product Category  updated successfully!!");
                        return $this->response->redirect('admin/productcategory/index/');
                    } else {
                        $this->flash->error("error occured,please try again later!!");
                    }
                
            }
        }
        //$data['action'] = "Create Press Release";
    }
public function deleteAction($value) {
    $User = ProductCategory::findFirst('id=' . $value);
    $User->delete();
    $this->response->redirect('admin/productcategory');
 }
 
}
