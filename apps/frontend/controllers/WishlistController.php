<?php

namespace Biz_mela\Frontend\Controllers;
use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\ProductWishlist as ProductWishlist;
use Biz_mela\Models\ProductMaster as ProductMaster;

use Phalcon\Paginator\Adapter\Model as Paginator;

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

         $user_id = $this->session->get('auth')['id'];
            
            
			$newresult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductMaster')
                  ->columns('Biz_mela\Models\ProductMaster.product_name,Biz_mela\Models\ProductMaster.id,Biz_mela\Models\ProductMaster.product_description,Biz_mela\Models\ProductMaster.price,
            		  Biz_mela\Models\ProductMaster.discount,Biz_mela\Models\ProductMaster.in_stock, Biz_mela\Models\ProductMaster.status,
            		  w.status as wish,p.picture')
                  ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
                  ->leftJoin('Biz_mela\Models\ProductWishlist', 'w.product_id = Biz_mela\Models\ProductMaster.id', 'w')
                  ->orderBy('Biz_mela\Models\ProductMaster.id desc')
                  ->where('w.user_id = :name:', array('name' => $user_id))
                  ->getQuery()
                  ->execute();

            $numberPage = $this->request->getQuery("page", "int", 1);
            $paginator = new Paginator(array(
                "data" => $newresult,
                "limit" => 5,
                "page" => $numberPage
            ));
            
            $page['Wish'] = $paginator->getPaginate();
            $page['value'] = $value;
            $this->view->setVars($page);
		


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
			//$find = ProductMaster::findFirst('product_name = "' . $value . '"');
			//$out=$find->id;
			$dump= ProductWishlist::findFirst('product_id = ' . $value );
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