<?php

namespace Biz_mela\Frontend\Controllers;

use Biz_mela\Models\ShopMaster as ShopMaster;
use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\ProductMaster as ProductMaster;
use Biz_mela\Models\ProductImage as ProductImage;


use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Tag as Tag,
Phalcon\Forms\Element\Text,
Phalcon\Validation\Validator\PresenceOf,
Phalcon\Forms\Element\File,
Phalcon\Forms\Element\Hidden,

Phalcon\Forms\Form;

class ShopController extends ControllerBase
{

	public function initialize()
    {
		$this->view->setTemplateAfter('myaccount');
		$this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            return $this->response->redirect('session/start/');
        }

        $this->view->setVars(array('title' => 'My Shop'));
        
        
    }
	
	 public function indexAction()
    {
		
	}


	public function shopAction()
    {
    	$user_id = $this->session->get('auth')['id'];
		$numberPage = $this->request->getQuery("page", "int", 1);


		$phql = ("SELECT Biz_mela\Models\ShopMaster.shop_name, Biz_mela\Models\ShopMaster.id,  Biz_mela\Models\ShopMaster.shop_image,  Biz_mela\Models\ShopMaster.status
			FROM Biz_mela\Models\ShopMaster,Biz_mela\Models\UserMaster
			WHERE Biz_mela\Models\UserMaster.id = Biz_mela\Models\ShopMaster.user_id
			AND Biz_mela\Models\UserMaster.id = $user_id
			ORDER BY Biz_mela\Models\ShopMaster.id desc
			LIMIT 0 , 30");

		$newresult = $this->modelsManager->executeQuery($phql);

		$paginator = new Paginator(array(
            "data" => $newresult,
            "limit" => 10,
            "page" => $numberPage
        ));
		
        $page['Shop'] = $paginator->getPaginate();
        $page['value'] = $value;
        $this->view->setVars($page);	



		
	}


	public function newAction()
	{
		$form = new Form();
		$shop_name = new Text("shop_name", array(
            'class' => 'form-control input-lg form-element',
			'id' => 'shop_name',
            'placeholder' => 'Shop_name',
			'autocomplete' => 'on'
        ));

        $shop_image = new File("shop_image", array(
            'class' => 'input-lg photo',
            'placeholder' => 'description'
        ));


        $banner = new File("banner", array(
            'class' => 'input-lg photo',
            'placeholder' => 'description'
        ));


        $form->add($shop_name);
        $form->add($shop_image);
        $form->add($banner);
        $data['form'] = $form;
        $this->view->setVars($data);


        if ($this->request->isPost()) {

        	if ($this->request->getPost('submit') == 'cancel') {
                return $this->response->redirect('shop/shop');
            }


            if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following error !!");
            } else {
            	$shop=new ShopMaster();

            	if ($this->request->hasFiles() == true) {
                    //if (isset($_FILES['thumb'])) {
                    // Print the real file names and sizes

                    $files = $this->request->getUploadedFiles();
                    foreach ($files as $file) {
                        //echo $file->getName() . " ";
                        $fileName = time() . $file->getName();
                        $file->moveTo('main/images/' . $fileName);
                        //$file->moveTo('uploads/gallery/' .'thumb_'. $fileName );
                        $image = new Phalcon\Image\Adapter\GD('main/images/' . $fileName);
                        $image->resize(200, 200)->crop(100, 100);
                        $image->save('main/images/' . $fileName);

                    }
                }

                    $shop->shop_image = 'main/images/' . $fileName;

                    $shop->banner = 'main/images/' . $fileName;
            	$shop_name = $this->request->getPost('shop_name');
            	$shop_image = $this->request->getPost('shop_image');
            	$banner = $this->request->getPost('banner');

            	$shop->shop_name=$shop_name;
            	$shop->user_id=$this->auth['id'];
            	$shop->shop_image=$shop_image;
            	$shop->banner=$banner;
            	$shop->created_at = date("Y-m-d");
                $shop->updated_at =date("Y-m-d");

                $shop->status = 1;

                if ($shop->create()) {
					$this->flash->success("Shop added successfully!!");
					return $this->response->redirect('shop/shop/');
						//exit();
					} else {
						$this->flash->error("error occured,please try again later!!");
		
					}

				}


			}


	}


	public function detailsAction($value = '') {
			$shop = ShopMaster::findFirst('id = "' . $value . '"');
			$product=ProductMaster::find('shop_id = "' . $value . '"');

			
			$data['id'] = $shop->id;
			$data['holder']=$shop->shop_name;
            $data['image']=$shop->shop_image;
			$data['heading'] = $this->auth['name'];
			
			
			$this->view->setVars($data);

            $user_id = $this->session->get('auth')['id'];
            $numberPage = $this->request->getQuery("page", "int", 1);
            $phql = ("SELECT Biz_mela\Models\ProductMaster.product_name,Biz_mela\Models\ProductMaster.id,Biz_mela\Models\ProductMaster.product_description,
                        Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.discount,Biz_mela\Models\ProductMaster.in_stock,
                        Biz_mela\Models\ProductMaster.created_at, Biz_mela\Models\ProductMaster.status,Biz_mela\Models\ProductImage.picture,Biz_mela\Models\ShopMaster.shop_image
                FROM Biz_mela\Models\ShopMaster, Biz_mela\Models\ProductMaster, Biz_mela\Models\UserMaster, Biz_mela\Models\ProductImage
                WHERE Biz_mela\Models\UserMaster.id = Biz_mela\Models\ShopMaster.user_id
                AND Biz_mela\Models\ShopMaster.id = Biz_mela\Models\ProductMaster.shop_id
                AND Biz_mela\Models\ProductImage.product_id = Biz_mela\Models\ProductMaster.id
                AND Biz_mela\Models\ShopMaster.id = $value
                ORDER BY Biz_mela\Models\ProductMaster.id desc
                LIMIT 0 , 30");

            $newresult = $this->modelsManager->executeQuery($phql);

            $paginator = new Paginator(array(
                "data" => $newresult,
                "limit" => 3,
                "page" => $numberPage
            ));
            
            $page['Product'] = $paginator->getPaginate();
            $page['value'] = $value;
            $this->view->setVars($page);    
    		
    }


   



	public function deleteAction($value = '')
	{

			$dump = ShopMaster::findFirst('id = "' . $value . '"');
			
			if ($dump != false) {
    			if ($dump->delete() == false) {
				        echo "Sorry, we can't delete the robot right now: \n";
				        foreach ($dump->getMessages() as $message) {
				            echo $message, "\n";
				        }
			    } else {
			        echo "The Shop was deleted successfully!";
			        return $this->response->redirect('shop/shop/');
			    }
			}

	}


}