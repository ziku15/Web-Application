<?php

namespace Biz_mela\Frontend\Controllers;

use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\OrderMaster as OrderMaster;
use Biz_mela\Models\OrderDetails as OrderDetails;
use Biz_mela\Models\ProductMaster as ProductMaster;
use Biz_mela\Models\OrderHistory as OrderHistory;
use Biz_mela\Models\OrderStatus as OrderStatus;

use Phalcon\Paginator\Adapter\Model as Paginator;



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
		$username = $this->session->get('auth');
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
		$user_id=$con->id;
		$numberPage = $this->request->getQuery("page", "int", 1);
		$order=OrderMaster::find("user_id="."'".$user_id."'");
		
		
		$paginator = new Paginator(array(
            "data" => $order,
            "limit" => 3,
            "page" => $numberPage
        ));
		
        $page['Order'] = $paginator->getPaginate();
        $page['value'] = $value;
        $this->view->setVars($page);
		
	}


	public function listAction()
	{
		
	

		$username = $this->session->get('auth');
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
		$user_id=$con->id;
		$numberPage = $this->request->getQuery("page", "int", 1);
		$order=OrderMaster::find("user_id="."'".$user_id."'");
		
		
		$paginator = new Paginator(array(
            "data" => $order,
            "limit" => 3,
            "page" => $numberPage
        ));
		
        $page['Order'] = $paginator->getPaginate();
        $page['value'] = $value;
        $this->view->setVars($page);
		
	
	}
	
	public function detailsAction($value = '')
	{

		$inventoryData = OrderDetails::findFirst('order_master_id = "' . $value . '"');
		$data['product_id'] = $inventoryData->product_id;
		$data['price'] = $inventoryData->price;
		$data['quantity'] = $inventoryData->quantity;
		
		$this->view->setVars($data);

		

	}


	public function pendingAction()
	{

		$username = $this->session->get('auth');
		
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
		
		$userid=$con->id;
		$numberPage = $this->request->getQuery("page", "int", 1);
		$phql = ("SELECT Biz_mela\Models\OrderMaster.order_no, Biz_mela\Models\OrderStatus.status_name,Biz_mela\Models\OrderMaster.id
			FROM Biz_mela\Models\OrderMaster, Biz_mela\Models\OrderStatus, Biz_mela\Models\OrderHistory
			WHERE Biz_mela\Models\OrderMaster.id = Biz_mela\Models\OrderHistory.order_master_id
			AND Biz_mela\Models\OrderStatus.status_code = Biz_mela\Models\OrderHistory.status_code
			AND Biz_mela\Models\OrderMaster.user_id = $userid
			AND Biz_mela\Models\OrderHistory.status_code = 2
			LIMIT 0 , 30");


		$newresult = $this->modelsManager->executeQuery($phql);

		$paginator = new Paginator(array(
            "data" => $newresult,
            "limit" => 3,
            "page" => $numberPage
        ));
		
        $page['Order'] = $paginator->getPaginate();
        $page['value'] = $value;
        $this->view->setVars($page);

	}


	public function orderdetailsAction($value = '')
	{

		$inventoryData = OrderDetails::findFirst('order_master_id = "' . $value . '"');
		$data['product_id'] = $inventoryData->product_id;
		$data['price'] = $inventoryData->price;
		$data['quantity'] = $inventoryData->quantity;
		
		$this->view->setVars($data);

	}


	public function rejectAction()
	{

		$username = $this->session->get('auth');
		
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
		
		$userid=$con->id;
		$numberPage = $this->request->getQuery("page", "int", 1);
		$phql = ("SELECT Biz_mela\Models\OrderMaster.order_no, Biz_mela\Models\OrderStatus.status_name,Biz_mela\Models\OrderMaster.id
			FROM Biz_mela\Models\OrderMaster, Biz_mela\Models\OrderStatus, Biz_mela\Models\OrderHistory
			WHERE Biz_mela\Models\OrderMaster.id = Biz_mela\Models\OrderHistory.order_master_id
			AND Biz_mela\Models\OrderStatus.status_code = Biz_mela\Models\OrderHistory.status_code
			AND Biz_mela\Models\OrderMaster.user_id = $userid
			AND Biz_mela\Models\OrderHistory.status_code = 3
			LIMIT 0 , 30");


		$newresult = $this->modelsManager->executeQuery($phql);
		$paginator = new Paginator(array(
            "data" => $newresult,
            "limit" => 3,
            "page" => $numberPage
        ));
		
        $page['Order'] = $paginator->getPaginate();
        $page['value'] = $value;
        $this->view->setVars($page);

		
         //$this->view->setVar(newresult,$newresult);


	}


	public function purchaseAction()
	{
		$username = $this->session->get('auth');
		
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
		
		$userid=$con->id;
		$numberPage = $this->request->getQuery("page", "int", 1);
		$phql = ("SELECT Biz_mela\Models\OrderMaster.order_no, Biz_mela\Models\OrderStatus.status_name,Biz_mela\Models\OrderMaster.id
			FROM Biz_mela\Models\OrderMaster, Biz_mela\Models\OrderStatus, Biz_mela\Models\OrderHistory
			WHERE Biz_mela\Models\OrderMaster.id = Biz_mela\Models\OrderHistory.order_master_id
			AND Biz_mela\Models\OrderStatus.status_code = Biz_mela\Models\OrderHistory.status_code
			AND Biz_mela\Models\OrderMaster.user_id = $userid
			AND Biz_mela\Models\OrderHistory.status_code = 1
			LIMIT 0 , 30");


		$newresult = $this->modelsManager->executeQuery($phql);
		$newresult = $this->modelsManager->executeQuery($phql);
		$paginator = new Paginator(array(
            "data" => $newresult,
            "limit" => 3,
            "page" => $numberPage
        ));
		
        $page['Order'] = $paginator->getPaginate();
        $page['value'] = $value;
        $this->view->setVars($page);


		
         //$this->view->setVar(newresult,$newresult);

	}
	













}