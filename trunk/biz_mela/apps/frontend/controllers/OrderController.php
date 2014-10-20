<?php

namespace Biz_mela\Frontend\Controllers;

use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\OrderMaster as OrderMaster;
use Biz_mela\Models\OrderDetails as OrderDetails;
use Biz_mela\Models\ProductMaster as ProductMaster;



use Phalcon\Tag as Tag,
Phalcon\Forms\Form,
Phalcon\Forms\Element\Text,
Phalcon\Forms\Element\Password,
Phalcon\Validation\Validator\PresenceOf;

class OrderController extends ControllerBase
{
	public function initialize()
    {
		$this->view->setTemplateAfter('myaccount');
		$this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            return $this->response->redirect('session/start/');
        }

        $this->view->setVars(array('title' => 'My Orders'));
        
        
    }
	
	public function orderAction()
	{
	
	
	
	}


	public function listAction()
	{
		/*$user_id = $this->session->get('auth')['id'];
		//print_r($user_id);exit();

		$newResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductMaster')
                  ->columns('Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
                  ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
                  ->orderBy('Biz_mela\Models\ProductMaster.created_at desc')
                  ->limit(4)
                  ->getQuery()
                  ->execute();

        $this->view->setVar(newResult,$newResult);*/
	

		$username = $this->session->get('auth');
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
		$user_id=$con->id;
		$order=OrderMaster::find("user_id="."'".$user_id."'");
		//$a=$User->id;
		//$shop=ProductMaster::find("shop_id="."'".$a."'");
		$data['value']=$order;
		//$data['action']="My Products";
		$this->view->setVar(data,$data);
		
	
	}
	
	public function detailsAction($value = '')
	{

		$inventoryData = OrderDetails::findFirst('order_master_id = "' . $value . '"');
		$data['product_id'] = $inventoryData->product_id;
		$data['price'] = $inventoryData->price;
		$data['quantity'] = $inventoryData->quantity;
		//$data['price'] = $inventoryData->price;
		//$data['in_stock'] = $inventoryData->in_stock;
		$this->view->setVars($data);

		

	}
	













}