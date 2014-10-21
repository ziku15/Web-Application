<?php
namespace Biz_mela\Backend\Controllers;
use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\ShopMaster as ShopMaster;
use Biz_mela\Models\ProductMaster as ProductMaster;
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

class UserController extends \Phalcon\Mvc\Controller {

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
                  ->from('Biz_mela\Models\UserMaster')
                  ->columns('*')
                  ->getQuery()
                  ->execute();
        $numberPage = $this->request->getQuery("page", "int", 1);
        $paginator = new Paginator(array(
            "data" => $newResult,
            "limit" => 10,
            "page" => $numberPage
        ));
        // $page['value'] = $value;
        $page['user'] = $paginator->getPaginate();
        
        // $page['value'] = $value;

        $this->view->setVars(array('sub_title' => 'UserManagement'));
        $this->view->setVars($page);

        
        //$this->view->setVars(array('action' => 'Group:Admin'));
      
    }

   
 public function changestatusAction($value) {
    $UserMaster = UserMaster::findFirst('id=' . $value);
    if(($UserMaster->status)==1)
    {
        $UserMaster->status=0;
    }
    else
    {

        $UserMaster->status=1;
    }
    $UserMaster->save();
    $this->response->redirect('admin/user');
 }

 public function changestatus_shopAction($value) {
    //echo $value;exit();
    $ShopMaster = ShopMaster::findFirst('id=' . $value);
    if(($ShopMaster->status)==1)
    {
        $ShopMaster->status=0;
    }
    else
    {

        $ShopMaster->status=1;
    }
    $ShopMaster->save();
    $this->response->redirect('admin/user/shop/'.$ShopMaster->user_id);
 }

  public function shopAction($value) {
   $newResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ShopMaster')
                  ->where('user_id='.$value)
                  ->columns('*')
                  ->getQuery()
                  ->execute();
         $numberPage = $this->request->getQuery("page", "int", 1);
        $paginator = new Paginator(array(
            "data" => $newResult,
            "limit" => 10,
            "page" => $numberPage
        ));
        $page['shop'] = $paginator->getPaginate();
        $page['id']=$value;
       // $page['shop'] = $newResult;
        
        // $page['value'] = $value;

        $this->view->setVars(array('sub_title' => 'UserManagement'));
        $this->view->setVars($page);

        
 }

 public function productAction($value) {
   $newResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductMaster')
                  ->where('shop_id='.$value)
                  ->columns('*')
                  ->getQuery()
                  ->execute();
        $numberPage = $this->request->getQuery("page", "int", 1);
        $paginator = new Paginator(array(
            "data" => $newResult,
            "limit" => 10,
            "page" => $numberPage
        ));
        $page['product'] = $paginator->getPaginate();
        $page['id']=$value;
        //$page['product'] = $newResult;
        
        // $page['value'] = $value;

        $this->view->setVars(array('sub_title' => 'UserManagement'));
        $this->view->setVars($page);

        
 }

}
