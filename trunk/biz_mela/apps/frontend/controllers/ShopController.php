<?php
namespace Biz_mela\Frontend\Controllers;

use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\ProductMaster as ProductMaster;
use Biz_mela\Models\ProductImage as ProductImage;
use Biz_mela\Models\ProductWishlist as ProductWishlist;
use Biz_mela\Models\OrderDetails as OrderDetails;
use Biz_mela\Models\ShopMaster as ShopMaster;
use Biz_mela\Models\ProductCategory as ProductCategory;

use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\View;
use Phalcon\Tag as Tag,
Phalcon\Forms\Element\Text,
Phalcon\Forms\Element\Check,
Phalcon\Validation\Validator\PresenceOf,

Phalcon\Forms\Form;

class ShopController extends ControllerBase
{
	public function initialize()
    {	
      /*
        $this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            return $this->response->redirect('session/start/');
        }
        */
		
    }

    public function indexAction($ID)
    {
       $data['shop_id'] = $ID;

       $bannerData = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ShopMaster')
        ->where('Biz_mela\Models\ShopMaster.id = :name:', array('name' => $ID))
        ->getQuery()
        ->execute();

       $bannerImage = $bannerData[0]->banner;

       $data['shop_name'] = $bannerData[0]->shop_name;

       if (!is_null($bannerImage)) {
           # code...
           $data['banner'] = $bannerImage;
       }

       $collection = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductMaster')
                  ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
                  ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
                  ->where('Biz_mela\Models\ProductMaster.shop_id = :id_of_shop:', array('id_of_shop' => $ID))
                  ->orderBy('Biz_mela\Models\ProductMaster.created_at desc')
                  ->limit(4)
                  ->getQuery()
                  ->execute();
       $this->view->setVar(collection,$collection);

       /* Render form */
       $form = new Form();

       /*Gender*/

        $form->add(new Check("men"));
        $form->add(new Check("women"));

       /*Product Type */
        $form->add(new Check("sunglasses"));
        $form->add(new Check("swimsuits"));
        $form->add(new Check("tops"));
        $form->add(new Check("trainers"));
        $form->add(new Check("trowsers"));
        $form->add(new Check("tshirts"));
        $form->add(new Check("vests"));

        /*Condition */

        $form->add(new Check("new"));
        $form->add(new Check("pre_owned"));
        $form->add(new Check("vintage"));
        
        /*Brand*/
        $form->add(new Check("pop"));
        $form->add(new Check("ren"));
        $form->add(new Check("rimmel_london"));
        $form->add(new Check("river_island"));
        $form->add(new Check("rodial"));
        $form->add(new Check("rose_and_co"));
        $form->add(new Check("stilla"));
        $form->add(new Check("ted_baker"));
        $form->add(new Check("the_balm"));

        /*Color*/
        $form->add(new Check("beige"));
        $form->add(new Check("blue"));
        $form->add(new Check("clear"));
        $form->add(new Check("cream"));
        $form->add(new Check("green"));

        
        /*Style*/
        $form->add(new Check("body_and_face"));
        $form->add(new Check("cleanse_and_tone"));

        $form_data['form'] = $form;
        $this->view->setVars($form_data);

       

       $bottomResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductMaster')
                  ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
                  ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
                  ->where('Biz_mela\Models\ProductMaster.shop_id = :id_of_shop:', array('id_of_shop' => $ID));

       if (($this->request->isGet()) && ($this->request->hasQuery('limit_low')) && ($this->request->hasQuery('limit_high')))
        {
            $low = $this->request->getQuery('limit_low');

            $high = $this->request->getQuery('limit_high');
            //$low=1000;
            //$low = $this->request->getQuery('limit_low');
           // $bottomResult =  $bottomResult->betweenWhere('Biz_mela\Models\ProductMaster.price', $low, $high);
            //->andWhere('Biz_mela\Models\ProductMaster.id != :name1:', array('name1' => $value))
            $bottomResult =  $bottomResult->andWhere('Biz_mela\Models\ProductMaster.price >= '.$low);
            $bottomResult =  $bottomResult->andWhere('Biz_mela\Models\ProductMaster.price <= '.$high);
            //$bottomResult = $bottomResult->andWhere('Biz_mela\Models\ProductMaster.price <= :name1:', array('name1' => $high));
            //$bottomResult =  $bottomResult->betweenWhere(CAST('Biz_mela\Models\ProductMaster.price' AS INT), $low, $high);
            //$bottomResult =  $bottomResult->andWhere('Biz_mela\Models\ProductMaster.price < :high_value:', array('high_value' => $this->request->getQuery('limit_high')));
            $range['low'] = $low;
            $range['high'] = $high;
            $this->view->setVar(range,$range);


        }
        
       $bottomResult =  $bottomResult->getQuery()->execute();

       $paginator = new Paginator(array(
            "data" => $bottomResult,
            "limit" => 20,
            "page" => $numberPage
        ));

        $page['Product'] = $paginator->getPaginate();
        $page['value'] = $value;
        $this->view->setVars($page);


       $this->view->setVar(data,$data);

    }

}

