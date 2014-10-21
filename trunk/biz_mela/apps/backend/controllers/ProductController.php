<?php
namespace Biz_mela\Backend\Controllers;
use Biz_mela\Models\ProductMaster as ProductMaster;
use Biz_mela\Models\ShopMaster as ShopMaster;
use Biz_mela\Models\ProductImage as ProductImage;
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

class ProductController extends \Phalcon\Mvc\Controller {

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
                  ->from('Biz_mela\Models\ProductMaster')
                  ->where('Biz_mela\Models\ProductMaster.status=1')
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
        
        // $page['value'] = $value;

        $this->view->setVars(array('sub_title' => 'ProductManagement'));
        $this->view->setVars($page);

        
        //$this->view->setVars(array('action' => 'Group:Admin'));
      
    }

    

        public function updateAction($value) {
        $User = ProductMaster::findFirst('id=' . $value);
        $User->status=0;
        $User->save();

        $this->response->redirect('admin/product');
    }

     public function detailAction($value) {
         $newResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductMaster')
                  ->columns('Biz_mela\Models\ProductMaster.id,Biz_mela\Models\ProductMaster.shop_id,Biz_mela\Models\ProductMaster.cat_id,Biz_mela\Models\ProductMaster.sku,Biz_mela\Models\ProductMaster.product_name,Biz_mela\Models\ProductMaster.product_description,Biz_mela\Models\ProductMaster.price,Biz_mela\Models\ProductMaster.discount,Biz_mela\Models\ProductMaster.in_stock,discount,Biz_mela\Models\ProductMaster.type,Biz_mela\Models\ProductMaster.uom,Biz_mela\Models\ProductMaster.minimum_order_level,Biz_mela\Models\ProductMaster.status,Biz_mela\Models\ProductMaster.is_hot,Biz_mela\Models\ProductMaster.updated_at,Biz_mela\Models\ProductMaster.created_at,p.picture,s.shop_name')
                  ->where('Biz_mela\Models\ProductMaster.id='.$value)
                  ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
                  ->leftJoin('Biz_mela\Models\ShopMaster', 's.id = Biz_mela\Models\ProductMaster.shop_id', 's')
                  
                  ->getQuery()
                  ->execute();
        $page['product'] = $newResult;
        $this->view->setVars(array('sub_title' => 'ProductManagement'));
        $this->view->setVars($page);
    }


}
