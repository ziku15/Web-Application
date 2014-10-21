<?php

namespace Biz_mela\Frontend\Controllers;

use Biz_mela\Models\UserMaster as UserMaster;
use Biz_mela\Models\ProductMaster as ProductMaster;
use Biz_mela\Models\ProductImage as ProductImage;
use Biz_mela\Models\ProductWishlist as ProductWishlist;
use Biz_mela\Models\OrderDetails as OrderDetails;
use Biz_mela\Models\ShopMaster as ShopMaster;
use Phalcon\Mvc\View;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        // $a=UserMaster::find();
        // var_dump($a);
        // $this->view->disable();

        

        $newResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductMaster')
                  ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
                  ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
                  ->orderBy('Biz_mela\Models\ProductMaster.created_at desc')
                  ->limit(4)
                  ->getQuery()
                  ->execute();

        $this->view->setVar(newResult,$newResult);


        $hotResult = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
        ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
        ->where('is_hot = 1')
        ->getQuery()
        ->execute();
        $this->view->setVar(hotResult,$hotResult);

        $bestResult = $this->modelsManager->createBuilder()
                  ->from('Biz_mela\Models\ProductMaster')
                  ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture, o.product_id')
                  ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
                  ->join('Biz_mela\Models\OrderDetails', 'o.product_id = Biz_mela\Models\ProductMaster.id', 'o','right')
                  ->groupBy('o.product_id')
                  ->orderBy('count(o.product_id) desc')
                  ->limit(4)
                  ->getQuery()
                  ->execute();
        $this->view->setVar(bestResult,$bestResult);

        $bottomResult = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
        ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
        ->limit(16)
        ->getQuery()
        ->execute();
        $this->view->setVar(bottomResult,$bottomResult);

        

    }

    public function wishlistAction()
    {
      $this->view->disable();

      $wish = $this->request->getPost('data1');

      //echo $wish;
      
      $username = $this->session->get('auth');
      $con=UserMaster::findFirst("username="."'".$username['name']."'");
      $user_id=$con->id;

      
      $product_data = ProductMaster::findFirst("id="."'".$wish."'");
      $product_name= $product_data->product_name;

     

     //$timestamp = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))); 
     //$timestamp = date('Y-m-d H:i:s'); 

     $Result = ProductWishlist::find("user_id="."'".$user_id."'and product_id="."'".$wish."'");

     $count = count($Result);

     $auth = $this->session->get('auth');
     if(!$auth){

      echo "1";
     }

     else if($count>0){

      //echo $product_name." exists in your wish list";

      //echo '<font color="red">'.$product_name." exists in your wish list".'</font>';
      echo "2";

      //return "123";



     }

     else {
      // Specifying columns to insert
     $phql = "INSERT INTO Biz_mela\Models\ProductWishlist (product_id, user_id, status, updated_at, created_at) "
      . "VALUES ($wish, $user_id, 1, now(), now())";
     $this->modelsManager->executeQuery($phql);

     //echo $product_name." is added to your wish list";

     //echo '<font color="green">'.$product_name." is added to your wish list".'</font>';
     echo "3";

     //return "456";

     }
    
    
    }

    public function addtocartAction(){
      $this->view->disable();

      $product_id = $this->request->getPost('data1');

      
   
      $number_of_days = 30 ;
      $date_of_expiry = time() + 60 * 60 * 24 * $number_of_days ; 

        $cookie_count = unserialize($_COOKIE['product']);
        if(!in_array($product_id, $cookie_count)){
            $cookie_count[] = $product_id;
            $value = serialize($cookie_count);

            setcookie( 'product', $value, $date_of_expiry, "/" );
            echo count($cookie_count);
        }

    }

    public function updateCookieAction(){
      $this->view->disable();

      $product_id = $this->request->getPost('data1');

      $number_of_days = 30 ;
      $date_of_expiry = time() + 60 * 60 * 24 * $number_of_days ; 

        $cookie_count = unserialize($_COOKIE['product']);
        $cookie_count = array_diff($cookie_count, array($product_id));
        $value = serialize($cookie_count);

        setcookie( 'product', $value, $date_of_expiry, "/" );
        echo count($cookie_count);

    }

    public function displayAction(){
      $this->view->disable();

      print_r($_COOKIE);

      $cookie_count = unserialize($_COOKIE['product']);

      echo '<br>';

      foreach ($cookie_count as $entry) {
        # code...
        echo $entry;
        echo '<br>';
      }

      print_r($cookie_count);
      /*

      $product_data = ProductMaster::findFirst("id="."'".$wish."'");

      foreach ($cookie_count as $entry) {
        # code...
        $product_data = ProductMaster::findFirst("id="."'".$entry."'");
        echo $product_data->product_name;
      }*/

      $cResult = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
        ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
        ->inWhere('Biz_mela\Models\ProductMaster.id', $cookie_count)
        ->getQuery()
        ->execute();

      //print_r($cResult);

      echo count($cResult);

      foreach ($cResult as $item) {
        # code...
        echo $item->product_name;
        echo $item->price;
        echo $item->picture;
        echo '<br>';
      }




    }

    public function clearAction(){
      $this->view->disable();
      $date_of_expiry = time() - 60 ;
        
      setcookie( 'product', '', $date_of_expiry, "/" );


    }

    public function shoppingcartAction(){
      //$this->view->disable();

      $this->view->disableLevel(View::LEVEL_LAYOUT);

      $cookie_count = unserialize($_COOKIE['product']);

      $cResult = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture')
        ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
        ->inWhere('Biz_mela\Models\ProductMaster.id', $cookie_count)
        ->getQuery()
        ->execute();
      if(count($cookie_count) > 0){
        $this->view->setVar(cResult,$cResult);
      }

      else{
        $this->view->disable();
        echo "No products to show";

      }

    }

    public function productdetailsAction($value){
      $this->view->disableLevel(View::LEVEL_LAYOUT);
      //$this->view->disable();
      //echo $value;
      
      $detailResult = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->columns('Biz_mela\Models\ProductMaster.id, Biz_mela\Models\ProductMaster.price, Biz_mela\Models\ProductMaster.product_name, Biz_mela\Models\ProductMaster.product_description, p.picture, s.shop_name')
        ->leftJoin('Biz_mela\Models\ProductImage', 'p.product_id = Biz_mela\Models\ProductMaster.id', 'p')
        ->leftJoin('Biz_mela\Models\ShopMaster', 's.id = Biz_mela\Models\ProductMaster.shop_id', 's')
        ->where('Biz_mela\Models\ProductMaster.id = :name:', array('name' => $value))
        ->getQuery()
        ->execute();
      

        //$a=$detailResult::findFirst();

        //echo $a->product_name;

        //foreach ($detailResult as $key) {
          # code...
          //echo $key->shop_name;
        //}
        $data['shop_name'] = $detailResult[0]->shop_name;
        $data['product_name'] = $detailResult[0]->product_name;
        $data['price'] = $detailResult[0]->price;
        $data['description'] = $detailResult[0]->product_description;
        $data['picture'] = $detailResult[0]->picture;
        
        $this->view->setVar(data,$data);

        //echo $detailResult[0]->shop_name;
        //echo $detailResult[0]->product_name;
         //echo $detailResult[0]->product_description;
          //echo $detailResult[0]->price;
          // echo $detailResult[0]->picture;

    }


    public function checkStockAction(){
      $this->view->disable();

      $product_id = $this->request->getPost('data1');

      $Result = $this->modelsManager->createBuilder()
        ->from('Biz_mela\Models\ProductMaster')
        ->where('Biz_mela\Models\ProductMaster.id = :name:', array('name' => $product_id))
        ->getQuery()
        ->execute();

      echo $Result[0]->in_stock;

    }



}

