<?php

namespace Biz_mela\Frontend\Controllers;

use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\ShopMaster as ShopMaster;
use Biz_mela\Models\ProductMaster as ProductMaster;

use Phalcon\Tag as Tag,
Phalcon\Forms\Element\Text,
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

		
        
		$username = $this->session->get('auth');
        $con=UserMaster::findFirst("username="."'".$username['name']."'");
		$user_id=$con->id;
		$User=ShopMaster::findFirst("user_id="."'".$user_id."'");
		$a=$User->id;
		$shop=ProductMaster::find("shop_id="."'".$a."'");
		$data['value']=$shop;
		$data['action']="My Products";
		$this->view->setVar(data,$data);
		
		
		
		/*$user_id = $this->session->get('auth')['id'];


		$phql = ("SELECT *
			FROM Biz_mela\Models\ShopMaster, Biz_mela\Models\ProductMaster, Biz_mela\Models\UserMaster
			WHERE Biz_mela\Models\UserMaster.id = Biz_mela\Models\ShopMaster.user_id
			AND Biz_mela\Models\ShopMaster.id = Biz_mela\Models\ProductMaster.shop_id
			AND Biz_mela\Models\UserMaster.id = $user_id
			LIMIT 0 , 30");

		$newresult = $this->modelsManager->executeQuery($phql);

		//$newresult = $query->execute();
		$data['value']=$newresult;
                 //print_r($data['value']);exit();
         $this->view->setVar('data',$data);*/

		//$numberPage = $this->request->getQuery("page", "int", 1);
       /* $newresult = $this->modelsManager->createBuilder()
                ->from('Biz_mela\Models\ProductMaster')
        		->columns('Biz_mela\Models\ProductMaster.product_name,Biz_mela\Models\ProductMaster.product_description')
        		->andWhere('Biz_mela\Models\UserMaster.id=Biz_mela\Models\ShoptMaster.user_id')
        		->andWhere('Biz_mela\Models\ShopMaster.id=Biz_mela\Models\ProductMaster.user_id')
        		->andWhere('Biz_mela\Models\UserMaster.id= .$user_id')
        		//->join('Biz_mela\Models\UserMaster', 'Biz_mela\Models\ShopMaster.user_id='.$user_id)
		        // //$builder = $builder->where('Biz_mela\Models\ProductMaster.id desc');

		        // //$builder = $builder->where('is_del = 0');
		        // //   $builder = $builder->andWhere('Forum.created_at + INTERVAL \'7\' day>=CURRENT_DATE');
        		// ->orderby('Biz_mela\Models\ProductMaster.shop_id desc')
        		->getQuery()
                ->execute();*/

        /* $data['value']=$newresult;
                 //print_r($data['value']);exit();
         $this->view->setVar('data',$data);*/

       /* $paginator = new Phalcon\Paginator\Adapter\QueryBuilder(array(
            "builder" => $newresult,
            "limit" => 10,
            "page" => $numberPage
        ));*/

		/*SELECT *
FROM shop_master, product_master, user_master
WHERE user_master.id = shop_master.user_id
AND shop_master.id = product_master.shop_id
AND user_master.id =130
LIMIT 0 , 30*/


        // Get the paginated results

		/*Look At this
        $page['Product'] = $paginator->getPaginate();
        $page['value'] = $value;*/

        //$this->view->setVars(array('sub_title'=>'List of All Threads'));
        //$this->view->setVars(array('action' => 'Listing all threads'));
        //$this->view->setVars($page);
		/*$numberPage = $this->request->getQuery("page", "int", 1);
        $builder = $this->modelsManager->createBuilder();
        $builder = $builder->from('ProductMaster');
		$builder = $builder->columns('ProductMaster.id,ProductMaster.product_name,ProductMaster.product_description');
        //$builder = $builder->andWhere('is_del = 0');
        //$builder = $builder->andWhere('status = 1');
		$builder->leftJoin('ShopMaster', 's.id = ProductMaster.shop_id', 's');
        $builder = $builder->orderBy('ProductMaster.id desc');

        $paginator = new Phalcon\Paginator\Adapter\QueryBuilder(array(
            "builder" => $builder,
            "limit" => 10,
            "page" => $numberPage
        ));

        // Get the paginated results
        $page['all_products'] = $paginator->getPaginate();
        $page['value'] = $value;

        //$this->view->setVars(array('sub_title' => 'List of events'));
        $this->view->setVars($page);*/
		
        
    }
	
	
	
	public function newAction() {
		$form = new Form();
		
		$shop_id = new Text("shop_id", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'shop_id',
            'placeholder' => 'Shop_id',
			'autocomplete' => 'off'
        ));
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
		
		$form->add($shop_id);
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
		$shop_id=$this->request->getPost('shop_id');
		$title = $this->request->getPost('product_name');
		$type = $this->request->getPost('type');
		$product_description = $this->request->getPost('product_description');
		$price = $this->request->getPost('price');
		$discount = $this->request->getPost('discount');
		$in_stock = $this->request->getPost('in_stock');
		$minimum_order_level = $this->request->getPost('minimum_order_level');
		if (!$form->isValid($_POST)) {
						$this->flash->error("Please solve the following error !!");
					}
					else{
		
					$Product = new ProductMaster();
					$Product->shop_id= $shop_id;
					$Product->product_name = $title;
					
                    $Product->type=$type;
					//$User->password = $this->security->hash($password);
					$Product->product_description=$product_description;
					$Product->price=$price;
					$Product->discount=$discount;
					$Product->in_stock=$in_stock;
					$Product->minimum_order_level=$minimum_order_level;
					
					$Product->created_by = $this->auth['id'];
					$Product->updated_by = $this->auth['id'];
					//$User->status = 1;
					$Product->created_at = date("Y-m-d h:i:s");
					$Product->updated_at = date('Y-m-d h:i:s');
					//$User->is_del=0;
					//$User->activation_code=0;
					
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
			
			
			/*$username = $this->session->get('auth');
			$con=UserMaster::findFirst("username="."'".$username['name']."'");
			$user_id=$con->id;
			$User=ShopMaster::findFirst("user_id="."'".$user_id."'");
			$a=$User->id;
			$inventoryData=ProductMaster::findFirst("shop_id="."'".$a."'");*/
			//$shop=$inventoryData->product_name;
			/*$data['value']=$inventoryData;
			$this->view->setVar(data,$data);*/
			/*$data['value']=$shop;
			
			//$data['bankname'] = $a->bankname;
			$this->view->setVar(data,$data);*/
			/*$data['value']=$inventoryData;
			$this->view->setVar(data,$data);*/
			$data['id'] = $inventoryData->id;
			$data['heading'] = $inventoryData->product_name;
			$data['description'] = $inventoryData->product_description;
			$data['price'] = $inventoryData->price;
			$data['in_stock'] = $inventoryData->in_stock;
			
			$data['discount'] = $inventoryData->discount;
			$data['created_at'] = $inventoryData->created_at;
			$data['minimum_order_level'] = $inventoryData->minimum_order_level;
			$data['action'] = $inventoryData->product_name;
			$this->view->setVars($data);
		
    }
	
	public function editAction()
	{
			$username = $this->session->get('auth');
			$con=UserMaster::findFirst("username="."'".$username['name']."'");
			$user_id=$con->id;
			$User=ShopMaster::findFirst("user_id="."'".$user_id."'");
			$a=$User->id;
			$inventoryData=ProductMaster::findFirst("shop_id="."'".$a."'");
			$pro=$inventoryData->id;
			$product=ProductMaster::findFirst("id="."'".$pro."'");
			//$data['value']=$product;
			//$product = ProductMaster::findFirst('id = "' . $id . '"');
		
		$form = new Form();
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
	
		$form->add($description);
		$form->add($price);
		$form->add($discount);
		$form->add($minimum_order_level);
		
		$data['form'] = $form;
        $this->view->setVars($data);
	
		if ($this->request->isPost()) {
			if ($this->request->getPost('submit') == 'cancel') {
                return $this->response->redirect('inventory/list/');
            }
		
			$description = $this->request->getPost('description');
			$price = $this->request->getPost('price');
			$discount = $this->request->getPost('discount');
			$minimum_order_level = $this->request->getPost('minimum_order_level');
			//$accountno = $this->request->getPost('accountno');
		
			$product->description = $description;
			$product->price = $price;
			$product->discount = $discount;
			//$User->account_holder_name = $accountholder;
			$product->minimum_order_level = $minimum_order_level;
			
			if ($product->save()) {
						$this->flash->success("Product updated successfully!!");
						$this->response->redirect('inventory/list/');
					} else {
						$this->flash->error("error occured,please try again later!!");
					}
	
	}
	
	
	}
}