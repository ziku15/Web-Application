<?php

namespace Biz_mela\Frontend\Controllers;


use Biz_mela\Models\ShopMaster as ShopMaster;
use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\ProductMaster as ProductMaster;
use Biz_mela\Models\ProductImage as ProductImage;
use Biz_mela\Models\OrderDetails as OrderDetails;
use Biz_mela\Models\OrderHistory as OrderHistory;
use Biz_mela\Models\OrderMaster as OrderMaster;

use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Tag as Tag,
Phalcon\Forms\Element\Text,
Phalcon\Forms\Element\Select,
Phalcon\Validation\Validator\PresenceOf,

Phalcon\Forms\Form;

class InventoryController extends ControllerBase
{
    public function initialize()
    {
		$this->view->setTemplateAfter('myaccount');
		$this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            return $this->response->redirect('session/start/');
        }

        $this->view->setVars(array('title' => 'My Inventory'));
        
        
    }
	
	 public function indexAction()
    {
		
	  }
	
	public function listAction() {
			$user_id = $this->session->get('auth')['id'];
			$newresult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductMaster')
                  ->columns('Biz_mela\Models\ProductMaster.product_name,Biz_mela\Models\ProductMaster.id,Biz_mela\Models\ProductMaster.product_description,Biz_mela\Models\ProductMaster.price,
            		  Biz_mela\Models\ProductMaster.discount,Biz_mela\Models\ProductMaster.in_stock, Biz_mela\Models\ProductMaster.status,
            		  p.picture,s.shop_name, s.id as sid,s.user_id')
                  ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
                  ->leftJoin('Biz_mela\Models\ShopMaster', 's.id = Biz_mela\Models\ProductMaster.shop_id', 's')
                  ->orderBy('Biz_mela\Models\ProductMaster.id desc')
                  ->where('s.user_id = :name:', array('name' => $user_id))
                  ->getQuery()
                  ->execute();
            //$newresult = $this->modelsManager->executeQuery($phql);

            $numberPage = $this->request->getQuery("page", "int", 1);
            $paginator = new Paginator(array(
                "data" => $newresult,
                "limit" => 8,
                "page" => $numberPage
            ));
            
            $page['Product'] = $paginator->getPaginate();

            $this->view->setVars($page);
            $user_id = $this->session->get('auth')['id'];
            $numberPage = $this->request->getQuery("page", "int", 1);
            $phql = ("SELECT Biz_mela\Models\ProductMaster.product_name,Biz_mela\Models\ProductMaster.id,Biz_mela\Models\ProductMaster.product_description,
            	Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.discount,Biz_mela\Models\ProductMaster.in_stock, Biz_mela\Models\ProductMaster.status,
            	Biz_mela\Models\ProductImage.picture,Biz_mela\Models\ShopMaster.shop_name, Biz_mela\Models\ShopMaster.id as sid
                FROM Biz_mela\Models\ShopMaster, Biz_mela\Models\ProductMaster, Biz_mela\Models\ProductImage
                Where Biz_mela\Models\ShopMaster.id = Biz_mela\Models\ProductMaster.shop_id
                AND Biz_mela\Models\ProductImage.product_id = Biz_mela\Models\ProductMaster.id
                AND Biz_mela\Models\ShopMaster.user_id = $user_id
                ORDER BY CAST(Biz_mela\Models\ProductMaster.price  AS SIGNED) desc
                ");
       // echo $phql;exit();
            //ORDER BY CAST(`type` AS SIGNED)

            $valueresult = $this->modelsManager->executeQuery($phql);
        //print_r($valueresult);exit();
            $paginator = new Paginator(array(
                "data" => $valueresult,
                "limit" => 8,
                "page" => $numberPage
            ));

            $page['Valuable'] = $paginator->getPaginate();

            $this->view->setVars($page);


            $user_id = $this->session->get('auth')['id'];
        $numberPage = $this->request->getQuery("page", "int", 1);

        $bestResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductMaster')
                  ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name,
                  	Biz_mela\Models\ProductMaster.status, s.shop_name, s.id as sid, p.picture,o.product_id')
                  ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
                  ->join('Biz_mela\Models\OrderDetails', 'o.product_id = Biz_mela\Models\ProductMaster.id', 'o','right')
                  ->leftJoin('Biz_mela\Models\ShopMaster', 's.id = Biz_mela\Models\ProductMaster.shop_id', 's')
                  ->where('s.user_id = :name:', array('name' => $user_id))
                  ->groupBy('o.product_id')
                  ->orderBy('count(o.product_id) desc')
                  ->limit(4)
                  ->getQuery()
                  ->execute();
       // print_r($bestResult);exit();

        $paginator = new Paginator(array(
                "data" => $bestResult,
                "limit" => 8,
                "page" => $numberPage
            ));

            $page['selling'] = $paginator->getPaginate();

            $this->view->setVars($page);
        
    }


	
	public function newAction() {

		
        $user_id = $this->session->get('auth')['id'];
        $shop=ShopMaster::find("user_id="."'".$user_id."'");
		$form = new Form();

        foreach ($shop as $value) {
        	$container[$value->id] =  $value->shop_name;
        }
		
		$shop_name = new Select("shop_name", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'shop_name',
			'autocomplete' => 'off',
			'multiple'=>'yes'
        ));

        $shop_name->setOptions($container);
		
		$product_name = new Text("product_name", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'product_name',
            'placeholder' => 'Product Title',
			'autocomplete' => 'off'
        ));
		$product_name->addValidator(new PresenceOf(array(
            'message' => 'The Product Name field is required'
        )));
		$type = new Text("type", array(
			'class' => 'form-control input-lg form-element',
			'id' => 'type',
            'placeholder' => 'Product Type',
			'autocomplete' => 'off'
		));
		$type->addValidator(new PresenceOf(array(
            'message' => 'The Product Type field is required'
        )));
		
		$product_description = new Text("product_description", array(
			'class' => 'form-control input-lg form-element',
			'id' => 'product_description',
            'placeholder' => 'Product Description',
			'autocomplete' => 'off',
			'maxlength' => 512
		));
		
		$price = new Text("price", array(
			'class' => 'form-control input-lg form-element',
			'id' => 'price',
            'placeholder' => 'Price',
			'autocomplete' => 'off'
		));
		
		$price->addValidator(new PresenceOf(array(
            'message' => 'The Product Price field is required'
        )));
		
		$discount = new Text("discount", array(
			'class' => 'form-control input-lg form-element',
			'id' => 'discount',
            'placeholder' => 'Discount(In 100 percent)',
			'autocomplete' => 'off'
		));
		
		
		$in_stock = new Text("in_stock", array(
			'class' => 'form-control input-lg form-element',
			'id'=>'in_stock',
            'placeholder' => 'Stock Quantity'
		));
		$in_stock->addValidator(new PresenceOf(array(
            'message' => 'The in_stock field is required'
        )));
		
		$minimum_order_level=new Text("minimum_order_level", array(
			'class' => 'form-control input-lg form-element',
			'id'=>'minimum_order_level',
            'placeholder' => 'Least amount of Order to be served',
			'autocomplete' => 'off'
		));
		$minimum_order_level->addValidator(new PresenceOf(array(
            'message' => 'The Order Quantity is required'
        )));
		
		$form->add($shop_name);
		$form->add($product_name);
		$form->add($type);
		$form->add($product_description);
        //$form->add($password);
		$form->add($price);
		$form->add($discount);
		$form->add($in_stock);
		$form->add($minimum_order_level);
        $data['form'] = $form;
        $this->view->setVars($data);
	
		if ($this->request->isPost()) {

            if ($this->request->getPost('submit') == 'cancel') {
                return $this->response->redirect('inventory/list');
            }
			$shop_id=$this->request->getPost('shop_name');
			$title = $this->request->getPost('product_name');
			$type = $this->request->getPost('type');
			$product_description = $this->request->getPost('product_description');
			$price = $this->request->getPost('price');
			$discount = $this->request->getPost('discount');
			$in_stock = $this->request->getPost('in_stock');
			$minimum_order_level = $this->request->getPost('minimum_order_level');

			if (!$form->isValid($_POST)) {
					$this->flash->error("Please solve the following errors !!");
				}else{
				
				$Product = new ProductMaster();
				
				$Product->shop_id= $shop_id;
				
				$Product->product_name = $title;
					
                $Product->type=$type;
					
				$Product->product_description=$product_description;
				$Product->price=$price;
				$Product->discount=$discount;
				$Product->in_stock=$in_stock;
				$Product->minimum_order_level=$minimum_order_level;
					
				$Product->created_by = $this->auth['id'];
				$Product->updated_by = $this->auth['id'];
					
				$Product->created_at = date("Y-m-d h:i:s");
				$Product->updated_at = date('Y-m-d h:i:s');
				$Product->status=1;
					
					
				if ($Product->create()) {
					
					$this->flash->success("Product  added successfully!!");
					return $this->response->redirect('inventory/list/');
						//exit();
					} else {
						$this->flash->error("error occured,please try again later!!");
		
					}
				}
	
		}
        $data['form'] = $form;
        $this->view->setVars($data);
	}



	
	public function detailsAction($value = '') {
			$inventoryData = ProductMaster::findFirst('id = "' . $value . '"');
			$photo=ProductImage::findFirst('product_id = "' . $value . '"');
			
			$data['id'] = $inventoryData->id;
			$data['heading'] = $inventoryData->product_name;
			$data['description'] = $inventoryData->product_description;
			$data['price'] = $inventoryData->price;
			$data['in_stock'] = $inventoryData->in_stock;
			
			$data['discount'] = $inventoryData->discount;
			$data['created_at'] = $inventoryData->created_at;
			$data['minimum_order_level'] = $inventoryData->minimum_order_level;
			$data['picture'] = $photo->picture;
			$data['action'] = $inventoryData->product_name;
			$this->view->setVars($data);



			$newresult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\OrderDetails')
                  ->columns('Biz_mela\Models\OrderDetails.price,Biz_mela\Models\OrderDetails.quantity,
                  	Biz_mela\Models\OrderDetails.product_id,h.status_code')
                  
                  
                  ->leftJoin('Biz_mela\Models\OrderHistory', 'h.order_master_id = Biz_mela\Models\OrderDetails.order_master_id', 'h')
                  
                  ->where('Biz_mela\Models\OrderDetails.product_id = :name:', array('name' => $value))
                  ->andWhere ('h.status_code = :name1:', array('name1' => 1))
                  ->getQuery()
                  ->execute();

            $sales['value']=$newresult;
            $this->view->setVar(sales,$sales);

            $sum = intval(0);

            foreach ($newresult as $value) {
                $sum = $sum + $value-> quantity;
        	}

        	$this->view->setVar(sum,$sum);
            /*foreach ($newresult as $value) {
            $product_data=OrderDetails::find("product_id="."'".$value->product_id."'");
            $temp[$value->quantity]=count($product_data);

            
        	}
        
        	$this->view->setVar(temp,$temp);*/

            //$sales['value']=$newresult;
            //$this->view->setVar(sales,$sales);
		
    }
	
	public function editAction($value = '')
	{
			
		$product=ProductMaster::findFirst("id="."'".$value."'");
			
		
		$form = new Form();

		$product_name=new Text("product_name", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'product_name',
            'value' => $product->product_name,
            'autocomplete' => 'off'
        ));
		$description = new Text("description", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'description',
            'value' => $product->product_description,
            'autocomplete' => 'off'
        ));
		$price = new Text("price", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'price',
            'value' => $product->price,
            'autocomplete' => 'off'
        ));
        $in_stock = new Text("in_stock", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'in_stock',
            'value' => $product->in_stock,
            'autocomplete' => 'off'
        ));
        
		$discount = new Text("discount", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'discount',
            'value' => $product->discount,
            'autocomplete' => 'off'
        ));
		$minimum_order_level = new Text("minimum_order_level", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'minimum_order_level',
            'value' => $product->minimum_order_level,
            'autocomplete' => 'off'
        ));

        $status = new Text("status", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'status',
            'value' => $product->status,
            'autocomplete' => 'off'
        ));

		$form->add($product_name);
		$form->add($description);
		$form->add($price);
		$form->add($in_stock);
		$form->add($discount);
		$form->add($minimum_order_level);
		$form->add($status);
		
		$data['form'] = $form;
        $this->view->setVars($data);
	
		if ($this->request->isPost()) {
			if ($this->request->getPost('submit') == 'cancel') {
                return $this->response->redirect('inventory/list/');
            }

			$product_name = $this->request->getPost('product_name');
			$description = $this->request->getPost('description');
			$price = $this->request->getPost('price');
			$in_stock = $this->request->getPost('in_stock');
			$discount = $this->request->getPost('discount');
			$minimum_order_level = $this->request->getPost('minimum_order_level');
			$status = $this->request->getPost('status');
			//$accountno = $this->request->getPost('accountno');

			$product->product_name = $product_name;
			$product->description = $description;
			$product->price = $price;
			$product->in_stock = $in_stock;
			$product->discount = $discount;
			//$User->account_holder_name = $accountholder;
			$product->minimum_order_level = $minimum_order_level;
			$product->status = $status;
			
			if ($product->save()) {
						$this->flash->success("Product updated successfully!!");
						return $this->dispatcher->forward(
					 	array(
					 		'controller' => 'inventory',
					 		'action' => 'details',
					 		'param' => $value
					 		)
					 	);
					} else {
						$this->flash->error("error occured,please try again later!!");
					}
	
	}
	
	
	}

	public function deleteAction($value = '')
	{

			$dump = ProductMaster::findFirst('id = "' . $value . '"');
			
			if ($dump != false) {
    			if ($dump->delete() == false) {
				        echo "Sorry, we can't delete the robot right now: \n";
				        foreach ($robot->getMessages() as $message) {
				            echo $message, "\n";
				        }
			    } else {
			        echo "The product was deleted successfully!";
			        return $this->response->redirect('inventory/list/');
			    }
			}

	}

	


}