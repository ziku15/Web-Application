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
	
	public function indexAction()
    {
    
    }


	public function listAction()
	{
		

        $user_id = $this->session->get('auth')['id'];
        $newresult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\OrderMaster')
                  ->columns('Biz_mela\Models\OrderMaster.order_no,Biz_mela\Models\OrderMaster.id as o_id,Biz_mela\Models\OrderMaster.total,
                    Biz_mela\Models\OrderMaster.subtotal,Biz_mela\Models\OrderMaster.status,d.price,d.quantity,p.id,h.status_code,
                    p.product_name,s.status_name')
                  ->leftJoin('Biz_mela\Models\OrderDetails', 'd.order_master_id = Biz_mela\Models\OrderMaster.id', 'd')
                  ->leftJoin('Biz_mela\Models\ProductMaster', 'p.id = d.product_id', 'p')
                  ->leftJoin('Biz_mela\Models\OrderHistory', 'h.order_master_id = Biz_mela\Models\OrderMaster.id', 'h')
                  ->leftJoin('Biz_mela\Models\OrderStatus', 's.status_code = h.status_code', 's')
                  ->orderBy('Biz_mela\Models\OrderMaster.order_no desc')
                  ->groupBy('Biz_mela\Models\OrderMaster.order_no')
                  ->where('Biz_mela\Models\OrderMaster.user_id = :name:', array('name' => $user_id))
                  ->getQuery()
                  ->execute();


        /*foreach ($newresult as $value) {
            $present = OrderHistory::findFirst('order_master_id = "' . $value->o_id . '"');
            $temp[$value->status_code]=$present->status_code;

            
        }

        $this->view->setVar(temp,$temp);*/
        //$present = OrderHistory::findFirst('order_master_id = "' . $value->o_id . '"');
        $numberPage = $this->request->getQuery("page", "int", 1);
            $paginator = new Paginator(array(
                "data" => $newresult,
                "limit" => 4,
                "page" => $numberPage
            ));
            
            $page['Order'] = $paginator->getPaginate();
            $page['value'] = $value;
            $this->view->setVars($page);



        $user_id = $this->session->get('auth')['id'];

        $successresult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\OrderMaster')
                  ->columns('Biz_mela\Models\OrderMaster.order_no,Biz_mela\Models\OrderMaster.total,Biz_mela\Models\OrderMaster.id,Biz_mela\Models\OrderMaster.subtotal,
                    s.status_name')
                  ->leftJoin('Biz_mela\Models\OrderHistory', 'h.order_master_id = Biz_mela\Models\OrderMaster.id', 'h')
                  ->leftJoin('Biz_mela\Models\OrderStatus', 's.status_code = h.status_code', 's')
                  ->orderBy('Biz_mela\Models\OrderMaster.order_no desc')
                  ->where('Biz_mela\Models\OrderMaster.user_id = :name:', array('name' => $user_id))
                  ->andWhere ('h.status_code = 1')
                  ->getQuery()
                  ->execute();

        $numberPage = $this->request->getQuery("page", "int", 1);
            $paginator = new Paginator(array(
                "data" => $successresult,
                "limit" => 4,
                "page" => $numberPage
            ));
            
            $page['Purchase'] = $paginator->getPaginate();
            $page['value'] = $value;
            $this->view->setVars($page);

        $user_id = $this->session->get('auth')['id'];

        $pendingresult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\OrderMaster')
                  ->columns('Biz_mela\Models\OrderMaster.order_no,Biz_mela\Models\OrderMaster.total,Biz_mela\Models\OrderMaster.id,
                    Biz_mela\Models\OrderMaster.subtotal,s.status_name,h.status_code')
                  ->leftJoin('Biz_mela\Models\OrderHistory', 'h.order_master_id = Biz_mela\Models\OrderMaster.id', 'h')
                  ->leftJoin('Biz_mela\Models\OrderStatus', 's.status_code = h.status_code', 's')
                  ->orderBy('Biz_mela\Models\OrderMaster.order_no desc')
                  ->where('Biz_mela\Models\OrderMaster.user_id = :name:', array('name' => $user_id))
                  ->andWhere ('h.status_code = 2')
                  ->getQuery()
                  ->execute();

        $numberPage = $this->request->getQuery("page", "int", 1);
            $paginator = new Paginator(array(
                "data" => $pendingresult,
                "limit" => 4,
                "page" => $numberPage
            ));
            
            $page['Pending'] = $paginator->getPaginate();
            $page['value'] = $value;
            $this->view->setVars($page);

        $user_id = $this->session->get('auth')['id'];

        $rejectresult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\OrderMaster')
                  ->columns('Biz_mela\Models\OrderMaster.order_no,Biz_mela\Models\OrderMaster.total,Biz_mela\Models\OrderMaster.subtotal,
                    Biz_mela\Models\OrderMaster.id,s.status_name')
                  ->leftJoin('Biz_mela\Models\OrderHistory', 'h.order_master_id = Biz_mela\Models\OrderMaster.id', 'h')
                  ->leftJoin('Biz_mela\Models\OrderStatus', 's.status_code = h.status_code', 's')
                  ->orderBy('Biz_mela\Models\OrderMaster.order_no desc')
                  ->where('Biz_mela\Models\OrderMaster.user_id = :name:', array('name' => $user_id))
                  ->andWhere ('h.status_code = 3')
                  ->getQuery()
                  ->execute();

        $numberPage = $this->request->getQuery("page", "int", 1);
            $paginator = new Paginator(array(
                "data" => $rejectresult,
                "limit" => 4,
                "page" => $numberPage
            ));
            
            $page['Reject'] = $paginator->getPaginate();
            $page['value'] = $value;
            $this->view->setVars($page);  


		
	
	}
	
	public function detailsAction($value = '')
	{

		$inventoryData = OrderDetails::findFirst('order_master_id = "' . $value . '"');
		$product=$inventoryData->product_id;
		$inventory=ProductMaster::findFirst('id = "' . $product .'"');
		$data['product_name'] = $inventory->product_name;
		$data['product_id'] = $inventoryData->product_id;
		$data['price'] = $inventoryData->price;
		$data['quantity'] = $inventoryData->quantity;
		
		$this->view->setVars($data);

		

	}


	


	public function orderdetailsAction($value = '')
	{

		//$order=OrderDetails::findFirst('order_master_id = "' . $value . '"');
    $newresult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\OrderDetails')
                  ->columns('Biz_mela\Models\OrderDetails.order_master_id,Biz_mela\Models\OrderDetails.product_id,
                    Biz_mela\Models\OrderDetails.price, Biz_mela\Models\OrderDetails.quantity,s.status_name,
                    m.total,m.subtotal,p.id,p.product_name,i.picture')
                  ->leftJoin('Biz_mela\Models\OrderMaster', 'Biz_mela\Models\OrderDetails.order_master_id = m.id', 'm')
                  
                  ->leftJoin('Biz_mela\Models\OrderHistory', 'h.order_master_id = m.id', 'h')
                  ->leftJoin('Biz_mela\Models\OrderStatus', 's.status_code = h.status_code', 's')
                  ->leftJoin('Biz_mela\Models\ProductMaster', 'p.id = Biz_mela\Models\OrderDetails.product_id', 'p')
                  ->leftJoin('Biz_mela\Models\ProductImage', 'i.product_id = Biz_mela\Models\OrderDetails.product_id', 'i')
                  //->orderBy('Biz_mela\Models\OrderMaster.order_no desc')
                  ->where('Biz_mela\Models\OrderDetails.order_master_id = :name:', array('name' => $value))
                  ->getQuery()
                  ->execute();

      $numberPage = $this->request->getQuery("page", "int", 1);
            $paginator = new Paginator(array(
                "data" => $newresult,
                "limit" => 3,
                "page" => $numberPage
            ));
            
            $page['Order'] = $paginator->getPaginate();
            $page['value'] = $value;
            $this->view->setVars($page);

      //$data['value']=$newresult;
      //$this->view->setVar(data,$data);

	}

  public function modifyAction($value = '')
  {

    $present = OrderHistory::findFirst('order_master_id = "' . $value . '"');

    $data['value']=$present;

    $this->view->setVar(data,$data);


  }


  public function changeAction($value = '')
  {

      /*$url= $_SERVER['HTTP_REFERER'];
      $prev=explode("/", $url);
      
      $id=$prev[6];

      if ($this->request->isPost()) {
      if ($this->request->getPost('submit') == 'cancel') {
                return $this->response->redirect('order/list/');
            }*/

      $product=OrderHistory::findFirst('order_master_id = "' . $value . '"');
      $product->status_code=3;
      $product->save();

      return $this->dispatcher->forward(
            array(
              'controller' => 'order',
              'action' => 'list'
              
              )
            );

  }


}