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
        	
    }

    public function indexAction($ID)
    {
        $data['category_id'] = $ID;

       /* $phql = ("SELECT Biz_mela\Models\ShopMaster.id,Biz_mela\Models\ShopMaster.shop_name,Biz_mela\Models\ShopMaster.user_id
                
                FROM Biz_mela\Models\ShopMaster
                
                ORDER BY Biz_mela\Models\ShopMaster.id 
                LIMIT 0 , 100");

        $shop = $this->modelsManager->executeQuery($phql);*/

        

        $form = new Form();

        /* foreach ($shop as $value) {
            

            $container[$value->id] =  $value->shop_name;
        }

        $shop_name = new Select("shop_name", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'shop_name',
            'autocomplete' => 'off',
            'multiple'=>'yes'
        ));

        $shop_name->setOptions($container);*/
        /*retrive the shop id query variable. If it is set, keep the corresponding radio button checked after in the search
        result page*/ 

        if ($this->request->isGet()) {
            # code...
            if ($this->request->hasQuery('shop_id')) {
                # code...
                $shop_id_checked = $this->request->getQuery('shop_id');
                $data['checked'] = $shop_id_checked;
            }
        }


        //Shop name
        $shop_collection = ShopMaster::find();
        /* Since phalcon 1.2.4 does not support Form\Element\Radio i am not using form helper in this case. An array to 
         store the shop names and id to pass to the view. It will help to render the form elements in view since ids of 
         these checkboxes are corresponding shop names. */
        $shop_names =  array();
        foreach ($shop_collection as $shop) {
            # code...
            /*
            if ((isset($shop_id_checked)) && ($shop->id == $shop_id_checked)) {
                # code...
                $form->add(new Check($shop->shop_name , 
                array('value' => $shop->id,
                      'name' => 'shop_id',
                      //'checked' => 'checked',
                      'onchange' => 'submit_form()')));
            }
            else{
                $form->add(new Check($shop->shop_name , 
                array('value' => $shop->id,
                      'name' => 'shop_id',
                      'onchange' => 'submit_form()')));
            }
            */
            $shop_names[$shop->id] = $shop->shop_name; 
        }

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
        //CAST(`type` AS SIGNED) CAST('Biz_mela\Models\ProductMaster.price' AS SIGNED) CAST(PROD_CODE AS INT)

        if ($this->request->isGet())
        {   if($this->request->hasQuery('limit_low')){
              $low = $this->request->getQuery('limit_low');
              $bottomResult =  $bottomResult->andWhere('Biz_mela\Models\ProductMaster.price >= '.$low);
              $range['low'] = $low;
            }


            if($this->request->hasQuery('limit_high')){
              $high = $this->request->getQuery('limit_high');
              $bottomResult =  $bottomResult->andWhere('Biz_mela\Models\ProductMaster.price <= '.$high);
              $range['high'] = $high;
              $this->view->setVar(range,$range);

            }

            if ($this->request->hasQuery('shop_id')) {
                # code...
                $shop_id = $this->request->getQuery('shop_id');
                $bottomResult =  $bottomResult->andWhere('Biz_mela\Models\ProductMaster.shop_id = '.$shop_id);

            }

            //$high = $this->request->getQuery('limit_high');
            //$low=1000;
            //$low = $this->request->getQuery('limit_low');
           // $bottomResult =  $bottomResult->betweenWhere('Biz_mela\Models\ProductMaster.price', $low, $high);
            //->andWhere('Biz_mela\Models\ProductMaster.id != :name1:', array('name1' => $value))
            
            //$bottomResult =  $bottomResult->andWhere('Biz_mela\Models\ProductMaster.price <= '.$high);
            //$bottomResult = $bottomResult->andWhere('Biz_mela\Models\ProductMaster.price <= :name1:', array('name1' => $high));
            //$bottomResult =  $bottomResult->betweenWhere(CAST('Biz_mela\Models\ProductMaster.price' AS INT), $low, $high);
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
        $this->view->setVar(shop_names,$shop_names);
        

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

        $max_price = ProductMaster::maximum(array("column" => "price"));

        echo $max_price;
        /*
        

        $phql = ("SELECT MAX(Biz_mela\Models\ProductMaster.price) AS maximum, MIN(Biz_mela\Models\ProductMaster.price) AS minimum FROM Biz_mela\Models\ProductMaster");
        $rows = $this->modelsManager->executeQuery($phql);
        foreach ($rows as $row)
        {
           echo $row["maximum"], ' ', $row["minimum"], "\n";
        }
        */

        echo $this->router->getControllerName();
        echo "<br>";
        echo $this->router->getActionName();
    
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


    public function menuAction(){
        $this->view->disable();

        $categories = ProductCategory::find();

        //echo count($categories);

        //echo "<br>";

        $count = 0;

        echo "<table><tr>";
        foreach ($categories as $entry) {
                echo '<td><a href="'.$this->url->get()."category/index/".$entry->id.'">'.$entry->cat_name.'</a></td>';
                $count++;
                if ($count%3==0) {
                    if($count >= count($categories))
                     echo "</tr>";

                    else
                        echo "</tr><tr>";
                }
               }

        echo "</tr></table>";
    }






}

