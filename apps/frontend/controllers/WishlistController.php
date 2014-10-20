<?php

namespace Biz_mela\Frontend\Controllers;
use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\ProductWishlist as ProductWishlist;
use Biz_mela\Models\ProductMaster as ProductMaster;

use Phalcon\Tag as Tag,
	Phalcon\Forms\Form,
	Phalcon\Forms\Element\Text;

class WishlistController extends ControllerBase
{

	public function initialize()
    {
		$this->view->setTemplateAfter('myaccount');
		$this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            return $this->response->redirect('session/start/');
        }

        $this->view->setVars(array('title' => 'My WishList'));
        
        //Tag::setTitle('Account Information');
        //parent::initialize();
    }

    public function indexAction()
    {
		
	}

	public function wishAction()
	{

		$username = $this->session->get('auth');
		$con=UserMaster::findFirst("username="."'".$username['name']."'");

		$userid=$con->id;

		$data=ProductWishlist::find("user_id="."'".$userid."'");
		//$data['value']=$user;
		//$productid=$user->product_id;

		/*$newResult = $this->modelsManager->createBuilder()
		->from('Biz_mela\Models\ProductMaster')
                  ->columns('Biz_mela\Models\ProductMaster.product_name')
                  ->andWhere('Biz_mela\Models\ProductMaster.id= "'.$productid.'"')
                  //->andWhere('Biz_mela\Models\ProductMaster.id= "'.$productid.'"')
                  ->orderBy('Biz_mela\Models\ProductMaster.id desc')
                  ->getQuery()
                  ->execute();

        $this->view->setVar(newResult,$newResult);*/

		//$data['value']=$newResult;
		$this->view->setVar(data,$data);
		//$productid=$user->product_id;

		//$product=ProductMaster::findFirst("id="."'".$productid."'");

		/*$data['product_name']=$product->product_name;
		$data['product_description']=$product->product_description;
		$this->view->setVars($data);*/


	}
	


	public function detailsAction($value = '')
	{

		$inventoryData = ProductMaster::findFirst('id = "' . $value . '"');

			$data['id'] = $inventoryData->id;
			$data['heading'] = $inventoryData->product_name;
			$data['description'] = $inventoryData->product_description;
			$data['price'] = $inventoryData->price;
			$data['discount'] = $inventoryData->discount;
			$this->view->setVars($data);


	}





















}