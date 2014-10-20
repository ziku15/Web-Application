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
		$phql = ("SELECT Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductWishlist.product_id
			FROM Biz_mela\Models\ProductMaster, Biz_mela\Models\ProductWishlist, Biz_mela\Models\UserMaster
			WHERE Biz_mela\Models\ProductMaster.id = Biz_mela\Models\ProductWishlist.product_id
			AND Biz_mela\Models\UserMaster.id = Biz_mela\Models\ProductWishlist.user_id
			AND Biz_mela\Models\ProductWishlist.user_id = $userid
			LIMIT 0 , 30");

		$newresult = $this->modelsManager->executeQuery($phql);

		
         $this->view->setVar(newresult,$newresult);
		


	}
	


	public function detailsAction($value = '')
	{

		$inventoryData = ProductMaster::findFirst('product_name = "' . $value . '"');


			$data['id'] = $inventoryData->id;
			$data['heading'] = $inventoryData->product_name;
			$data['description'] = $inventoryData->product_description;
			$data['price'] = $inventoryData->price;
			$data['discount'] = $inventoryData->discount;
			$this->view->setVars($data);


	}


	public function deleteAction($value = '')
	{
			$find = ProductMaster::findFirst('product_name = "' . $value . '"');
			$out=$find->id;
			$dump= ProductWishlist::findFirst('product_id = ' . $out );
			if ($dump != false) {
    			if ($dump->delete() == false) {
				        echo "Sorry, we can't delete the robot right now: \n";
				        foreach ($robot->getMessages() as $message) {
				            echo $message, "\n";
				        }
			    } else {
			        echo "The product was deleted successfully!";
			        return $this->response->redirect('wishlist/wish/');
			    }
			}


	}



















}