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

class CategoryController extends ControllerBase
{
	public function initialize()
    {	
        $this->auth = $auth = $this->session->get('auth');
        if (!$auth) {
            return $this->response->redirect('session/start/');
        }
		
    }

    public function indexAction($ID)
    {
        $data['category_id'] = $ID;

        $form = new Form();

        $form->add(new Check("accessories"));
        $form->add(new Check("bronzers_and_blushers"));
        $form->add(new Check("eye_lashes"));
        $form->add(new Check("eye_liners"));
        $form->add(new Check("eye_shadow"));
        $form->add(new Check("foundation"));
        $form->add(new Check("illuminator"));
        $form->add(new Check("lip_gloss"));
        $form->add(new Check("lip_stick"));

        $form->add(new Check("pop"));
        $form->add(new Check("ren"));
        $form->add(new Check("rimmel_london"));
        $form->add(new Check("river_island"));
        $form->add(new Check("rodial"));
        $form->add(new Check("rose_and_co"));
        $form->add(new Check("stilla"));
        $form->add(new Check("ted_baker"));
        $form->add(new Check("the_balm"));

        $form->add(new Check("beige"));
        $form->add(new Check("blue"));
        $form->add(new Check("clear"));
        $form->add(new Check("cream"));
        $form->add(new Check("green"));

        $form->add(new Check("beige"));
        $form->add(new Check("blue"));
        $form->add(new Check("clear"));
        $form->add(new Check("cream"));
        $form->add(new Check("green"));

        $form->add(new Check("body_and_face"));
        $form->add(new Check("cleanse_and_tone"));

        $form_data['form'] = $form;
        $this->view->setVars($form_data);

        //$low = $this->request->getQuery('limit_low');
        //$high = $this->request->getQuery('limit_high');
        
        //$priceTo = 25000;
       
        $numberPage = $this->request->getQuery("page", "int", 1);

        $bottomResult = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
        ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
        ->leftJoin('Biz_mela\Models\ProductCategory', 'c.id = Biz_mela\Models\ProductMaster.cat_id', 'c')
        ->where('Biz_mela\Models\ProductMaster.cat_id = :name:', array('name' => $ID));
        //->betweenWhere('Biz_mela\Models\ProductMaster.price', 500, 25000)
        //->andWhere('Biz_mela\Models\ProductMaster.price > '.$priceTo)
        //->andWhere('Biz_mela\Models\ProductMaster.price > '.$low)
        //->getQuery()->execute();

        if (($this->request->isGet()) && ($this->request->hasQuery('limit_low')) && ($this->request->hasQuery('limit_high')))
        {
            $low = $this->request->getQuery('limit_low');

            $high = $this->request->getQuery('limit_high');
            //$low=1000;
            //$low = $this->request->getQuery('limit_low');
           // $bottomResult =  $bottomResult->betweenWhere('Biz_mela\Models\ProductMaster.price', $low, $high);
            //->andWhere('Biz_mela\Models\ProductMaster.id != :name1:', array('name1' => $value))
            $bottomResult =  $bottomResult->andWhere('Biz_mela\Models\ProductMaster.price > '.$low);
            $bottomResult =  $bottomResult->andWhere('Biz_mela\Models\ProductMaster.price < '.$high);
            //$bottomResult =  $bottomResult->andWhere('Biz_mela\Models\ProductMaster.price < :high_value:', array('high_value' => $this->request->getQuery('limit_high')));

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

    public function maxAction(){
        $this->view->disable();
        //echo "hello";

        /*
        $phql = ("SELECT max(price)
            FROM Biz_mela\Models\ProductMaster");

        $MAX = $this->modelsManager->executeQuery($phql);

        echo $MAX;
        */

       // $max_price = ProductMaster::maximum(array("column" => "price"));

        //echo $max_price;
        

        $phql = ("SELECT MAX(Biz_mela\Models\ProductMaster.price) AS maximum, MIN(Biz_mela\Models\ProductMaster.price) AS minimum FROM Biz_mela\Models\ProductMaster");
        $rows = $this->modelsManager->executeQuery($phql);
        foreach ($rows as $row)
        {
           echo $row["maximum"], ' ', $row["minimum"], "\n";
        }
    
    }

    public function formtestAction(){
        $this->view->disable();
        if ($this->request->isGet()){

            $price = $this->request->getQuery('max_range');

            $low = $this->request->getQuery('limit_low');
            $high = $this->request->getQuery('limit_high');

            echo $price;

            echo "<br>";

            echo $low;
            echo "<br>";

            echo $high;
        }
    }




}

